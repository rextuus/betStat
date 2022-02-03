<?php


namespace App\Service\LiveFormTable;


use App\Entity\Season;
use App\Service\Club\ClubService;
use App\Service\League\LeagueService;
use App\Service\MatchGame\MatchDayGameService;
use App\Service\Season\SeasonService;
use App\Service\UrlResponseBackup\UrlResponseBackupService;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use DOMElement;
use ErrorException;
use Exception;
use Proxies\__CG__\App\Entity\UrlResponseBackup;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class LiveFormTableProvider
{

    const BASE_URL = 'https://www.transfermarkt.de';

    /**
     * @var LeagueService
     */
    private $leagueService;

    private $leagues = [
        'en1' => [
            'url' => 'https://www.transfermarkt.de/premier-league/spieltagtabelle/wettbewerb/GB1/saison_id/2021',
            'teams' => 20
        ],
        'en2' => [
            'url' => 'https://www.transfermarkt.de/championship/spieltagtabelle/wettbewerb/GB2/saison_id/2021',
            'teams' => 24
        ],
        'it1' => [
            'url' => 'https://www.transfermarkt.de/serie-a/spieltagtabelle/wettbewerb/IT1/saison_id/2021',
            'teams' => 20
        ],
        'it2' => [
            'url' => 'https://www.transfermarkt.de/serie-b/spieltagtabelle/wettbewerb/IT2/saison_id/2021',
            'teams' => 20
        ],
        'es1' => [
            'url' => 'https://www.transfermarkt.de/laliga/spieltagtabelle/wettbewerb/ES1/saison_id/2021',
            'teams' => 20
        ],
        'es2' => [
            'url' => 'https://www.transfermarkt.de/laliga2/spieltagtabelle/wettbewerb/ES2/saison_id/2021',
            'teams' => 22
        ],
        'fr1' => [
            'url' => 'https://www.transfermarkt.de/ligue-1/spieltagtabelle/wettbewerb/FR1/saison_id/2021',
            'teams' => 20
        ],
        'fr2' => [
            'url' => 'https://www.transfermarkt.de/ligue-2/spieltagtabelle/wettbewerb/FR2/saison_id/2021',
            'teams' => 20
        ],
        'de1' => [
            'url' => 'https://www.transfermarkt.de/de1/spieltagtabelle/wettbewerb/L1/saison_id/2021',
            'teams' => 18
        ],
        'de2' => [
            'url' => 'https://www.transfermarkt.de/2-de1/spieltagtabelle/wettbewerb/L2/saison_id/2021',
            'teams' => 18
        ],
    ];
    /**
     * @var ClubService
     */
    private $clubService;
    /**
     * @var MatchDayGameService
     */
    private $matchDayGameService;
    /**
     * @var SeasonService
     */
    private $seasonService;
    /**
     * @var UrlResponseBackupService
     */
    private $urlResponseBackupService;

    /**
     * LiveFormTableProvider constructor.
     * @param LeagueService $leagueService
     * @param ClubService $clubService
     * @param MatchDayGameService $matchDayGameService
     * @param SeasonService $seasonService
     * @param UrlResponseBackupService $urlResponseBackupService
     */
    public function __construct(
        LeagueService $leagueService,
        ClubService $clubService,
        MatchDayGameService $matchDayGameService,
        SeasonService $seasonService,
        UrlResponseBackupService $urlResponseBackupService
    )
    {
        $this->leagueService = $leagueService;
        $this->clubService = $clubService;
        $this->matchDayGameService = $matchDayGameService;
        $this->seasonService = $seasonService;
        $this->urlResponseBackupService = $urlResponseBackupService;
    }

    /**
     * @return CandidateResult
     * @throws NonUniqueResultException
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getAllCandidatesForWeekend(): CandidateResult
    {
        $candidates = array();
        $errors = array();
        foreach ($this->leagues as $leagueName => $league) {
            if (in_array($leagueName, [])) {
                continue;
            }

            // Get matches for league
            /** @var Season $currentSeason */
            $currentSeason = $this->seasonService->getSeasonByLeagueAndStartYear($leagueName, 2021);

            $matchDayUrl = self::BASE_URL . $this->getMatchDayLinkByLeague($leagueName);
            $matches = [];
            try {
                // get html raw site
                $httpClient = HttpClient::create();
                $response = $httpClient->request('GET', $matchDayUrl);

                $matches = $this->calculateLiveForms($response->getContent());
            } catch (Exception $e) {
                $errors[] = $leagueName;
            }

            // check if there was errors => try some backup urls
            if (in_array($leagueName, $errors)){
                dump($currentSeason->getLeague());
                dump($this->getMatchDayByLeague($leagueName));
                $urlBackups = $this->urlResponseBackupService->findByLeague(
                    $currentSeason->getLeague(),
                    $this->getMatchDayByLeague($leagueName)
                );


                if (!isEmpty($urlBackups) && $matchDayUrl == $urlBackups[0]->getUrl()){
                    $matches = $this->calculateLiveForms($urlBackups[0]->getRawContent());
                }
            }

            // check matches for candidates to bet on
            foreach ($matches as $liveFormEntry) {
                /** @var LiveFormEntry $liveFormEntry */
                $teamToBetOn = $liveFormEntry->checkForBetCandidate();
                if (!is_null($teamToBetOn)) {
                    $candidates[] = $liveFormEntry;

                    // find clubs
                    $targetTeam = $this->clubService->findClubByName($teamToBetOn);
                    $homeTeam = $this->clubService->findClubByName($liveFormEntry->getHomeTeamName());
                    $awayTeam = $this->clubService->findClubByName($liveFormEntry->getAwayTeamName());
                    if (is_null($homeTeam)) {
                        dump("Home fehlt");
                        dump($liveFormEntry->getHomeTeamName());
                        dump($liveFormEntry->getAwayTeamName());
                    }
                    if (is_null($awayTeam)) {
                        dump("Away fehlt");
                        dump($liveFormEntry->getAwayTeamName());
                        dump($liveFormEntry->getHomeTeamName());
                    }

                    // find match
                    try {
                        $correspondingMatch = $this->matchDayGameService->getMatchByTeamsAndSeason($homeTeam, $awayTeam, $currentSeason);
                    } catch (NoResultException $exception) {
                        $errors[] = $leagueName;
                        dump($exception);
                        dump($homeTeam->getName());
                        dump($awayTeam->getName());
                        continue;
                    }

                    // get odds
                    $odds = $correspondingMatch->getOdds();
                    $meanDrawOdd = 0.0;
                    $meanTargetOdd = 0.0;
                    // middle odds
                    foreach ($odds as $odd) {
                        $meanDrawOdd = $meanDrawOdd + $odd->getDrawOdd();
                        if ($correspondingMatch->getTargetTeam($targetTeam) == 1) {
                            $meanTargetOdd = $meanTargetOdd + $odd->getHomeOdd();
                        }
                        if ($correspondingMatch->getTargetTeam($targetTeam) == 2) {
                            $meanTargetOdd = $meanTargetOdd + $odd->getAwayOdd();
                        }
                    }
                    $meanDrawOdd = $meanDrawOdd / count($odds);
                    $meanTargetOdd = $meanTargetOdd / count($odds);
                    $liveFormEntry->setDrawOdd($meanDrawOdd);
                    $liveFormEntry->setTargetOdd($meanTargetOdd);
                }
            }
        }

        usort($candidates, function (LiveFormEntry $a, LiveFormEntry $b) {
            return ($a->getKickOff() < $b->getKickOff()) ? -1 : 1;
        });

        $candidateResult = new CandidateResult();
        $candidateResult->setMatches($candidates);
        $candidateResult->setErrors($errors);

        return $candidateResult;
    }

    /**
     * @param string $leagueIdent
     * @return string
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getMatchDayLinkByLeague(string $leagueIdent)
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', $this->leagues[$leagueIdent]['url']);

        $crawler = new Crawler($response->getContent());

        $linkCrawler = $crawler->selectLink('Der komplette Spieltag');
        return $linkCrawler->getNode(0)->attributes->item(0)->nodeValue;
    }

    /**
     * @param string $httpResponseContent
     * @return array
     */
    public function calculateLiveForms(string $httpResponseContent)
    {
        // get html raw site
//        $httpClient = HttpClient::create();
//        $response = $httpClient->request('GET', $url);

        // remove spaces
        $cleanHttpResponseContent = preg_replace("/\s+/", " ", $httpResponseContent);

        // get clubs
        $clubs = $this->extractClubsFromResponse($cleanHttpResponseContent);

        $matchCandidates = array();
        foreach ($clubs as $club) {
            $liveFormEntry = new LiveFormEntry();
            $liveFormEntry->setHomeTeamName($club[0]);
            $liveFormEntry->setAwayTeamName($club[1]);
            $matchCandidates[] = $liveFormEntry;
        }

        $crawler = new Crawler($httpResponseContent);

        // get home serieses
        $homeFormCrawler = $crawler->filter('td[class="rechts-no-padding hide-for-small"]');
        foreach ($homeFormCrawler as $index => $domElement) {
            $matchCandidates[$index]->setHomeTeamIsCandidate($this->checkSeries($domElement->nodeValue));
        }

        // get away serieses
        $awayFormCrawler = $crawler->filter('td[class="hide-for-small"]');
        foreach ($awayFormCrawler as $index => $domElement) {
            $matchCandidates[$index]->setAwayTeamIsCandidate($this->checkSeries($domElement->nodeValue));
        }

        // get the kickoff times TODO guess its not necessary cause kickoff time is also provided via odds api
        $kickOffCrawler = $crawler->filter('td[class="zentriert no-border"]');
        foreach ($kickOffCrawler as $index => $domElement) {
            /** @var DOMElement $domElement */
            $dateString = '15:30';
            foreach ($domElement->childNodes as $element) {

                /** @var DOMElement $element */
                if (preg_match('~\d\d\.\d\d\.\d\d\d\d~', trim($element->nodeValue)) != 0) {
                    $dateString = trim($element->nodeValue);
                }
                if (preg_match('~\d\d:\d\d Uhr~', trim($element->nodeValue)) != 0) {
                    preg_match('~\d\d:\d\d~', trim($element->nodeValue), $matches);
                    $dateString = $dateString . ' ' . $matches[0];
                }
            }

            $matchCandidates[$index]->setKickOff((new DateTime($dateString)));
        }

        return $matchCandidates;
    }




    /**
     * @param string $cleanHttpResponseContent
     * @return array
     */
    private function extractClubsFromResponse(string $cleanHttpResponseContent): array
    {
        $regexForTeams = '~spieltagsansicht-vereinsname.*?<a title="(.*?)"~';
        preg_match_all($regexForTeams, $cleanHttpResponseContent, $teams);

        // TODO switch clubs from an 2d array to an obkect
        $clubs = array();
        $matchNr = 0;

        $switch = true;
        foreach ($teams[1] as $nr => $team) {
            if ($nr % 2 == 0) {
                if ($switch) {
                    $clubs[$matchNr][0] = $team;
                    $switch = false;
                } else {
                    $clubs[$matchNr][1] = $team;
                    $switch = true;
                    $matchNr++;
                }
            }
        }
        return $clubs;
    }

    /**
     * @param string $series
     * @return bool
     */
    private function checkSeries(string $series)
    {
        $pattern = '~\s~';
        $extractedSeries = preg_split($pattern, $series, -1, PREG_SPLIT_NO_EMPTY);

        $length = count($extractedSeries);
        if ($length > 1) {
            if ($extractedSeries[$length - 1] == "S" && $extractedSeries[$length - 2] != "S") {
                return true;
            } else {
                return false;
            }
        } else {
            if ($extractedSeries[$length - 1] == "S") {
                return true;
            } else {
                return false;
            }
        }
    }

    public function getMatchDayByLeague(string $leagueIdent)
    {
        $link = $this->getMatchDayLinkByLeague($leagueIdent);
        $matchDay = explode('/', $link);
        return (int)$matchDay[count($matchDay) - 1];
    }

    public function getClubsOfLeague(string $leagueIdent)
    {
        $numberOfTeams = $this->leagues[$leagueIdent]['teams'];

        $matchDayUrl = 'https://www.transfermarkt.de' . $this->getMatchDayLinkByLeague($leagueIdent);

        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', $matchDayUrl);

        $crawler = new Crawler($response->getContent());

        $teamCrawler = $crawler->filter('a[class="vereinprofil_tooltip"]');

        $clubs = array();
        $count = 0;
        $matchNr = 0;

        foreach ($teamCrawler as $index => $domElement) {
            if ($domElement->nodeValue != '' && $count < $numberOfTeams * 2) {
                if ($count % 2 == 0) {
                    if ($count % 4 == 0) {
                        $clubs[$matchNr][0] = $domElement->nodeValue;
                    } else {
                        $clubs[$matchNr][1] = $domElement->nodeValue;
                        $matchNr++;
                    }
                }
                $count++;
            }
        }

        $clean = array();
        foreach ($clubs as $club) {
            $clean = array_merge($clean, $club);
        }

        return $clean;
    }
}