<?php


namespace App\Service\Api;


use App\Entity\FixtureOdd;
use App\Service\Api\Response\ClubStanding;
use App\Service\Api\Response\FixtureOddResponse;
use App\Service\Api\Response\FixtureResponse;
use App\Service\Api\Response\RoundResponse;
use App\Service\Api\Response\StandingResponse;
use App\Service\Setting\FootballApiManagerService;
use DateTime;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class SportsmonkApiGateway
{
    private const BASE_URI = 'https://soccer.sportmonks.com/api/v2.0/';
    private const API_KEY = 'm2t50hFJHSBPoP5fn9JQFf0Xx7sPbv4MC0M3kScaZuhh2V2dJ1oPYEkZQ1FZ';

    /**
     * @var GuzzleClientFactory
     */
    private $clientFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param GuzzleClientFactory $clientFactory
     * @param LoggerInterface $logger
     */
    public function __construct(GuzzleClientFactory $clientFactory, LoggerInterface $autoUpdateLogger)
    {
        $this->clientFactory = $clientFactory;
        $this->logger = $autoUpdateLogger;
    }

    /**
     * @return array
     */
    public function getAvailableLeagues(): array
    {
        $client = $this->clientFactory->createClient([], self::BASE_URI);

        $options = [
            'query' => ['api_token' => self::API_KEY]
        ];

        try {
            $response = $client->request('GET', 'leagues', $options);
        } catch (GuzzleException $e) {
            dump($e);
            return [];
        }

        $response = json_decode($response->getBody(), true);

        return $response['data'];
    }

    /**
     * @return array
     */
    public function getAvailableSeasons(int $page): array
    {
        $client = $this->clientFactory->createClient([], self::BASE_URI);

        $options = [
            'query' => ['api_token' => self::API_KEY, 'page' => $page]
        ];

        try {
            $response = $client->request('GET', 'seasons', $options);
        } catch (GuzzleException $e) {
            return [];
        }

        $response = json_decode($response->getBody(), true);

        return $response['data'];
    }

    /**
     * @return array
     */
    public function getAvailableSeasonsPageCall(): array
    {
        $client = $this->clientFactory->createClient([], self::BASE_URI);

        $options = [
            'query' => ['api_token' => self::API_KEY]
        ];

        try {
            $response = $client->request('GET', 'seasons', $options);
        } catch (GuzzleException $e) {
            return [];
        }

        $response = json_decode($response->getBody(), true);

        return $response['meta'];
    }

    /**
     * @param int $seasonId
     * @return array
     */
    public function getClubsForSeason(int $seasonId): array
    {
        $client = $this->clientFactory->createClient([], self::BASE_URI);

        $options = [
            'query' => ['api_token' => self::API_KEY]
        ];

        try {
            $response = $client->request('GET', 'teams/season/'.$seasonId, $options);
        } catch (GuzzleException $e) {
            return [];
        }

        $response = json_decode($response->getBody(), true);

        return $response['data'];
    }

    /**
     * @param int $seasonId
     * @return array
     */
    public function getRoundForSeason(int $seasonId): array
    {
        $client = $this->clientFactory->createClient([], self::BASE_URI);

        $options = [
            'query' => ['api_token' => self::API_KEY, 'include' => 'fixtures']
        ];

        try {
            $response = $client->request('GET', 'rounds/season/'.$seasonId, $options);
        } catch (GuzzleException $e) {
            return [];
        }

        $response = json_decode($response->getBody(), true);

        return $response['data'];
    }

    /**
     * @param int $fixtureId
     * @return FixtureOddResponse[]
     */
    public function getOddsForFixture(int $fixtureId): array
    {
        $client = $this->clientFactory->createClient([], self::BASE_URI);

        $options = [
            'query' => ['api_token' => self::API_KEY]
        ];

        try {
            $response = $client->request('GET', 'odds/fixture/'.$fixtureId, $options);
        } catch (GuzzleException $e) {
            return [];
        }

        $response = json_decode($response->getBody(), true);
        if (array_key_exists('error', $response)){
            if ($response['error']['code'] == 429){
                $this->logger->info("Limit reached for odds route");
                dd("Limit reached");
            }
        }

        return $this->parseOddResponse($response, $fixtureId);
    }

    /**
     * @param array $response
     * @return FixtureOddResponse[]
     */
    private function parseOddResponse(array $response, int $fixtureId): array
    {
        $oddResponses = array();
        if (empty($response['data'])){
            return [];
        }
        foreach ($response['data'] as $betVariant){
            $bookmakers = $betVariant['bookmaker']['data'];

            foreach ($bookmakers as $bookmaker){
                $oddResponse = new FixtureOddResponse();
                $oddResponse->setProvider($betVariant['name']);
                $oddResponse->setFixtureApiId($fixtureId);
                if ($betVariant['name'] === '3Way Result') {
                    $odds = $bookmaker['odds']['data'];
                    foreach ($odds as $odd) {
                        if ($odd['label'] == '1') {
                            $oddResponse->setHomeOdd($odd['value']);
                        }
                        if ($odd['label'] == 'X') {
                            $oddResponse->setDrawOdd($odd['value']);
                        }
                        if ($odd['label'] == '2') {
                            $oddResponse->setAwayOdd($odd['value']);
                        }
                    }
                    $oddResponse->setType(FixtureOdd::TYPE_CLASSIC);
                    $oddResponses[] = $oddResponse;

                }
                if ($betVariant['name'] === 'Double Chance') {
                    $odds = $bookmaker['odds']['data'];
                    foreach ($odds as $odd) {
                        if ($odd['label'] == '1X') {
                            $oddResponse->setHomeOdd($odd['value']);
                        }
                        if ($odd['label'] == '12') {
                            $oddResponse->setDrawOdd($odd['value']);
                        }
                        if ($odd['label'] == 'X2') {
                            $oddResponse->setAwayOdd($odd['value']);
                        }
                    }
                    $oddResponse->setType(FixtureOdd::TYPE_DOUBLE_CHANCE);
                    $oddResponses[] = $oddResponse;
                }
            }
        }
        return $oddResponses;
    }

    public function getStandingsForSeasonRound(int $seasonId, int $roundId): array
    {
        $client = $this->clientFactory->createClient([], self::BASE_URI);

        $options = [
            'query' => ['api_token' => self::API_KEY]
        ];

        try {
            $response = $client->request('GET', 'standings/season/'.$seasonId.'/round/'.$roundId, $options);
        } catch (GuzzleException $e) {
            return [];
        }

        $response = json_decode($response->getBody(), true);

        return $response['data'];
    }

    /**
     * @param string $startDate
     * @param string|null $endDate
     * @return array
     */
    public function getFixturesInDateRangeForLeague(string $startDate, string $endDate, int $leagueId): array
    {
        $client = $this->clientFactory->createClient([], self::BASE_URI);

        $options = [
            'query' => ['api_token' => self::API_KEY, 'leagues' => $leagueId]
        ];

        try {
            $response = $client->request('GET', 'fixtures/between/'.$startDate.'/'.$endDate, $options);
        } catch (GuzzleException $e) {
            return [];
        }

        $response = json_decode($response->getBody(), true);

        return $response['data'];
    }



    // TODO
    // 1.get rounds ?
    // 2.get fixtures for rounds </
    // 3.get odds for fixtures </
    // 4.get standings
    // 5.calculate seedings
    // search league by name
}
