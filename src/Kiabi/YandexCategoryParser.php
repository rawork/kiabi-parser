<?php

namespace Kiabi;

class YandexCategoryParser
{
	protected $types = [];
	protected $categories = [];
	protected $id = 0;

	public function parseItem(\SimpleXMLElement $node)
	{
		$product_type = str_replace('/','&gt;',$node->product_type);
		$types = array_map('trim', explode('&gt;', $product_type));
//		var_dump($types);

		foreach ($types as $level => $type) {
			if (array_key_exists($type, $this->types)) {
				continue;
			}

			$this->types[$type] = $this->getId();

			// todo проверить повторяемость названий вложенных категорий

			if (!array_key_exists($this->types[$type], $this->categories)){
				$this->categories[$this->types[$type]] = [
					'id' => $this->types[$type],
					'title' => $type,
					'parent_id' => 0,
				];
			}


			if ($level > 0) {
				$this->categories[$this->types[$type]]['parent_id'] = $this->types[$types[$level-1]];
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