<?php
// src/Kiabi/Command/FeedDownloadCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FeedDownloadCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('feed:download')
            ->setDescription('Download original feed.')
            ->setHelp('This command allows you to download original feed')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);

        $output->writeln([
            'Start download "Kiabi" feed',
            '============',
            '',
        ]);

        $oldTimestamp = 0;

        $ch = curl_init(FEED_URL);  /* create URL handler */
        curl_setopt($ch, CURLOPT_NOBODY, TRUE); /* don't retrieve body contents */
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); /* follow redirects */
        curl_setopt($ch, CURLOPT_HEADER, FALSE); /* retrieve last modification time */
        curl_setopt($ch, CURLOPT_FILETIME, TRUE); /* get timestamp */
        $res = curl_exec($ch);
        $timestamp = curl_getinfo($ch, CURLINFO_FILETIME);
        curl_close($ch);

        if (file_exists(TIMEFILE_PATH)) {
            $oldTimestamp = intval(file_get_contents(TIMEFILE_PATH));
        }

        if ($oldTimestamp < $timestamp) {

            $client = new \Kiabi\Curl();

            $client->get(FEED_URL);

            @unlink(FEED_YANDEX_PATH);

            file_put_contents(FEED_YANDEX_PATH, $client->response);
            file_put_contents(TIMEFILE_PATH, $timestamp);

            $client->close();
        }

        $output->writeln('Feed downloaded');
    }
}