<?php

namespace Kiabi;

class YandexCategoryParser
{
	protected $types = [];
	protected $categories = [];
	protected $id = 0;

	public function parseItem(\SimpleXMLElement $node)
	{
		$types = array_map('trim', explode('|', str_replace(' / ', '|', $node->product_type)));

		foreach ($types as $level => $type) {

			$categoryText = implode('|', array_slice($types, 0, $level+1));
			$categoryKey = md5($categoryText);

			if (array_key_exists($categoryKey, $this->types)) {
				continue;
			}

			$this->types[$categoryKey] = $this->getId();

			if (!array_key_exists($categoryKey, $this->categories)){
				$this->categories[$categoryKey] = [
					'id' => $this->types[$categoryKey],
					'title' => $type,
					'parent_key' => $level > 0 ? md5(implode('|', array_slice($types, 0, $level))) : 0,
					'type' => implode('|', array_slice($types, 0, $level+1)),
				];
			}

		}
	}

	public function getId()
	{
		return ++$this->id;
	}

	public function getJson() {
		return json_encode($this->categories);
	}

	public function parse()
	{
		$reader = new \XMLReader();
		$reader->open(FEED_YANDEX_PATH);

		while($reader->read()) {
			if($reader->nodeType == \XMLReader::ELEMENT) {
				if($reader->localName == 'googleShoppingProduct') {
					$this->parseItem(new \SimpleXMLElement($reader->readOuterXml()));
				}
			}
		}
	}

}