#!/usr/bin/env php
<?php

set_time_limit(0);

require_once __DIR__.'/../bootstrap.php';

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
	@unlink(FEED_GOOGLE_PATH);

	file_put_contents(FEED_YANDEX_PATH, $client->response);
	file_put_contents(FEED_GOOGLE_PATH, $client->response);
	file_put_contents(TIMEFILE_PATH, $timestamp);

	$client->close();
}
