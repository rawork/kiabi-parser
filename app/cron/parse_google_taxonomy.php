#!/usr/bin/env php
<?php

set_time_limit(0);

require_once __DIR__.'/../bootstrap.php';

$columns = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
$proxy = [];
$data = [];

$objPHPExcel = \PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objPHPExcel->load(GOOGLE_CATEGORIES_PARSED_PATH);
$objPHPExcel->setActiveSheetIndex(0);

$taxonomy = \PHPExcel_IOFactory::createReader('Excel5');
$taxonomy = $taxonomy->load(GOOGLE_TAXONOMY_PATH);
$taxonomy->setActiveSheetIndex(0);

for ($i = 1; $i < $taxonomy->getActiveSheet()->getHighestRow(); $i++) {
	$proxy['c_'.trim($taxonomy->getActiveSheet()->getCell('A'.$i)->getValue())] = $i;
}

for ($i = 2; $i < $objPHPExcel->getActiveSheet()->getHighestRow(); $i++) {
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

			$data['c_'.$id] = ['num' => $proxy['c_'.$id], 'title' => '', 'path' => ''];
//			foreach ($taxonomy->getActiveSheet()->getRowIterator() as $row) {
//				$cellIterator = $row->getCellIterator();
//				$cellIterator->setIterateOnlyExistingCells(true);
//				foreach ($cellIterator as $cell) {
//					if ($cell->getValue() == $id) {
//
//						break 2;
//					}
//				}
//			}

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
$objWriter->save(GOOGLE_CATEGORIES_PARSED_PATH);

echo "Taxomony parsed\n";