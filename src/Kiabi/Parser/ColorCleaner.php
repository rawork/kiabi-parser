<?php

namespace Kiabi\Parser;

use Kiabi\Replacer;

class ColorCleaner
{
	protected $replacer;
	protected $feedPath;
	protected $colors = [];
	protected $j = 0;

	public function __construct($feedPath, Replacer $replacer)
	{
		$this->replacer = $replacer;
		$this->feedPath = $feedPath;
	}

	private function sxiToArray($sxi)
	{
		$a = array();

		for( $sxi->rewind(); $sxi->valid(); $sxi->next() ) {
			if(!array_key_exists($sxi->key(), $a)){
				$a[$sxi->key()] = array();
			}
			if($sxi->hasChildren()){
				$a[$sxi->key()][] = $this->sxiToArray($sxi->current());
			}
			else{
				$a[$sxi->key()][] = strval($sxi->current());
			}
		}

		return $a;
	}

	public function generateItem(\SimpleXMLIterator $node)
	{
		$references = $this->sxiToArray($node->references->children());

		foreach ($references['reference'] as $reference) {

			$color = $reference['color'][0];
			if ($this->replacer->analize($color)) {
				if (array_key_exists(md5($color), $this->colors)) {
					$this->colors[md5($color)]['entry'] = [];
				}
			}
		}
	}

	public function getJson()
	{
		return json_encode($this->colors);
	}

	public function getColors()
	{
		return $this->colors;
	}

	public function parse()
	{
		$reader = new \XMLReader();
		$reader->open($this->feedPath);
		$i = 0;

		while($reader->read()) {
			if($reader->nodeType == \XMLReader::ELEMENT) {
				if($reader->localName == 'googleShoppingProduct') {
					$i++;
					$this->generateItem(new \SimpleXMLIterator($reader->readOuterXml()));
				}
			}
		}

		echo sprintf("Color JSON-file is cleaned: products = %d pcs.\n", $i);
	}

}