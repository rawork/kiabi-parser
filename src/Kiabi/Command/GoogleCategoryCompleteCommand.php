<?php
// src/Kiabi/Command/GoogleCategoryCompleteCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\NullOutput;

class GoogleCategoryCompleteCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('google:category:complete')
            ->setDescription('Complete google categories json with google taxomony data.')
            ->setHelp('This command allows you to complete google categories json with google taxomony data')
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
            'Start complete Google categories',
            '============',
            '',
        ]);

        $categories = [];
        if (file_exists(GOOGLE_CATEGORIES_JSON_PATH)) {
            $categories = json_decode(file_get_contents(GOOGLE_CATEGORIES_JSON_PATH), true);
        }

        $objPHPExcel = \PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objPHPExcel->load(GOOGLE_CATEGORIES_XSLX_PATH);
        $objPHPExcel->setActiveSheetIndex(0);

        for ($i = 2; $i <= $objPHPExcel->getActiveSheet()->getHighestRow(); $i++) {
            $type = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
            $key = md5($type);
            if (isset($categories[$key])) {
                $categories[$key]['google_id'] = str_replace('.', ',', $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue());
                $categories[$key]['google_title'] = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue();
                $categories[$key]['google_path'] = $objPHPExcel->getActiveSheet()->getCell('G'.$i)->getValue();
                $categories[$key]['age'] = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();
                $categories[$key]['gender'] = $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getValue();
            }
        }

        @unlink(GOOGLE_CATEGORIES_JSON_PATH);
        @file_put_contents(GOOGLE_CATEGORIES_JSON_PATH, json_encode($categories));

        $output->writeln(sprintf("Completed %d Google categories. JSON saved", count($categories)));
    }
}