<?php
// src/Kiabi/Command/YandexColorCleanCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;

class YandexColorCleanCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('yandex:color:clean')
            ->setDescription('Clean yandex colors json from links.')
            ->setHelp('This command allows you to build yandex colors json')
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
            'Start clean Yandex colors',
            '============',
            '',
        ]);

        $colors = [];
        if (file_exists(YANDEX_COLORS_PATH)) {
            $colors = json_decode(file_get_contents(YANDEX_COLORS_PATH), true);
        }

        $parser = new \Kiabi\Parser\ColorCleaner(FEED_ORIGINAL_PATH, new \Kiabi\Replacer());

        $parser->parse();

        $colors = array_merge($parser->getColors(), $colors);

        @unlink(YANDEX_COLORS_PATH);
        @file_put_contents(YANDEX_COLORS_PATH, json_encode($colors));

        $output->writeln('Colors stuff links have cleaned');
    }
}