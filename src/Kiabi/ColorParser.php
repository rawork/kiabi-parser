<?php

namespace Kiabi;


class ColorParser
{
	protected $replacer;
	protected $colors;
	protected $j = 0;

	public function __construct(Replacer $replacer)
	{
		$this->replacer = $replacer;
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
				$this->colors[md5($color)] = ['source' => $color, 'yandex' => ''];
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
		$reader->open(FEED_YANDEX_PATH);
		$i = 0;

		while($reader->read()) {
			if($reader->nodeType == \XMLReader::ELEMENT) {
				if($reader->localName == 'googleShoppingProduct') {
					$i++;
					$this->generateItem(new \SimpleXMLIterator($reader->readOuterXml()));
				}
			}
		}


		echo sprintf("Feed file is parsed: products = %d pcs., wrong colors = %d pcs.\n", $i, count($this->colors));
	}

}