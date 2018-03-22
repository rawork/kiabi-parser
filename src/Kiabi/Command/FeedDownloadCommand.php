<?php
// src/Kiabi/Command/FeedDownloadCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\NullOutput;

class FeedDownloadCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('feed:download')
            ->setDescription('Download original feed.')
            ->setHelp('This command allows you to download original feed')
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

        if ($oldTimestamp < $timestamp || !file_exists(FEED_ORIGINAL_PATH) || filesize(FEED_ORIGINAL_PATH) == 0) {

//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, FEED_URL);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
////            curl_setopt($ch, CURLOPT_SSLVERSION,3);
//            $data = curl_exec ($ch);
//            $error = curl_error($ch);
//            curl_close ($ch);
//
//            @unlink(FEED_ORIGINAL_PATH);
//
//            var_dump($data);
//            var_dump($error);
//
//            $file = fopen(FEED_ORIGINAL_PATH, "w+");
//            fputs($file, $data);
//            fclose($file);

//            $client = new \Kiabi\Curl();
//            $client->get(FEED_URL);
//
            file_put_contents(FEED_ORIGINAL_PATH, file_get_contents(FEED_URL));
            file_put_contents(TIMEFILE_PATH, $timestamp);

//            $client->close();
            $output->writeln('Feed downloaded');
        } else {
            $output->writeln('Feed not downloaded. Time:'.$timestamp.'. Oldtime:'.$oldTimestamp);
        }


    }
}