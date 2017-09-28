<?php
// src/Kiabi/Command/AvitoParseCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class AvitoParseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('parse:avito')
            ->setDescription('Build avito feed.')
            ->setHelp('This command allows you to build avito feed (divided on categories) from original feed')
            ->addOption('utm', 'u', InputOption::VALUE_NONE, 'Add UTM counter to links')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);

        $output->writeln([
            'Start build "Avito" divided feed '.($input->getOption('utm') ? 'with UTM' : 'w/o UTM') ,
            '============',
            '',
        ]);

        $parser = new \Kiabi\Parser\AvitoDividedParser(
            FEED_YANDEX_PATH,
            YANDEX_CATEGORIES_PATH,
            LINK_COUNTER_APPENDIX_AVITO,
            new \Kiabi\Cutter([]),
            new \Kiabi\Replacer(json_decode(file_get_contents(YANDEX_COLORS_PATH), true)),
            $input->getOption('utm')
        );

        $parser->parse();

        $feeds = $parser->getXML();

        $pathParts = pathinfo(FEED_CONVERTED_AVITO_PATH);

        foreach ($feeds as $feed) {
            @unlink($pathParts['dirname']  .'/'. $pathParts['filename'] . '_' . $feed['id']. '.' . $pathParts['extension']);
            @file_put_contents($pathParts['dirname']  .'/'. $pathParts['filename'] . '_' . $feed['id']. '.' . $pathParts['extension'], $feed['content']);
        }
    }
}