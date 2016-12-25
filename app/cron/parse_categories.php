#!/usr/bin/env php
<?php

set_time_limit(0);

require_once __DIR__.'/../bootstrap.php';

$parser = new Kiabi\CategoryParser();

$parser->parse();

@unlink(CATEGORIES_PATH);
@file_put_contents(CATEGORIES_PATH, $parser->getXML());
