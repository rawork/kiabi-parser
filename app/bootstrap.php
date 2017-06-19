<?php

require __DIR__.'/config/config.php';

$loader = require __DIR__.'/../vendor/autoload.php';


if(!function_exists('mb_ucfirst'))
{
	function mb_ucfirst($str, $encoding = "UTF-8", $lower_str_end = true) {
		$first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
		$str_end = "";
		if ($lower_str_end) {
			$str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
		}
		else {
			$str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
		}
		$str = $first_letter . $str_end;
		return $str;
	}
}
if (!function_exists('mb_substr_replace'))
{
	function mb_substr_replace($output, $replace, $posOpen, $posClose)
	{
		return mb_substr($output, 0, $posOpen).$replace.mb_strtolower(mb_substr($output, $posClose));
	}
}

$container = new Kiabi\Container();