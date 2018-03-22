<?php
// src/Kiabi/Command/GoogleCategoryParseCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\NullOutput;

class GoogleCategoryParseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('google:category:parse')
            ->setDescription('Build google categories json.')
            ->setHelp('This command allows you to build google categories json & xlsx')
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
            'Start parse Google categories',
            '============',
            '',
        ]);

        $categories = [];
        if (file_exists(GOOGLE_CATEGORIES_JSON_PATH)) {
            $categories = json_decode(file_get_contents(GOOGLE_CATEGORIES_JSON_PATH), true);
        }

        $parser = new \Kiabi\Parser\GoogleCategoryParser($categories);

        $parser->parse();

        $categories = $parser->getCategories();

        $output->writeln(sprintf("Feed file is parsed: categories = %d pcs.", count($categories)));

        @unlink(GOOGLE_CATEGORIES_JSON_PATH);
        @file_put_contents(GOOGLE_CATEGORIES_JSON_PATH, json_encode($categories));

        $output->writeln('JSON saved');

        if (file_exists(GOOGLE_CATEGORIES_XSLX_PATH)) {
            $objPHPExcel = \PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = $objPHPExcel->load(GOOGLE_CATEGORIES_XSLX_PATH);
            $objPHPExcel->setActiveSheetIndex(0);
            $i = $objPHPExcel->getActiveSheet()->getHighestRow()+1;
        } else {
            // Create new PHPExcel object
            $objPHPExcel = new \PHPExcel();

// Set properties
            $objPHPExcel->getProperties()->setCreator("Roman Alyakritskiy");
            $objPHPExcel->getProperties()->setLastModifiedBy("Roman Alyakritskiy");
            $objPHPExcel->getProperties()->setTitle("Kiabi Google Categories Document");

// Add some data
            $objPHPExcel->setActiveSheetIndex(0);

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(70);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(70);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(70);
            $objPHPExcel->getActiveSheet()->getStyle('A1:A'.$objPHPExcel->getActiveSheet()->getHighestRow())
                ->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle('B2:B'.$objPHPExcel->getActiveSheet()->getHighestRow())
                ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('C1:C'.$objPHPExcel->getActiveSheet()->getHighestRow())
                ->getAlignment()->setWrapText(true);

            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Kiabi Category');
            $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Google Category ID');
            $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Google Category Title');
            $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Age');
            $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Sex');
            $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Material');
            $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Google Category Path');
            $i = 2;
        }

        foreach ($categories as $category) {
            if ('-' == $category['google_title']) {
                $found = false;
                foreach ($objPHPExcel->getActiveSheet()->getRowIterator() as $row) {
                    $cellIterator = $row->getCellIterator('A', 'A');
                    $cellIterator->setIterateOnlyExistingCells(true);
                    foreach ($cellIterator as $cell) {
                        if ($cell->getValue() == $category['title']) {
                            $found = true;
                            break 2;
                        }
                    }
                }
                if ($found) {
                    continue;
                }
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $category['title']);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $category['google_id']);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $category['google_title']);

                $i++;
            }

        }

        // Save Excel 2007 file
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(GOOGLE_CATEGORIES_XSLX_PATH);

        $output->writeln('XLS saved');

        $output->writeln('Categories parsed');
    }
}