<?php
namespace Werkint\Bundle\StatsBundle\Command;

use Doctrine\ORM\Query;
use Emisser\Bundle\ProcessingBundle\Service\Util\BalancesStatsProvider;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * StatsCommand.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class StatsUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('werkint:stats:update')
            ->setDescription('Updates stats');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return null
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $output->write('Updating stats: ');
        $output->writeln($this->serviceStatsDirector()->updateCache() . ' updated');
    }

    // -- Services ---------------------------------------

    protected function serviceStatsDirector()
    {
        return $this->getContainer()->get('werkint_stats.statsdirector');
    }
}
