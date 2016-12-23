<?php

namespace Kiabi;

class YandexParser
{
	protected $content = '';
	protected $categories = [];
	protected $types = [];
	protected $generateID = 400001;


	public function getHeader()
	{
		$date = date('Y-m-d H:i');

		$content = '<?xml version="1.0" encoding="UTF-8"?>
<yml_catalog date="'.$date.'"> 
  <shop>
	<name>'.STORE_TITLE.'</name>
	<company>'.COMPANY_TITLE.'</company>
	<url>'.STORE_URL.'</url>
	<currencies>
		<currency id="RUR" rate="1"/>
	</currencies>
	<categories>
';

		foreach ($this->categories as $category) {
			$content .= '		<category id="'.$category['id'].'" parentId="'.$category['parent_id'].'">'.$category['title'].'</category>
';
		}

		$content .= '	</categories>
	<delivery-options>
		<option cost="0" days="31" order-before="24"/>
	</delivery-options>
	<offers>
';

		return $content;
	}

	public function getFooter()
	{
		return '	</offers>
  </shop>
</yml_catalog>';
	}


	public function generateItem(\SimpleXMLElement $node)
	{
		$content = '';
		$skus = $node->references->reference->skus->children();

		if ($skus->sku instanceof \SimpleXMLElement) {
			$sku = [$skus->sku];
		} else {
			$sku = $skus->sku;
		}

//		var_dump($node, $sku);

		$shipping = '';

		if (isset($node->shipping)) {
			$shipping = '<g:shipping>
  			<g:country>'.$node->shipping->country.'</g:country>
  			<g:service>'.$node->shipping->service.'</g:service>
  			<g:price>'.$node->shipping->price.' RUB</g:price>
		</g:shipping>';
		}



		foreach ($sku as $skunode) {
			$content .= '	
	';
		}

		return $content;
	}

	public function generateCategory(\SimpleXMLElement $node)
	{
		$category = [];

		$text = ' '.$node;
		$productTypes = array_map('trim', explode('/', $text));

		var_dump($text, $productTypes);

		return $category;
	}

	public function getXML() {
		return $this->getHeader().$this->content.$this->getFooter();
	}

	public function parse()
	{
		$reader = new \XMLReader();
	 	$reader->open(FEED_GOOGLE_PATH);

		// todo read categories
		while($reader->read()) {
			if($reader->nodeType == \XMLReader::ELEMENT) {
				if($reader->localName == 'product_type') {
					$this->categories[] = $this->generateCategory(new \SimpleXMLElement($reader->readOuterXml()));
				}
			}
		}

		$reader->close();
		return;

		$reader = new \XMLReader();
		$reader->open(FEED_YANDEX_PATH);

		while($reader->read()) {
			if($reader->nodeType == \XMLReader::ELEMENT) {
				if($reader->localName == 'googleShoppingProduct') {
					$this->content .= $this->generateItem(new \SimpleXMLElement($reader->readOuterXml()));
				}
			}
		}
	}

}