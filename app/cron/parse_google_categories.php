#!/usr/bin/env php
<?php

set_time_limit(0);

require_once __DIR__.'/../bootstrap.php';

$categories = [];
if (file_exists(GOOGLE_CATEGORIES_JSON_PATH)) {
	$colors = json_decode(file_get_contents(GOOGLE_CATEGORIES_JSON_PATH), true);
}

$parser = new Kiabi\GoogleCategoryParser();

$parser->parse();

$categories = array_merge($parser->getCategories(), $categories);

echo sprintf("Feed file is parsed: categories = %d pcs.\n", count($categories));

@unlink(GOOGLE_CATEGORIES_JSON_PATH);
@file_put_contents(GOOGLE_CATEGORIES_JSON_PATH, json_encode($categories));

echo "Json saved\n";

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
$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$objPHPExcel->getActiveSheet()->getHighestRow())
	->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$objPHPExcel->getActiveSheet()->getHighestRow())
	->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$objPHPExcel->getActiveSheet()->getHighestRow())
	->getAlignment()->setWrapText(true);

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Kiabi Category');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Google Category ID');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Google Category Title');

$i = 2;

foreach ($categories as $category) {
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $category['title']);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $category['google_id']);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $category['google_title']);

	$i++;
}

// Save Excel 2007 file
$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(GOOGLE_CATEGORIES_XSLX_PATH);

echo "XLSX saved\n";