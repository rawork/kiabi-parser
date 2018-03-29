<?php

namespace Kiabi\Parser;

class GoogleCategoryParser
{
	protected $categories = [];

	public function __construct($categories)
	{
		$this->categories = $categories;
	}

	public function parseItem(\SimpleXMLElement $node)
	{
		$type = implode('|', array_map('trim', explode('|', str_replace(' / ', '|', $node->product_type))));

		if (!array_key_exists(md5($type), $this->categories)) {
			$this->categories[md5($type)] = ['title' => $type, 'google_id' => '0', 'google_title' => '-'];
			echo sprintf("Found new category \"%s\"\n", $type);
		}
	}

	public function getJson()
	{
		return json_encode($this->categories);
	}

	public function getCategories()
	{
		return $this->categories;
	}

	public function parse()
	{
		$reader = new \XMLReader();
		$reader->open(FEED_ORIGINAL_PATH);

		while($reader->read()) {
			if($reader->nodeType == \XMLReader::ELEMENT) {
				if($reader->localName == 'googleShoppingProduct') {
					$this->parseItem(new \SimpleXMLElement($reader->readOuterXml()));
				}
			}
		}
	}

	public function apply(){

	}

	public function fullfill()
	{

	}

}