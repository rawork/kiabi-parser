<?php

namespace Kiabi;

class YandexCategoryParser
{
	protected $feedPath;
	protected $types = [];
	protected $categories = [];
	protected $rootCategories = [];
	protected $id = 0;

	public function __construct($feedPath, array $categories)
	{
		$this->feedPath = $feedPath;
		$this->categories = $categories;

		foreach ($this->categories as $category) {
			if(intval($category['id']) > $this->id) {
				$this->id = $category['id'];
			}
		}
	}

	public function parseItem(\SimpleXMLElement $node)
	{
		$types = array_map('trim', explode('|', str_replace(' / ', '|', $node->product_type)));
		$rootKey = '';
		foreach ($types as $level => $type) {

			$typePath = $this->getType($types, $level + 1);
			$categoryKey = $this->getKey($typePath);
			if (0 == $level) {
				$rootKey = $categoryKey;
			}
			if (array_key_exists($categoryKey, $this->categories)) {
				$this->categories[$categoryKey]['is_root'] = $level == 0;
				if	(!array_key_exists('root_key', $this->categories[$categoryKey])) {
					$this->categories[$categoryKey]['root_key'] = $rootKey;
				}

				continue;
			}

			$this->categories[$categoryKey] = [
				'id' => $this->getId(),
				'title' => $this->getTitle($types, $level),
				'parent_key' => $level > 0 ? $this->getKey($this->getType($types, $level)) : 0,
				'root_key' => $rootKey,
				'is_root' => $level == 0,
				'type' => $typePath,
			];

			echo sprintf("Found new category \"%s\"\n", $typePath);
		}
	}

	public function getTitle($array, $pos)
	{
		return $array[$pos];
	}

	public function getType($array, $length)
	{
		return implode('|', array_slice($array, 0, $length));
	}

	public function getKey($str) {
		return md5($str);
	}

	public function getId()
	{
		return ++$this->id;
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
		$reader->open($this->feedPath);

		while($reader->read()) {
			if($reader->nodeType == \XMLReader::ELEMENT) {
				if($reader->localName == 'googleShoppingProduct') {
					$this->parseItem(new \SimpleXMLElement($reader->readOuterXml()));
				}
			}
		}
	}

}