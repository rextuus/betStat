<?php


namespace App\Tests;


use App\Entity\Club;
use App\Entity\Fixture;
use App\Entity\Season;
use App\Entity\Seeding;
use App\Service\Api\FootballApiGateway;
use App\Service\Api\FootballApiManagerService;
use App\Service\Club\ClubService;
use App\Service\Evaluation\EvaluationService;
use App\Service\Fixture\FixtureService;
use App\Service\FixtureOdd\FixtureOddService;
use App\Service\Import\UpdateService;
use App\Service\League\LeagueService;
use App\Service\Season\SeasonService;
use App\Service\Seeding\SeedingService;
use Monolog\Logger;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateServiceTest extends TestCase
{
    /**
     * @var UpdateService
     */
    private $updateService;
    /**
     * @var MockObject|FixtureService
     */
    private $fixtureService;
    /**
     * @var MockObject|FixtureOddService
     */
    private $fixtureOddService;
    /**
     * @var MockObject|LeagueService
     */
    private $leagueService;
    /**
     * @var MockObject|SeasonService
     */
    private $seasonService;
    /**
     * @var MockObject|ClubService
     */
    private $clubService;
    /**
     * @var MockObject|SeedingService
     */
    private $seedingService;
    /**
     * @var MockObject|FootballApiGateway
     */
    private $footballApiGateway;
    /**
     * @var MockObject|FootballApiManagerService
     */
    private $footballApiManagerService;
    /**
     * @var MockObject|Logger
     */
    private $logger;

    public function setUp(): void
    {
        parent::setUp();
        $this->fixtureService = $this->getMockBuilder(FixtureService::class)->disableOriginalConstructor()->getMock();
        $this->fixtureOddService = $this->getMockBuilder(FixtureOddService::class)->disableOriginalConstructor()->getMock();
        $this->leagueService = $this->getMockBuilder(LeagueService::class)->disableOriginalConstructor()->getMock();
        $this->seasonService = $this->getMockBuilder(SeasonService::class)->disableOriginalConstructor()->getMock();
        $this->clubService = $this->getMockBuilder(ClubService::class)->disableOriginalConstructor()->getMock();
        $this->seedingService = $this->getMockBuilder(SeedingService::class)->disableOriginalConstructor()->getMock();
        $this->footballApiManagerService = $this->getMockBuilder(FootballApiManagerService::class)->disableOriginalConstructor()->getMock();
        $this->logger = $this->getMockBuilder(Logger::class)->disableOriginalConstructor()->getMock();
        $this->footballApiGateway = $this->getMockBuilder(FootballApiGateway::class)->disableOriginalConstructor()->getMock();
        $this->updateService = new UpdateService(
            $this->footballApiGateway,
            $this->clubService,
            $this->leagueService,
            $this->seasonService,
            $this->seedingService,
            $this->fixtureService,
            $this->fixtureOddService,
            $this->footballApiManagerService,
            $this->logger
        );

    }


    public function testStoreFormsTillCurrentRound(): void
    {
        $fixture = new Fixture();
        $club = new Club();
        $season = new Season();
        $fixture->setHomeTeam($club);
        $fixture->setAwayTeam($club);
        $fixture->setSeason($season);
        $fixture->setMatchDay(1);
        $homeSeeding = new Seeding();
        $homeSeeding->setForm('WWW');
        $awaySeeding = new Seeding();
        $awaySeeding->setForm('WWW');
        $this->seedingService->method('findByClubAndSeasonAndLRound')->willReturnOnConsecutiveCalls($homeSeeding, $awaySeeding);

        $form = 'WDLDLDLD';
        $this->updateService->storeFormsTillCurrentRound($form, $club, $season);

        dump(strlen('LLDLDDLLDLDDLDWLLLDDWWW'));
    }
}
