<?php
// src/Kiabi/Command/RBFCheckCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class RBFCheckCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('rbf:check')
            ->setDescription('Check not found goods.')
            ->setHelp('This command allows you to check which RBF goods not found in original feed')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);

        $output->writeln([
            'Search not found goods in "Real BlackFriday" feed',
            '============',
            '',
        ]);

        $rawGoods = array_keys(json_decode(file_get_contents(RBF_GOODS_PATH), true));
        
        $feedGoods = [];

        $reader = new \XMLReader();
        $reader->open(FEED_CONVERTED_RBF_PATH);

        while($reader->read()) {
            if($reader->nodeType == \XMLReader::ELEMENT) {
                if($reader->localName == 'offer') {
                    $node = new \SimpleXMLIterator($reader->readOuterXml());
                    $url = explode('#', $node->url);
                    $feedGoods[] = md5($url[0]);
                }
            }
        }

        $emptyGoods = array_diff($rawGoods, $feedGoods);

        foreach ($emptyGoods as $code) {
            $output->writeln($rawGoods[$code]);
        }
        $output->writeln("Not found ".count($emptyGoods)." offers");
    }
}