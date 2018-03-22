<?php
// src/Kiabi/Command/MailParseCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;

class MailParseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('mail:parse')
            ->setDescription('Build mail remarketing feed.')
            ->setHelp('This command allows you to build correct feeds from original feed')
            ->addOption('utm', 'u', InputOption::VALUE_NONE, 'Add UTM counter to links')
            ->addOption('target', 't', InputOption::VALUE_REQUIRED, 'Which feed to build', 'yandex0_mail')
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
            'Start build "'.$input->getOption('target').'" feed '.($input->getOption('utm') ? 'with UTM' : 'w/o UTM') ,
            '============',
            '',
        ]);

        switch ($input->getOption('target')) {
            default:
                $utm = LINK_COUNTER_APPENDIX_YANDEX0_MAIL;
                $feedPath = FEED_CONVERTED_YANDEX0_MAIL_PATH;
        }


        $parser = new \Kiabi\Parser\MailParser(
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