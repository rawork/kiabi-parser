<?php
// src/Kiabi/Command/Google2ParseCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class Google2ParseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('google2:parse')
            ->setDescription('Build short Google feed, w/o sku information in offers.')
            ->setHelp('This command allows you to build short Google feed from original feed')
            ->addOption('utm', 'u', InputOption::VALUE_NONE, 'Add UTM counter to links')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);

        $output->writeln([
            'Start build "Short Google" feed '.($input->getOption('utm') ? 'with UTM' : 'w/o UTM') ,
            '============',
            '',
        ]);

        $utm = LINK_COUNTER_APPENDIX_GOOGLE2;
        $utmMobile = LINK_COUNTER_APPENDIX_GOOGLE2;
        $feedPath = FEED_CONVERTED_GOOGLE2_PATH;

        $parser = new \Kiabi\Parser\Google2Parser(
            FEED_ORIGINAL_PATH,
            $utm,
            $utmMobile,
            $input->getOption('utm')
        );

        $parser->parse();

        @unlink($feedPath);
        @file_put_contents($feedPath, $parser->getXML());
    }
}