<?php
// src/Kiabi/Command/GoogleTaxonomyParseCommand.php
namespace Kiabi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;

class GoogleTaxonomyParseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('google:taxonomy:parse')
            ->setDescription('Fullfill google categories xlsx with google taxonomy data.')
            ->setHelp('This command allows you to fullfill google categories xlsx with google taxonomy data')
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
            'Start parse Google taxonomy',
            '============',
            '',
        ]);

        $columns = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
        $proxy = [];
        $data = [];

        $objPHPExcel = \PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objPHPExcel->load(GOOGLE_CATEGORIES_XSLX_PATH);
        $objPHPExcel->setActiveSheetIndex(0);

        $taxonomy = \PHPExcel_IOFactory::createReader('Excel5');
        $taxonomy = $taxonomy->load(GOOGLE_TAXONOMY_PATH);
        $taxonomy->setActiveSheetIndex(0);

        for ($i = 1; $i <= $taxonomy->getActiveSheet()->getHighestRow(); $i++) {
            $proxy['c_'.trim($taxonomy->getActiveSheet()->getCell('A'.$i)->getValue())] = $i;
        }

        for ($i = 2; $i <= $objPHPExcel->getActiveSheet()->getHighestRow(); $i++) {
            $idText = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
            if (!$idText){
                continue;
            }

            $ids = strpos($idText, '.') !== false ? explode('.', $idText) : explode(',', $idText);
            $ids = array_map('trim', $ids);

            $titles = [];
            $paths = [];

            foreach ($ids as $id){

                if (!array_key_exists('c_'.$id, $data)) {

                    if (!array_key_exists('c_'.$id, $proxy)) {
                        $output->writeln("Google product category with ID=".$id." not found in taxonomy!");
                        continue;
                    }

                    $data['c_'.$id] = ['num' => $proxy['c_'.$id], 'title' => '', 'path' => ''];

                    $title = '';
                    $path = [];

                    foreach ($columns as $column){
                        $value = $taxonomy->getActiveSheet()->getCell($column.$data['c_'.$id]['num'])->getValue();

                        if (!$value) {
                            break;
                        }

                        $title = $value;
                        $path[] = $value;
                    }

                    $data['c'.$id]['title'] = $title;
                    $data['c'.$id]['path'] = implode(' / ', $path);
                }

                $titles[] = $data['c'.$id]['title'];
                $paths[] = $data['c'.$id]['path'];
            }

            $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, implode(',', $titles));
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, implode(',', $paths));
        }

        // Save Excel 2007 file
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(GOOGLE_CATEGORIES_XSLX_PATH);

        $output->writeln("Taxomony parsed");
    }
}