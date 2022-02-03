<?php


namespace App\Service\Api;


use App\Entity\FixtureOdd;
use App\Service\Api\Response\ClubStanding;
use App\Service\Api\Response\FixtureOddResponse;
use App\Service\Api\Response\FixtureResponse;
use App\Service\Api\Response\StandingResponse;
use DateTime;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class FootballApiGateway
{
    private const BASE_URI = 'https://api-football-v1.p.rapidapi.com/v3/';
    private const API_KEY = '58630f3a2bmsh3711e91741fe2d0p1b7618jsn21436b4a6368';

    /**
     * @var GuzzleClientFactory
     */
    private $clientFactory;

    /**
     * @var FootballApiManagerService
     */
    private $footballApiManagerService;

    /**
     * FootballApiGateway constructor.
     * @param GuzzleClientFactory $clientFactory
     * @param FootballApiManagerService $footballApiManagerService
     */
    public function __construct(GuzzleClientFactory $clientFactory, FootballApiManagerService $footballApiManagerService)
    {
        $this->clientFactory = $clientFactory;
        $this->footballApiManagerService = $footballApiManagerService;
    }

    /**
     * @param int $leagueId
     * @param int $seasonYear
     * @return StandingResponse
     */
    public function getStandingForLeagues(int $leagueId, int $seasonYear): StandingResponse
    {
        $headers = ['x-rapidapi-host' => 'api-football-v1.p.rapidapi.com', 'x-rapidapi-key' => self::API_KEY];

        $client = $this->clientFactory->createClient($headers, self::BASE_URI);

        $options = [
            'query' => ['league' => $leagueId, 'season' => $seasonYear]
        ];

        try {
            $response = $client->request('GET', 'standings', $options);
        } catch (GuzzleException $e) {
            return new StandingResponse();
        }
        $this->footballApiManagerService->increaseCallCounter();

        $response = json_decode($response->getBody(), true);
        return $this->parseStandingResponse($response['response']);
    }

    /**
     * @param int $leagueId
     * @param int $seasonYear
     * @return FixtureResponse[]
     */
    public function getNextFixturesForLeagueAndCurrentRound(int $leagueId, int $seasonYear): array
    {
        $response = $this->getCurrentRoundForLeague($leagueId, $seasonYear);
        if (is_null($response)){
            return [];
        }

        $response = json_decode($response->getBody(), true);
        $currentRound = $response['response'][0]; // Regular Season - 23

        return $this->getFixturesForLeagueMatchDay($leagueId, $seasonYear, $currentRound);
    }

    public function getCurrentRoundForLeague(int $leagueId, int $seasonYear): ?ResponseInterface
    {
        $headers = ['x-rapidapi-host' => 'api-football-v1.p.rapidapi.com', 'x-rapidapi-key' => self::API_KEY];

        $client = $this->clientFactory->createClient($headers, self::BASE_URI);

        $options = [
            'query' => ['league' => $leagueId, 'season' => $seasonYear, 'current' => 'true']
        ];

        try {
            $response = $client->request('GET', 'fixtures/rounds', $options);
        } catch (GuzzleException $e) {
            return null;
        }
        $this->footballApiManagerService->increaseCallCounter();
        return $response;
    }

    /**
     * @param int $leagueId
     * @param int $seasonYear
     * @param int $round
     * @return FixtureResponse[]
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getNextFixturesForLeagueAndRound(int $leagueId, int $seasonYear, int $round): array
    {
        $headers = ['x-rapidapi-host' => 'api-football-v1.p.rapidapi.com', 'x-rapidapi-key' => self::API_KEY];

        $client = $this->clientFactory->createClient($headers, self::BASE_URI);

        $roundString = 'Regular Season - '.$round;
        $options = [
            'query' => ['league' => $leagueId, 'season' => $seasonYear, 'round' => $roundString]
        ];

        try {
            $response = $client->request('GET', 'fixtures', $options);
        } catch (GuzzleException $e) {
            return [];
        }
        $this->footballApiManagerService->increaseCallCounter();

        $response = json_decode($response->getBody(), true);

        return $this->parseFixtureResponse($response['response']);
    }

    /**
     * @param string $leagueId
     * @param int $seasonYear
     * @param string $round
     * @return FixtureResponse[]
     */
    public function getFixturesForLeagueMatchDay(string $leagueId, int $seasonYear, string $round): array
    {
        $headers = ['x-rapidapi-host' => 'api-football-v1.p.rapidapi.com', 'x-rapidapi-key' => self::API_KEY];

        $client = $this->clientFactory->createClient($headers, self::BASE_URI);

        $options = [
            'query' => ['league' => $leagueId, 'season' => $seasonYear, 'round' => $round]
        ];

        try {
            $response = $client->request('GET', 'fixtures', $options);
        } catch (GuzzleException $e) {
            return [];
        }
        $this->footballApiManagerService->increaseCallCounter();

        $response = json_decode($response->getBody(), true);

        return $this->parseFixtureResponse($response['response']);
    }

    /**
     * @param int $fixtureId
     * @return FixtureOddResponse[]
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getOddsForFixture(int $fixtureId): array
    {
        $headers = ['x-rapidapi-host' => 'api-football-v1.p.rapidapi.com', 'x-rapidapi-key' => self::API_KEY];

        $client = $this->clientFactory->createClient($headers, self::BASE_URI);

        $options = [
            'query' => ['fixture' => $fixtureId]
        ];

        try {
            $response = $client->request('GET', 'odds', $options);
        } catch (GuzzleException $e) {
            return [];
        }
        $this->footballApiManagerService->increaseCallCounter();

        $response = json_decode($response->getBody(), true);

        if (count($response['response']) == 0){
            return [];
        }
        return $this->parseOddResponse($response['response'][0]);
    }

    /**
     * @param array $response
     * @return StandingResponse
     */
    private function parseStandingResponse(array $response){
        $standingResponse = new StandingResponse();

        $league = $response[0]['league'];
        $standingResponse->setLeagueId($league['id']);
        $standingResponse->setLeagueName($league['name']);
        $standingResponse->setSeasonStartYear($league['season']);

        $standings = array();
        foreach ($league['standings'][0] as $club){
            $clubsStanding = new ClubStanding();
            $clubsStanding->setClubId($club['team']['id']);
            $clubsStanding->setClubName($club['team']['name']);
            $clubsStanding->setForm($club['form']);
            $clubsStanding->setRank($club['rank']);
            $clubsStanding->setPoints($club['points']);
            $clubsStanding->setRound($club['all']['played']);
            $clubsStanding->setWins($club['all']['win']);
            $clubsStanding->setDraws($club['all']['draw']);
            $clubsStanding->setLooses($club['all']['lose']);
            $clubsStanding->setGoals($club['all']['goals']['for']);
            $clubsStanding->setAgainstGoals($club['all']['goals']['against']);
            $standings[] = $clubsStanding;
        }
        $standingResponse->setClubStandings($standings);
        return $standingResponse;
    }

    /**
     * @param array $response
     * @return FixtureResponse[]
     * @throws \Exception
     */
    private function parseFixtureResponse(array $response): array
    {
        $fixtureResponses = array();
        foreach ($response as $fixture){
            $fixtureResponse = new FixtureResponse();
            $fixtureResponse->setApiId($fixture['fixture']['id']);
            $fixtureResponse->setDate(new DateTime($fixture['fixture']['date']));
            $fixtureResponse->setTimeStamp($fixture['fixture']['timestamp']);
            $fixtureResponse->setHomeTeamApiId($fixture['teams']['home']['id']);
            $fixtureResponse->setAwayTeamApiId($fixture['teams']['away']['id']);
            $fixtureResponse->setLeagueApiId($fixture['league']['id']);
            $fixtureResponse->setSeasonStartYear($fixture['league']['season']);
            $fixtureResponse->setRound($fixture['league']['round']);
            $fixtureResponse->setScoreHomeHalfTime($fixture['score']['halftime']['home']);
            $fixtureResponse->setScoreHomeFullTime($fixture['score']['fulltime']['home']);
            $fixtureResponse->setScoreAwayHalfTime($fixture['score']['halftime']['away']);
            $fixtureResponse->setScoreAwayFullTime($fixture['score']['fulltime']['away']);
            $fixtureResponses[] = $fixtureResponse;
        }

        return $fixtureResponses;
    }

    /**
     * @param array $response
     * @return FixtureOddResponse[]
     */
    private function parseOddResponse(array $response): array
    {
        $oddResponses = array();
        foreach ($response['bookmakers'] as $bookmaker){
            $bets = $bookmaker['bets'];
            // search for classic bet and double chance
            foreach($bets as $bet){
                if ($bet['name'] === 'Match Winner'){
                    $oddResponse = new FixtureOddResponse();
                    $oddResponse->setFixtureApiId($response['fixture']['id']);
                    foreach ($bet['values'] as $oddValue){
                        if ($oddValue['value'] === 'Home'){
                            $oddResponse->setHomeOdd($oddValue['odd']);
                        }
                        if ($oddValue['value'] === 'Draw'){
                            $oddResponse->setDrawOdd($oddValue['odd']);
                        }
                        if ($oddValue['value'] === 'Away'){
                            $oddResponse->setAwayOdd($oddValue['odd']);
                        }
                    }
                    $oddResponse->setProvider($bookmaker['name']);
                    $oddResponse->setType(FixtureOdd::TYPE_CLASSIC);
                    $oddResponses[] = $oddResponse;
                }
                if ($bet['name'] === 'Double Chance'){
                    $oddResponse = new FixtureOddResponse();
                    $oddResponse->setFixtureApiId($response['fixture']['id']);
                    foreach ($bet['values'] as $oddValue){
                        if ($oddValue['value'] === 'Home/Draw'){
                            $oddResponse->setHomeOdd($oddValue['odd']);
                        }
                        if ($oddValue['value'] === 'Home/Away'){
                            $oddResponse->setDrawOdd($oddValue['odd']);
                        }
                        if ($oddValue['value'] === 'Draw/Away'){
                            $oddResponse->setAwayOdd($oddValue['odd']);
                        }
                    }
                    $oddResponse->setProvider($bookmaker['name']);
                    $oddResponse->setType(FixtureOdd::TYPE_DOUBLE_CHANCE);
                    $oddResponses[] = $oddResponse;
                }
            }
        }
        return $oddResponses;
    }
}
