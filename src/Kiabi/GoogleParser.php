<?php

namespace Kiabi;

class GoogleParser
{
	protected $content = '';

	public function getHeader()
	{
		$date = date('Y-m-d');
		$time = date('H:i:s');

		return '<?xml version="1.0"?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:g="http://base.google.com/ns/1.0">
	<title>'.STORE_TITLE.' - Online Store</title>
	<link rel="self" href="'.STORE_URL.'"/>
	<updated>'.$date.'T'.$time.'Z</updated>
	';
	}

	public function getFooter()
	{
		return '</feed>';
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

		$product_type = str_replace('/','&gt;',$node->product_type);
		$types = explode('&gt;', $product_type);
		if (count($types) > 1 && strpos($types[1], trim($types[0])) !== false) {
			unset($types[1]);
			$product_type = implode('&gt;', $types);
		}

		foreach ($sku as $skunode) {
			$content .= '<entry>
		<g:id>'.$node->id.'</g:id>
		<g:title>'.htmlspecialchars($node->title).'</g:title>
		<g:description>'.htmlspecialchars($node->description).'</g:description>
		<g:link>'.$node->references->reference->link.'</g:link>
		<g:mobile_link>'.$node->references->reference->mobile_link.'</g:mobile_link>
		<g:image_link>'.$node->references->reference->image_link.'</g:image_link>
		<g:condition>'.$node->condition.'</g:condition>
		<g:availability>'.$skunode->availability.'</g:availability>
		<g:price>'.$skunode->price.' RUB</g:price>
		<g:sale_price>'.$skunode->sale_price.' RUB</g:sale_price>
		<g:product_type>'.$product_type.'</g:product_type>
		<g:brand>'.$node->brand.'</g:brand>
		<g:color>'.$node->references->reference->color.'</g:color>
		<g:size>'.$skunode->size.'</g:size>
		<g:gtin>'.$skunode->gtin.'</g:gtin>
		<g:size_system>'.$node->system_size.'</g:size_system>
		<g:item_group_id>'.$node->references->reference->item_group_id.'</g:item_group_id>
		'.$shipping.'
	</entry>
	';
		}

		return $content;
	}

	public function getXML() {
		return $this->getHeader().$this->content.$this->getFooter();
	}

	public function parse()
	{
		$reader = new \XMLReader();
		$reader->open(FEED_GOOGLE_PATH);

		while($reader->read()) {
			if($reader->nodeType == \XMLReader::ELEMENT) {
				if($reader->localName == 'googleShoppingProduct') {
					$this->content .= $this->generateItem(new \SimpleXMLElement($reader->readOuterXml()));
				}
			}
		}
	}

}