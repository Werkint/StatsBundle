<?php
namespace Werkint\Bundle\StatsBundle\Service;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Werkint\Bundle\FrameworkExtraBundle\Service\Logger\IndentedLoggerInterface;
use Werkint\Bundle\FrameworkExtraBundle\Service\Processor\Stuff\StuffProviderInterface;

/**
 * StatsStuffProvider.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class StatsStuffProvider implements
    StuffProviderInterface
{
    protected $director;

    /**
     * @param StatsDirector $director
     */
    public function __construct(
        StatsDirector $director
    ) {
        $this->director = $director;
    }

    // -- Stuff ---------------------------------------

    /**
     * {@inheritdoc}
     */
    public function process(
        IndentedLoggerInterface $logger,
        ContainerAwareCommand $command = null,
        $forced = false
    ) {
        $logger->write('Updating stats... ');
        $amount = $this->director->updateCache();
        $logger->writeln($amount . ' updated');
    }
} 