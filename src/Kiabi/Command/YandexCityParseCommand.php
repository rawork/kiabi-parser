<?php
// src/Kiabi/Command/YandexParseCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;

class YandexCityParseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('yandex:city:parse')
            ->setDescription('Build yandex')
            ->setHelp('This command allows you to build correct feeds from original feed')
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
            'Start build "yandex " feed '.($input->getOption('utm') ? 'with UTM' : 'w/o UTM') ,
            '============',
            '',
        ]);

        $cities = [
            ['num' => 'num', 'name' => 'moscow'],
            ['num' => 'num', 'name' => 'peterburg'],
            ['num' => 'num', 'name' => 'voronej'],
            ['num' => 'num', 'name' => 'ekaterinburg'],
            ['num' => 'num', 'name' => 'kazan'],
            ['num' => 'num', 'name' => 'krasnodar'],
            ['num' => 'num', 'name' => 'samara'],
            ['num' => 'num', 'name' => 'toliatti'],
            ['num' => 'num', 'name' => 'ufa'],
            ['num' => 'num', 'name' => 'russia'],
        ];

        $utmTemplate = LINK_COUNTER_APPENDIX_YANDEX_CITY;
        $feedPathTemplate = FEED_CONVERTED_YANDEX_CITY_PATH;

        foreach ($cities as $city) {
            $output->writeln([
                'City ' . $city['name']
            ]);
            $utm = str_replace('{city_num}', $city['num'], $utmTemplate);
            $utm = str_replace('{city_name}', $city['name'], $utm);

            $feedPath = str_replace('{city_id}', $city['name'], $feedPathTemplate);

            $output->writeln([
                $feedPath
            ]);

            $parser = new \Kiabi\Parser\YandexParser(
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
}