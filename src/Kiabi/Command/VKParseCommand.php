<?php
// src/Kiabi/Command/YandexParseCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class VKParseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('vk:parse')
            ->setDescription('Build vk feeds.')
            ->setHelp('This command allows you to build correct feeds from original feed')
            ->addOption('utm', 'u', InputOption::VALUE_NONE, 'Add UTM counter to links')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);

        $output->writeln([
            'Start build "VK" feed '.($input->getOption('utm') ? 'with UTM' : 'w/o UTM') ,
            '============',
            '',
        ]);


        $utm = LINK_COUNTER_APPENDIX_YANDEX_VK;
        $feedPath = FEED_CONVERTED_YANDEX_VK_PATH;

        $parser = new \Kiabi\Parser\VKParser(
            FEED_ORIGINAL_PATH,
            YANDEX_CATEGORIES_PATH,
            $utm,
            new \Kiabi\Cutter([]),
            new \Kiabi\Replacer(json_decode(file_get_contents(YANDEX_COLORS_PATH), true)),
            $input->getOption('utm')
        );

        $parser->parse();

        @unlink($feedPath);
        @file_put_contents($feedPath, $parser->getXML());
    }
}