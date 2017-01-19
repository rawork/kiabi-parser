<?php

namespace Kiabi;


class Cutter
{
	protected $blacklist = [];
	protected $whitelist = [];
	protected $words = [];
	protected $phrases = [];

	public function __construct($blacklist, $whitelist = [])
	{
		$this->blacklist = $blacklist;
		$this->whitelist = $whitelist;
	}

	public function clear($text)
	{
		return preg_replace('/[\,\.\!\?]+/i', '', $text);
	}

	public function trim($text)
	{
		$text = trim($text);
		$text = str_replace('  ', ' ', $text);

		return $text;
	}

	public function findWords($text)
	{
		$this->words = explode(' ', $text);

		return $this->words;
	}

	public function findPhrases() {
		$this->phrases = [];

		$before0 = '';
		$before = '';

		foreach ($this->words as $word) {
			if ($before0) {
				$this->phrases[] = $before0.' '.$before.' '.$word;
			}
			if ($before) {
				$this->phrases[] = $before.' '.$word;
			}
			$before0 = $before;
			$before = $word;
		}

		return $this->phrases;
	}

	public function cut($text)
	{
		$text = $this->clear($text);

		$this->findWords($text);
		$this->findPhrases();

		foreach ($this->phrases as $phrase) {
			if (!in_array($phrase, $this->whitelist) && in_array($phrase, $this->blacklist)) {
				$text = str_replace($phrase, '', $text);
			}
		}

		foreach ($this->words as $word) {
			if (!in_array($word, $this->whitelist) && in_array($word, $this->blacklist)) {
				$text = str_replace($word, '', $text);
			}
		}

		$text = $this->trim($text);

		return $text;
	}
}