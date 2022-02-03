<?php

namespace App\Service\Api;

use App\Entity\Club;
use App\Entity\Season;
use App\Service\Api\Odd\OddData;
use App\Service\Api\Odd\OddService;
use App\Service\Club\ClubService;
use App\Service\Import\RawFileImporter;
use App\Service\LiveFormTable\LiveFormTableProvider;
use App\Service\MatchGame\MatchDayGameData;
use App\Service\MatchGame\MatchDayGameService;
use App\Service\Season\SeasonService;
use DateTime;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OddApiGateway
{
    private const BASE_URI = 'https://api.the-odds-api.com/v4/sports';
    private const API_KEY = '838fee31b4b6b888826bf89514fdbc2d';
    private const LEAGUES = [
        'it1' => 'soccer_italy_serie_a',
        'it2' => 'soccer_italy_serie_b',
        'es1' => 'soccer_spain_la_liga',
        'es2' => 'soccer_spain_segunda_division',
        'en1' => 'soccer_epl',
        'en2' => 'soccer_efl_champ',
        'fr1' => 'soccer_france_ligue_one',
        'fr2' => 'soccer_france_ligue_two',
        'de1' => 'soccer_germany_bundesliga',
        'de2' => 'soccer_germany_bundesliga2',
    ];

    /**
     * @var HttpClientInterface
     */
    private $client;
    /**
     * @var SeasonService
     */
    private $seasonService;
    /**
     * @var LiveFormTableProvider
     */
    private $liveFormTableProvider;
    /**
     * @var ClubService
     */
    private $clubService;

    /**
     * @var MatchDayGameService
     */
    private $matchDayGameService;
    /**
     * @var OddService
     */
    private $oddService;

    public function __construct(
        HttpClientInterface $client,
        SeasonService $seasonService,
        LiveFormTableProvider $liveFormTableProvider,
        ClubService $clubService,
        MatchDayGameService $matchDayGameService,
        OddService $oddService
    )
    {
        $this->client = $client;
        $this->seasonService = $seasonService;
        $this->liveFormTableProvider = $liveFormTableProvider;
        $this->clubService = $clubService;
        $this->matchDayGameService = $matchDayGameService;
        $this->oddService = $oddService;
    }

    /**
     * @param string $league
     * @return array
     * @throws ORMException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function getOddsForLeague(string $league): array
    {
        // get odds from api
        $url = self::BASE_URI . '/' . self::LEAGUES[$league] . '/odds?regions=eu&apiKey=' . self::API_KEY;
        $response = $this->client->request(
            'GET',
            $url
        );

        $content = $response->toArray();

        // create MatchGames for current matchDays
        /** @var Season $currentSeason */
        $currentSeason = $this->seasonService->getSeasonByLeagueAndStartYear($league, 2021);
        $currentMatchDay = $this->liveFormTableProvider->getMatchDayByLeague($league);

        foreach ($content as $match){
            // parse infos from odd api
            $matchDayGameData = new MatchDayGameData();
            $matchDayGameData->setKickoffDay(new DateTime($match['commence_time']));
            $matchDayGameData->setSeason($currentSeason);
            $matchDayGameData->setMatchDay($currentMatchDay);

            // get clubs
            $homeClub = $this->clubService->findClubByName($match['home_team']);
            if (is_null($homeClub)){
                $homeClub = $this->findClubByBetApiClubIdent($currentSeason, $match['home_team']);
            }
            $matchDayGameData->setHomeTeam($homeClub);

            $awayClub = $this->clubService->findClubByName($match['away_team']);
            if (is_null($awayClub)){
                $awayClub = $this->findClubByBetApiClubIdent($currentSeason, $match['away_team']);
            }
            $matchDayGameData->setAwayTeam($awayClub);

            // init with 0
            $matchDayGameData->setHomeGoalsFirst(0);
            $matchDayGameData->setHomeGoalsSecond(0);
            $matchDayGameData->setAwayGoalsFirst(0);
            $matchDayGameData->setAwayGoalsSecond(0);
            $matchDayGameData->setPlayed(false);

            // check if match already exists (cause last api call was just a few days before)
            if(!$this->matchDayGameService->checkIfMatchAlreadyExists($matchDayGameData)){
                $matchDayGame = $this->matchDayGameService->createByData($matchDayGameData);
            }else{
                $matchDayGame = $this->matchDayGameService->getMatchByTeamsAndSeason(
                    $homeClub,
                    $awayClub,
                    $currentSeason
                );
            }

            // after match creation we can store the received bets
            foreach ($match['bookmakers'] as $bookmaker){
                $oddData = new OddData();
                $oddData->setOddProvider($bookmaker['title']);
                $outcomes = $bookmaker['markets'][0]['outcomes'];

                $oddData->setHomeOdd($outcomes[$this->getCorrectOutCome($outcomes, $match['home_team'])]['price']);
                $oddData->setAwayOdd($outcomes[$this->getCorrectOutCome($outcomes, $match['away_team'])]['price']);
                $oddData->setDrawOdd($outcomes[$this->getCorrectOutCome($outcomes, 'Draw')]['price']);

                $oddData->setMatchDayGame($matchDayGame);
                $this->oddService->createByData($oddData);
            }
        }

        return $content;
    }

    private function getCorrectOutCome(array $outcomes, string $searchedOutcome): int
    {
        if ($searchedOutcome == $outcomes[0]['name']){
            return 0;
        }
        elseif ($searchedOutcome == $outcomes[1]['name']){
            return 1;
        }
        elseif ($searchedOutcome == $outcomes[2]['name']){
            return 2;
        }
        return null;
    }

    /**
     * @param Season $currentSeason
     * @param string $searchedClub
     * @return Club
     */
    private function findClubByBetApiClubIdent(Season $currentSeason, string $searchedClub): Club
    {
        $allClubs = $currentSeason->getClubs();
        $similarity = array();
        foreach ($allClubs as $potentialClub) {
            similar_text($searchedClub, $potentialClub->getName(), $percentage);
            $similarity[$potentialClub->getId()] = $percentage;
        }
        arsort($similarity);
        return $this->clubService->findClubById(array_key_first($similarity));
    }
}