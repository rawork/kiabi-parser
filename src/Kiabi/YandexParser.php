<?php

namespace Kiabi;

use Kiabi\Cutter;

class YandexParser
{
	protected $content = '';
	protected $categories;
	protected $types = [];
	protected $j = 0;
	protected $cutter;
	protected $replacer;

	protected $intSizes = ['2XS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', '2XL', 'XXXL', '3XL'];
	protected $monthSize = 'm';

	public function __construct(Cutter $cutter, Replacer $replacer)
	{
		$this->cutter = $cutter;
		$this->replacer = $replacer;
	}

	protected function getHeader()
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
			$content .= '<category id="'.$category['id'].'" parentId="'.($category['parent_key'] ? $this->categories[$category['parent_key']]['id'] : 0).'">'.$category['title'].'</category>
		';
		}

		$content .= '	</categories>
	<delivery-options>
		<option cost="0" days="17-19" order-before="24"/>
	</delivery-options>
	<offers>
';

		return $content;
	}

	protected function getFooter()
	{
		return '	</offers>
  </shop>
</yml_catalog>';
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
			$shipping = '
				<delivery-options>
                	<option cost="'.$node->shipping->price.'.00" days="17-19" order-before="24"/>
            	</delivery-options>
  				';
		}

		$product_type = str_replace(' / ', '|', $node->product_type);
		$categories = $this->getCategories();



//		$title = $this->cutter->cut($node->title);

		$sizeSystem = $node->system_size;

		foreach ($references['reference'] as $reference) {
			$skus = $reference['skus'][0]['sku'];

			$pictures = '';
			for ($i = 1; $i <= 10; $i++) {
				if (!empty($reference['additionnal_image_link'.$i][0])) {
					$pictures .= '
					<picture>'.$reference['additionnal_image_link'.$i][0].'</picture>
					';
				}
			}

			$color = $reference['color'][0];
			if ($this->replacer->analize($color)) {
				$color = $this->replacer->replace($color);
			}

			foreach ($skus as $sku) {

				$available = $sku['availability'][0] == 'in stock' ? 'true' : 'false';

				$category = $categories[md5($product_type)];

				$categoryId = $category['id'];

				if (isset($sku['sale_price']) && floatval($sku['sale_price'][0]) < floatval($sku['price'][0])) {
					$oldprice = '
				<oldprice>'.$sku['price'][0].'</oldprice>
';
					$price = $sku['sale_price'][0];
				} else {
					$price = $sku['price'][0];
					$oldprice = '';
				}

				if (in_array(trim($sku['size'][0]), $this->intSizes)) {
					$sizeSystem = 'INT';
				}

				if (strpos($sku['size'][0], $this->monthSize)) {
					$sizeSystem = 'Months';
				}

				$content .= '<offer id="'.$sku['code'][0].'" available="'.$available.'">
                <url>'.$reference['link'][0].'</url>
                <price>'.$price.'</price>'
                .$oldprice.
                '<currencyId>RUB</currencyId>
                <categoryId>'.$categoryId.'</categoryId>
                <picture>'.$reference['image_link'][0].'</picture>
                '.$pictures
				.'	
                <store>true</store>
                <pickup>true</pickup>
                <delivery>true</delivery>'.
					$shipping
					.'<vendor>'.$node->brand.'</vendor>
                <description>'.htmlspecialchars($node->description).'</description>
                <sales_notes>Оплата наличными и банковской картой.</sales_notes>
                <name>'.htmlspecialchars($node->title).'</name>
                
                <param name="Цвет">'.$color.'</param>
                <param name="Размер" unit="'.$sizeSystem.'">'.$sku['size'][0].'</param>
            </offer>	
	';
				$this->j++;
			}
		}

		return $content;
	}

	public function getXML()
	{
		return $this->getHeader().$this->content.$this->getFooter();
	}

	public function getCategories()
	{
		if (!$this->categories) {
			$this->categories = json_decode(file_get_contents(YANDEX_CATEGORIES_PATH), true);
		}

		return $this->categories;
	}

	public function parse()
	{
		$reader = new \XMLReader();
		$reader->open(FEED_YANDEX_PATH);
		$i = 0;

		while($reader->read()) {
			if($reader->nodeType == \XMLReader::ELEMENT) {
				if($reader->localName == 'googleShoppingProduct') {
					$i++;
					$this->content .= $this->generateItem(new \SimpleXMLIterator($reader->readOuterXml()));
				}
			}
		}

		echo sprintf("Feed file is parsed: products = %d pcs., skus = %d pcs.\n", $i, $this->j);
	}

}