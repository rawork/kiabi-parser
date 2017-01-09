<?php

namespace Kiabi;

class GoogleParser
{
	protected $content = '';
	protected $j = 0;

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

	private function sxiToArray($sxi){
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
		$content = '';
		$references = $this->sxiToArray($node->references->children());

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

		foreach ($references['reference'] as $reference) {
			$skus = $reference['skus'][0]['sku'];

			foreach ($skus as $sku) {
				$content .= '<entry>
		<g:id>'.$reference['item_group_id'][0].'-'.$sku['code'][0].'</g:id>
		<g:title>'.htmlspecialchars($node->title).'</g:title>
		<g:description>'.htmlspecialchars($node->description).'</g:description>
		<g:link>'.$reference['link'][0].'</g:link>
		<g:mobile_link>'.$reference['mobile_link'][0].'</g:mobile_link>
		<g:image_link>'.$reference['image_link'][0].'</g:image_link>
		<g:condition>'.$node->condition.'</g:condition>
		<g:availability>'.$sku['availability'][0].'</g:availability>
		<g:price>'.$sku['price'][0].' RUB</g:price>
		<g:sale_price>'.$sku['sale_price'][0].' RUB</g:sale_price>
		<g:product_type>'.$product_type.'</g:product_type>
		<g:brand>'.$node->brand.'</g:brand>
		<g:color>'.$reference['color'][0].'</g:color>
		<g:size>'.$sku['size'][0].'</g:size>
		<g:gtin>'.$sku['gtin'][0].'</g:gtin>
		<g:size_system>'.$node->system_size.'</g:size_system>
		<g:item_group_id>'.$reference['item_group_id'][0].'</g:item_group_id>
		'.$shipping.'
	</entry>
	';
				$this->j++;
			}
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
		$i = 0;

		while($reader->read()) {
			if($reader->nodeType == \XMLReader::ELEMENT) {
				if($reader->localName == 'googleShoppingProduct') {
					$i++;
					$this->content .= $this->generateItem(new \SimpleXMLIterator($reader->readOuterXml()));
				}
			}
		}
		echo 'Feed file is parsed: products = '.$i.' pcs., skus = '.$this->j." pcs.\n";
	}

}