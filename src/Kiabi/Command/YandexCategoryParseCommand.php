<?php
// src/Kiabi/Command/YandexCategoryParseCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;

class YandexCategoryParseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('yandex:category:parse')
            ->setDescription('Build yandex categories json.')
            ->setHelp('This command allows you to build yandex categories json')
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
            'Start parse Yandex categories',
            '============',
            '',
        ]);

        $categories = [];
        if (file_exists(YANDEX_CATEGORIES_PATH)) {
            $categories = json_decode(file_get_contents(YANDEX_CATEGORIES_PATH), true);
        }

        $parser = new \Kiabi\Parser\YandexCategoryParser(FEED_ORIGINAL_PATH, $categories);

        $parser->parse();

        @unlink(YANDEX_CATEGORIES_PATH);
        @file_put_contents(YANDEX_CATEGORIES_PATH, $parser->getJson());

        $output->writeln('Categories parsed');
    }
}