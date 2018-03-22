<?php
// src/Kiabi/Command/FacebookParseCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;

class FacebookParseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('facebook:parse')
            ->setDescription('Build facebook (google style) feed.')
            ->setHelp('This command allows you to build facebook (google style) feed from original feed')
            ->addOption('utm', 'u', InputOption::VALUE_NONE, 'Add UTM counter to links')
            ->addOption('quiet', 'q', InputOption::VALUE_NONE, 'Disable all output of the program.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);

        if (true === $input->getOption('quiet')) {
            $output = new NullOutput();
        }

        $output->writeln([
            'Start build "Facebook" feed '.($input->getOption('utm') ? 'with UTM' : 'w/o UTM') ,
            '============',
            '',
        ]);

        $utm = LINK_COUNTER_APPENDIX_FACEBOOK;
        $utmMobile = LINK_COUNTER_APPENDIX_FACEBOOK;
        $feedPath = FEED_CONVERTED_FACEBOOK_PATH;

        $parser = new \Kiabi\Parser\FacebookParser(
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