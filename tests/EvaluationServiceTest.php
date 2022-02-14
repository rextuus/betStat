<?php


namespace App\Tests;


use App\Entity\Club;
use App\Entity\Fixture;
use App\Entity\Season;
use App\Entity\Seeding;
use App\Service\Club\ClubService;
use App\Service\Evaluation\EvaluationService;
use App\Service\Fixture\FixtureService;
use App\Service\FixtureOdd\FixtureOddService;
use App\Service\League\LeagueService;
use App\Service\Season\SeasonService;
use App\Service\Seeding\SeedingService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EvaluationServiceTest extends TestCase
{
    /**
     * @var EvaluationService
     */
    private $evaluationService;
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

    public function setUp(): void
    {
        parent::setUp();
        $this->fixtureService = $this->getMockBuilder(FixtureService::class)->disableOriginalConstructor()->getMock();
        $this->fixtureOddService = $this->getMockBuilder(FixtureOddService::class)->disableOriginalConstructor()->getMock();
        $this->leagueService = $this->getMockBuilder(LeagueService::class)->disableOriginalConstructor()->getMock();
        $this->seasonService = $this->getMockBuilder(SeasonService::class)->disableOriginalConstructor()->getMock();
        $this->clubService = $this->getMockBuilder(ClubService::class)->disableOriginalConstructor()->getMock();
        $this->seedingService = $this->getMockBuilder(SeedingService::class)->disableOriginalConstructor()->getMock();
        $this->evaluationService = new EvaluationService(
            $this->fixtureService,
            $this->fixtureOddService,
            $this->leagueService,
            $this->seasonService,
            $this->clubService,
            $this->seedingService
        );

    }


    public function testGetCandidateForFixtureNoOneIsCandidate(): void
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

        $candidate = $this->evaluationService->getCandidateForFixture($fixture);
        $this->assertEquals(-1, $candidate);
    }

    public function testGetCandidateForFixtureMissingForms(): void
    {
        $fixture = new Fixture();
        $club = new Club();
        $season = new Season();
        $fixture->setHomeTeam($club);
        $fixture->setAwayTeam($club);
        $fixture->setSeason($season);
        $fixture->setMatchDay(1);
        $homeSeeding = new Seeding();
        $awaySeeding = new Seeding();
        $this->seedingService->method('findByClubAndSeasonAndLRound')->willReturnOnConsecutiveCalls($homeSeeding, $awaySeeding);

        $candidate = $this->evaluationService->getCandidateForFixture($fixture);
        $this->assertEquals(-1, $candidate);
    }

    public function testGetCandidateForFixtureHomeIsCandidate(): void
    {
        $fixture = new Fixture();
        $club = new Club();
        $season = new Season();
        $fixture->setHomeTeam($club);
        $fixture->setAwayTeam($club);
        $fixture->setSeason($season);
        $fixture->setMatchDay(1);
        $homeSeeding = new Seeding();
        $homeSeeding->setForm('DDWLW');
        $awaySeeding = new Seeding();
        $awaySeeding->setForm('DDWWW');
        $this->seedingService->method('findByClubAndSeasonAndLRound')->willReturnOnConsecutiveCalls($homeSeeding, $awaySeeding, $homeSeeding, $awaySeeding);

        $candidate = $this->evaluationService->getCandidateForFixture($fixture);
        $this->assertEquals(1   , $candidate);
    }

    public function testGetCandidateForFixtureAwayIsCandidate(): void
    {
        $fixture = new Fixture();
        $club = new Club();
        $season = new Season();
        $fixture->setHomeTeam($club);
        $fixture->setAwayTeam($club);
        $fixture->setSeason($season);
        $fixture->setMatchDay(1);
        $homeSeeding = new Seeding();
        $homeSeeding->setForm('DDWLL');
        $awaySeeding = new Seeding();
        $awaySeeding->setForm('DDWLW');
        $this->seedingService->method('findByClubAndSeasonAndLRound')->willReturnOnConsecutiveCalls($homeSeeding, $awaySeeding, $homeSeeding, $awaySeeding);

        $candidate = $this->evaluationService->getCandidateForFixture($fixture);
        $this->assertEquals(2   , $candidate);
    }

    public function testGetCandidateForFixtureBothAreCandidate(): void
    {
        $fixture = new Fixture();
        $club = new Club();
        $season = new Season();
        $fixture->setHomeTeam($club);
        $fixture->setAwayTeam($club);
        $fixture->setSeason($season);
        $fixture->setMatchDay(1);
        $homeSeeding = new Seeding();
        $homeSeeding->setForm('DDDDDWLW');
        $awaySeeding = new Seeding();
        $awaySeeding->setForm('DDDDDWLW');
        $this->seedingService->method('findByClubAndSeasonAndLRound')->willReturnOnConsecutiveCalls($homeSeeding, $awaySeeding, $homeSeeding, $awaySeeding);

        $candidate = $this->evaluationService->getCandidateForFixture($fixture);
        $this->assertEquals(0   , $candidate);
    }
}
