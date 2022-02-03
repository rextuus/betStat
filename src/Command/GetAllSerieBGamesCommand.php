<?php


namespace App\Command;


use App\Entity\League;
use App\Service\Api\OddApiGateway;
use App\Service\Api\OddSet\OddData;
use App\Service\Api\OddSet\OddService;
use App\Service\Api\PinnacleGateway;
use App\Service\Import\RawFileImporter;
use App\Service\League\LeagueService;
use App\Service\LiveFormTable\LiveFormEntry;
use App\Service\LiveFormTable\LiveFormTableProvider;
use App\Service\Season\SeasonService;
use Doctrine\ORM\ORMException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GetAllSerieBGamesCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'bet:store:games:serieB';
    /**
     * @var LiveFormTableProvider
     */
    private $liveFormTableProvider;

    /**
     * @var OddApiGateway
     */
    private $oddApiGateway;

    /**
     * ReadRawDataCommand constructor.
     * @param LiveFormTableProvider $liveFormTableProvider
     * @param OddApiGateway $pinnacleGateway
     */
    public function __construct(
        LiveFormTableProvider $liveFormTableProvider,
        OddApiGateway $pinnacleGateway
    )
    {
        $this->liveFormTableProvider = $liveFormTableProvider;
        $this->oddApiGateway = $pinnacleGateway;
        parent::__construct();
    }


    protected function configure(): void
    {
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws ORMException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $en1 = ['url' => 'https://www.transfermarkt.de/premier-league/spieltag/wettbewerb/GB1/spieltag/7/saison_id/2021', 'teams' => 20];
        $en2 = ['url' => 'https://www.transfermarkt.de/championship/spieltag/wettbewerb/GB2/spieltag/11/saison_id/2021', 'teams' => 24];
        $it1 = ['url' => 'https://www.transfermarkt.de/serie-a/spieltag/wettbewerb/IT1/spieltag/7/saison_id/2021', 'teams' => 20];
        $it2 = ['url' => 'https://www.transfermarkt.de/serie-b/spieltag/wettbewerb/IT2/spieltag/7/saison_id/2021', 'teams' => 20];
        $sp1 = ['url' => 'https://www.transfermarkt.de/laliga/spieltag/wettbewerb/ES1/spieltag/8/saison_id/2021', 'teams' => 20];
        $sp2 = ['url' => 'https://www.transfermarkt.de/laliga2/spieltag/wettbewerb/ES2/spieltag/8/saison_id/2021', 'teams' => 20];
        $fr1 = ['url' => 'https://www.transfermarkt.de/ligue-1/spieltag/wettbewerb/FR1/spieltag/9/saison_id/2021', 'teams' => 20];
        $fr2 = ['url' => 'https://www.transfermarkt.de/ligue-2/spieltag/wettbewerb/FR2/spieltag/11/saison_id/2021', 'teams' => 20];
        $buli1 = ['url' => 'https://www.transfermarkt.de/de1/spieltag/wettbewerb/L1/spieltag/7/saison_id/2021', 'teams' => 18];
        $buli2 = ['url' => 'https://www.transfermarkt.de/2-de1/spieltag/wettbewerb/L2/spieltag/9/saison_id/2021', 'teams' => 18];

        $leagues = [
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
                'teams' => 20
            ],
            'fr1' => [
                'url' => 'https://www.transfermarkt.de/ligue-1/spieltagtabelle/wettbewerb/FR1/saison_id/2021',
                'teams' => 20
            ],
            'fr2' => [
                'url' => 'https://www.transfermarkt.de/ligue-2/spieltagtabelle/wettbewerb/FR2/saison_id/2021',
                'teams' => 20
            ],
            'bl1' => [
                'url' => 'https://www.transfermarkt.de/de1/spieltagtabelle/wettbewerb/L1/saison_id/2021',
                'teams' => 18
            ],
            'bl2' => [
                'url' => 'https://www.transfermarkt.de/2-de1/spieltagtabelle/wettbewerb/L2/saison_id/2021',
                'teams' => 18
            ],
        ];

        $url = 'https://www.transfermarkt.de/premier-league/spieltagtabelle/wettbewerb/GB1/saison_id/2021';
        $url = 'https://www.transfermarkt.de/2-de1/spieltagtabelle/wettbewerb/L2/saison_id/2021';

        //$matchDayUrl = 'https://www.transfermarkt.de' . $this->liveFormTableProvider->getMatchDayLinkByLeague($url);

        // $matches = $this->liveFormTableProvider->calculateLiveForms($matchDayUrl, 18);
        //$this->liveFormTableProvider->getAllCandidatesForWeekend();

        $leagueIdents = ['de1', 'de2', 'en1', 'en2', 'it1', 'it2', 'es1', 'es2', 'fr1', 'fr2'];

        foreach ($leagueIdents as $ident){
            $this->oddApiGateway->getOddsForLeague($ident);
            dump("New Matches for current ".$ident." matchday successfull stored");
        }
        return Command::SUCCESS;
    }

    private function clubIsPartOfMatch(string $club, array $matches)
    {
        $highestFittingMatch = [];
        $highestFittingMatchScore = 0;
        foreach ($matches as $match) {
            if (similar_text($club, $match[0]) > $highestFittingMatchScore) {
                $highestFittingMatchScore = similar_text($club, $match[0]);
                $highestFittingMatch = $match;
            }
            if (similar_text($club, $match[1]) > $highestFittingMatchScore) {
                $highestFittingMatchScore = similar_text($club, $match[1]);
                $highestFittingMatch = $match;
            }
        }
        dump($highestFittingMatch);
    }


}