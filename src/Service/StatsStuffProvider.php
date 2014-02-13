<?php
namespace Werkint\Bundle\StatsBundle\Service;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Werkint\Bundle\CommandBundle\Service\Contract\StuffProviderInterface;

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
     * @param OutputInterface       $out
     * @param ContainerAwareCommand $command
     * @param bool                  $forced
     */
    public function process(
        OutputInterface $out,
        ContainerAwareCommand $command = null,
        $forced = false
    ) {
        $out->write('Updating stats... ');
        $amount = $this->director->updateCache();
        $out->writeln($amount . ' updated');
    }
} 