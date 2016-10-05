<?php

namespace WarbleMedia\PhoenixBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WriteMetricsCommand extends ContainerAwareCommand
{
    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this
            ->setName('phoenix:write-metrics')
            ->setDescription('Store the performance indicators for the application');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $metricsManager = $this->getContainer()->get('warble_media_phoenix.model.metrics_manager');

        $metrics = $metricsManager->captureTodaysMetrics();
        $metricsManager->updateMetrics($metrics);

        $output->writeln('Wrote today\'s metrics to the database.');
    }
}
