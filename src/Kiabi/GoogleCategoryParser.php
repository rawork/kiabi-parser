<?php

namespace Kiabi;

class GoogleCategoryParser
{
	protected $categories = [];

	public function parseItem(\SimpleXMLElement $node)
	{
		$type = implode('|', array_map('trim', explode('|', str_replace(' / ', '|', $node->product_type))));

		$this->categories[md5($type)] = ['title' => $type, 'google_id' => '', 'google_title' => ''];
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
		$reader->open(FEED_GOOGLE_PATH);

		while($reader->read()) {
			if($reader->nodeType == \XMLReader::ELEMENT) {
				if($reader->localName == 'googleShoppingProduct') {
					$this->parseItem(new \SimpleXMLElement($reader->readOuterXml()));
				}
			}
		}
	}

}