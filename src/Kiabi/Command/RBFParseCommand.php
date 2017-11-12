<?php
// src/Kiabi/Command/RBFParseCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class RBFParseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('rbf:parse')
            ->setDescription('Build yandex feed for blackfriday.')
            ->setHelp('This command allows you to build correct feed from original feed')
            ->addOption('utm', 'u', InputOption::VALUE_NONE, 'Add UTM counter to links')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);

        $output->writeln([
            'Start build "Real BlackFriday" feed '.($input->getOption('utm') ? 'with UTM' : 'w/o UTM') ,
            '============',
            '',
        ]);

        $utm = LINK_COUNTER_APPENDIX_RBF;
        $feedPath = FEED_CONVERTED_RBF_PATH;

        $parser = new \Kiabi\Parser\RBFParser(
            FEED_YANDEX_PATH,
            YANDEX_CATEGORIES_PATH,
            $utm,
            new \Kiabi\Cutter([]),
            new \Kiabi\Replacer(json_decode(file_get_contents(YANDEX_COLORS_PATH), true)),
            RBF_GOODS_PATH,
            $input->getOption('utm')

        );

        $parser->parse();

        @unlink($feedPath);
        @file_put_contents($feedPath, $parser->getXML());
    }
}