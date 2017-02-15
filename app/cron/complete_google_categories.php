#!/usr/bin/env php
<?php

set_time_limit(0);

require_once __DIR__.'/../bootstrap.php';

$categories = [];
if (file_exists(GOOGLE_CATEGORIES_JSON_PATH)) {
	$categories = json_decode(file_get_contents(GOOGLE_CATEGORIES_JSON_PATH), true);
}

$objPHPExcel = \PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objPHPExcel->load(GOOGLE_CATEGORIES_PARSED_PATH);
$objPHPExcel->setActiveSheetIndex(0);


for ($i = 2; $i < $objPHPExcel->getActiveSheet()->getHighestRow(); $i++) {
	$type = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
	$key = md5($type);
	$categories[$key]['google_id'] = str_replace('.', ',', $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue());
	$categories[$key]['google_title'] = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue();
	$categories[$key]['google_path'] = $objPHPExcel->getActiveSheet()->getCell('G'.$i)->getValue();
	$categories[$key]['age'] = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();
	$categories[$key]['gender'] = $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getValue();
}

@unlink(GOOGLE_CATEGORIES_JSON_PATH);
@file_put_contents(GOOGLE_CATEGORIES_JSON_PATH, json_encode($categories));

echo sprintf("Completed %d Google categories. JSON saved\n", count($categories));
