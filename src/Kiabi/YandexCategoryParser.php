<?php

namespace Kiabi;

class YandexCategoryParser
{
	protected $types = [
		0 => [],
		1 => [],
		2 => [],
		3 => [],
		4 => [],
		5 => [],
		6 => [],
		7 => [],
		8 => [],
		9 => []
	];
	protected $categories = [];
	protected $id = 0;

	public function parseItem(\SimpleXMLElement $node)
	{
		$product_type = str_replace('/','&gt;',$node->product_type);
		$types = array_map('trim', explode('&gt;', $product_type));
//		var_dump($types);

		foreach ($types as $level => $type) {
//			var_dump($level);
			if (array_key_exists($type, $this->types[$level])) {
				continue;
			}

			$this->types[$level][$type] = $this->getId();

			// todo проверить повторяемость названий вложенных категорий

			if (!array_key_exists($this->types[$level][$type], $this->categories)){
				$this->categories[$this->types[$level][$type]] = [
					'id' => $this->types[$level][$type],
					'title' => $type,
					'parent_id' => 0,
				];
			}


			if ($level > 0) {
				$this->categories[$this->types[$level][$type]]['parent_id'] = $this->types[$level-1][$types[$level-1]];
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