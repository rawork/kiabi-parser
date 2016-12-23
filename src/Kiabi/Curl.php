<?php

namespace Kiabi;


class Curl extends \Curl\Curl
{
	public $timestamp;

	public function head($url)
	{
		$this->setOpt(CURLOPT_URL, $url);
		$this->setOpt(CURLOPT_NOBODY, true);
		$this->setOpt(CURLOPT_FOLLOWLOCATION, TRUE); /* follow redirects */
		$this->setOpt(CURLOPT_HEADER, FALSE); /* retrieve last modification time */
		$this->setOpt(CURLOPT_FILETIME, TRUE); /* get timestamp */
		$this->exec();
		$this->timestamp = curl_getinfo($this->curl, CURLOPT_FILETIME);

		return $this->error_code;
	}
}