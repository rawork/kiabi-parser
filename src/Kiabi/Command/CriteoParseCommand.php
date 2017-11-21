<?php
// src/Kiabi/Command/CriteoParseCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class CriteoParseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('criteo:parse')
            ->setDescription('Build google feed for Criteo.')
            ->setHelp('This command allows you to build google feed for Criteo from original feed')
            ->addOption('utm', 'u', InputOption::VALUE_NONE, 'Add UTM counter to links')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);

        $output->writeln([
            'Start build "criteo" feed '.($input->getOption('utm') ? 'with UTM' : 'w/o UTM') ,
            '============',
            '',
        ]);

        $feedPath = FEED_CONVERTED_CRITEO_PATH;

        $parser = new \Kiabi\Parser\CriteoParser(
            FEED_ORIGINAL_PATH,
            '',
            '',
            $input->getOption('utm')
        );

        $parser->parse();

        @unlink($feedPath);
        @file_put_contents($feedPath, $parser->getXML());
    }
}