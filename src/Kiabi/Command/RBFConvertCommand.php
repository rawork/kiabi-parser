<?php
// src/Kiabi/Command/RBFConvertCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;

class RBFConvertCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('rbf:convert')
            ->setDescription('Convert raw RBF goods json to indexed.')
            ->setHelp('This command allows you to convert raw RBF goods json to indexed by url')
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
            'Start convert "Real BlackFriday" selected goods json to indexed json',
            '============',
            '',
        ]);

        $raw = json_decode(file_get_contents(RBF_GOODS_RAW_PATH), true);
        $indexed = [];

        foreach ($raw as $item) {
            $item['Link'] = trim($item['Link']);
            $indexed[md5($item['Link'])] = $item;
        }

        file_put_contents(RBF_GOODS_PATH, json_encode($indexed));
    }
}