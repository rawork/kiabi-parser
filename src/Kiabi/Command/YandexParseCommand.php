<?php
// src/Kiabi/Command/YandexParseCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class YandexParseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('parse:yandex')
            ->setDescription('Build yandex, mail, price, push4site, avito feeds.')
            ->setHelp('This command allows you to build correct feeds from original feed')
            ->addOption('utm', 'u', InputOption::VALUE_NONE, 'Add UTM counter to links')
            ->addOption('target', 't', InputOption::VALUE_REQUIRED, 'Which feed to build', 'yandex');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);

        $output->writeln([
            'Start build '.$input->getOption('target').' feed '.($input->getOption('utm') ? 'with UTM' : 'w/o UTM') ,
            '============',
            '',
        ]);

        switch ($input->getOption('target')) {
            case 'price':
                $utm = LINK_COUNTER_APPENDIX_PRICE;
                $feedPath = FEED_CONVERTED_PRICE_PATH;
                break;
            case 'push4site':
                $utm = '';
                $feedPath = FEED_CONVERTED_PUSH4SITE_PATH;
                break;
            case 'mail':
                $utm = '';
                $feedPath = FEED_CONVERTED_MAIL_PATH;
                break;
            case 'avito':
                $utm = LINK_COUNTER_APPENDIX_AVITO;
                $feedPath = FEED_CONVERTED_AVITO_PATH;
                break;
            case 'yandex0':
                $utm = '';
                $feedPath = FEED_CONVERTED_YANDEX0_PATH;
                break;
            default:
                $utm = LINK_COUNTER_APPENDIX_YANDEX;
                $feedPath = FEED_CONVERTED_YANDEX_PATH;
        }


        $parser = new \Kiabi\YandexParser(
            FEED_YANDEX_PATH,
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