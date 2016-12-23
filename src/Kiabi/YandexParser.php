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

		$content .= file_get_contents(CP_CATEGORIES_PATH);

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

		$shipping = '';

		if (isset($node->shipping)) {
			$shipping = '<delivery-options>
                <option cost="'.$node->shipping->price.'" days="31" order-before="24"/>
            </delivery-options><g:shipping>
  			';
		}

		$product_type = str_replace('/','&gt;',$node->product_type);
		$types = explode('&gt;', $product_type);
		if (count($types) > 1 && strpos($types[1], trim($types[0])) !== false) {
			unset($types[1]);
			$product_type = implode('&gt;', $types);
		}


		foreach ($sku as $skunode) {

			$available = $skunode->availability == 'In stock' ? 'true' : 'false';

			$categoryId = 0;

			$content .= '<offer id="'.$node->id.'" available="'.$available.'">
                <url>'.$node->references->reference->link.'</url>
                <price>'.$skunode->price.'</price>
                <currencyId>RUB</currencyId>
                <categoryId>'.$categoryId.'</categoryId>
                <picture>'.$node->references->reference->image_link.'</picture>
                <store>true</store>
                <pickup>true</pickup>
                <delivery>true</delivery>'.
                $shipping
                .'<vendor>'.$node->brand.'</vendor>
                <description>'.htmlspecialchars($node->description).'</sales_notes>
                <name>'.htmlspecialchars($node->title).'</name>
                <oldprice>'.$skunode->sale_price.'</oldprice>
                <param name="Цвет">'.$node->references->reference->color.'</param>
                <param name="Размер">'.$skunode->size.'</param>
            </offer>	
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