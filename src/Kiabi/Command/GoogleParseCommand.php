<?php
// src/Kiabi/Command/YandexParseCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class GoogleParseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('parse:google')
            ->setDescription('Build google feed.')
            ->setHelp('This command allows you to build google feed from original feed')
            ->addOption('utm', 'u', InputOption::VALUE_NONE, 'Add UTM counter to links')
            ->addOption('target', 't', InputOption::VALUE_REQUIRED, 'Add UTM counter to links', 'google');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);

        $output->writeln([
            'Start build "'.$input->getOption('target').'" feed '.($input->getOption('utm') ? 'with UTM' : 'w/o UTM') ,
            '============',
            '',
        ]);

        switch ($input->getOption('target')) {
            case 'criteo':
                $utm = '';
                $feedPath = FEED_CONVERTED_CRITEO_PATH;
                break;
            default:
                $utm = LINK_COUNTER_APPENDIX_GOOGLE;
                $feedPath = FEED_CONVERTED_GOOGLE_PATH;
        }

        $parser = new \Kiabi\GoogleParser(
            FEED_YANDEX_PATH,
            $utm,
            $input->getOption('utm')
        );

        $parser->parse();

        @unlink($feedPath);
        @file_put_contents($feedPath, $parser->getXML());
    }
}