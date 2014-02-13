<?php
namespace Werkint\Bundle\StatsBundle\Command;

use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * StatsCommand.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class StatsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('werkint:stats:get')
            ->setDescription('Returs stats value')
            ->addArgument('name')
            ->addArgument('options', InputArgument::OPTIONAL);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @throws \InvalidArgumentException
     * @return null
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $name = $input->getArgument('name');
        $options = $input->getArgument('options');
        if ($options) {
            $options = json_decode($options);
            if ($options === null) {
                throw new \InvalidArgumentException('Wrong options');
            }
        }

        $ret = $this->serviceStatsDirector()->getStat(
            $name,
            $options ? $options : [],
            false
        );
        $output->write('Stat "' . $name . '": ');
        $output->writeln(is_scalar($ret) ? '[SCALAR] ' . $ret : '[JSON] ' . json_encode($ret));
    }

    // -- Services ---------------------------------------

    protected function serviceStatsDirector()
    {
        return $this->getContainer()->get('werkint.stats');
    }
}
