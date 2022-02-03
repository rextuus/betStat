<?php


namespace App\Command;


use App\Service\Api\OddApiGateway;
use App\Service\Api\OddSet\OddData;
use App\Service\Api\OddSet\OddService;
use App\Service\Api\PinnacleGateway;
use App\Service\LiveFormTable\LiveFormTableProvider;
use Doctrine\ORM\ORMException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GetNewestOddsCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'bet:store:odds';
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
     * @throws ClientExceptionInterface
     * @throws ORMException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws DecodingExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $leagueIdents = ['de1', 'de2', 'en1', 'en2', 'it1', 'it2', 'es1', 'es2', 'fr1', 'fr2'];

        foreach ($leagueIdents as $ident){
            $this->oddApiGateway->getOddsForLeague($ident);
            $output->writeln("New Matches for current ".$ident." matchday successfull stored");
        }
        return Command::SUCCESS;
    }
}