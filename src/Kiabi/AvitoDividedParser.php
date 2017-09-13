<?php

namespace Kiabi;


class AvitoDividedParser
{
	protected $content = '';
	protected $categories;
	protected $rootCategories;
	protected $types = [];
	protected $j = 0;
	protected $cutter;
	protected $replacer;
	protected $k = 0;
	protected $deliveryPrice = 299;

	protected $intSizes = ['2XS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', '2XL', 'XXXL', '3XL'];
	protected $monthSizes = ['m', 'M'];
	protected $ages = ['Муж' => 'Взрослый', 'Жен' => 'Взрослый', 'Малыш' => 'Для малышей', 'Дев' => 'Детский', 'Мальч' => 'Детский'];
	protected $gender = ['Муж' => 'Мужской', 'Жен' => 'Женский', 'Дев' => 'Женский', 'Мальч' => 'Мужской'];
	protected $titles = [
		'балетки', 'боди', 'болеро', 'борсалино', 'ботинки', 'брюки', 'бюстгальтер',
		'бетровка', 'водолазка', 'галстук-бабочка', 'галстук', 'джеггинсы', 'джегинсы',
		'джемпер-пончо', 'джемпер', 'джинсы', 'жакет', 'жилет', 'зонт', 'капри', 'кардиган',
		'кеды', 'кепка', 'колготки', 'комбинация', 'комбинезон', 'кроссовки', 'купальник',
		'купальные трусики', 'куртка бомбер', 'куртка', 'леггинсы', 'легинсы', 'лонгслив', 'майка',
		'митенки', 'мокасины', 'накидка', 'наматрасник', 'носки', 'ночная рубашка', 'пальто',
		'парка', 'пиджак', 'пижама', 'плавки', 'платок', 'платье-джемпер', 'платье-колокольчик',
		'платье-комбинезон', 'платье-рубашка', 'платье-футляр', 'платье', 'плащ-накидка', 'плащ',
		'плед', 'повязка', 'покрывало', 'ползунки', 'поло', 'полотенце-накидка', 'полотенце',
		'полусапоги', 'пончо', 'пояс', 'пуловер', 'пуховик', 'ремень', 'рубашка', 'рукавички',
		'сандалии', 'сапоги', 'сапожки', 'сарафан', 'свитер', 'свитшот', 'слюнявчик',
		'спортивный костюм', 'тапочки', 'толстовка', 'топ', 'трегинсы', 'тренч', 'тренчкот', 'трусики',
		'трусики-стринги', 'трусики-танга', 'трусики-шортики', 'трусики-шорты', 'трусы-боксеры',
		'туника', 'туфли', 'туфли-лодочки', 'футболка', 'халат', 'шапочка', 'шаровары', 'шарф',
		'шляпа', 'шортики', 'шорты', 'юбка'
	];
	protected $titles2 = [];

	public function __construct(Cutter $cutter, Replacer $replacer)
	{
		$this->cutter = $cutter;
		$this->replacer = $replacer;

		$this->titles2 = array_map( function($a) { return mb_convert_case($a, MB_CASE_TITLE); }, $this->titles);
	}

	protected function getHeader($rootKey)
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

		foreach ($this->getCategories() as $category) {
			if (!isset($category['root_key']) || $rootKey != $category['root_key']) {
				continue;
			}

			if ($category['parent_key']) {
				$content .= '<category id="'.$category['id'].'" parentId="'.$this->categories[$category['parent_key']]['id'].'">'.$category['title'].'</category>
				';
			} else {
				$content .= '<category id="'.$category['id'].'">'.$category['title'].'</category>
				';
			}
		}

		$content .= '	</categories>
	<delivery-options>
		<option cost="'.$this->deliveryPrice.'" days="1-2" order-before="24"/>
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

	public function getTitle($title)
	{
		$title = ''.$title;

		if (preg_match("/".implode('|', $this->titles)."/", $title, $matches)) {
			$title = mb_convert_case($matches[0], MB_CASE_TITLE);
		} else if (preg_match('/'.implode('|', $this->titles2).'/', $title, $matches)) {
			$title = $matches[0];
		}

		return htmlspecialchars($title);
	}

	public function generateItem(\SimpleXMLIterator $node)
	{
		$content = '';

        if (preg_match('/[а-яё]/iu', $node->title)) {

        } else {
            echo 'NOT RUSSIAN TITLE: ID = '.$node->id.', TITLE = '.$node->title."\n";
            return '';
        }

		$title = $this->getTitle($node->title);
		$title .= ' '.$node->brand;
		//		$title = $this->cutter->cut($node->title);

		$references = $this->sxiToArray($node->references->children());

		$shipping = '';

//		if (isset($node->shipping)) {
//			$shipping = '
//				<delivery-options>
//                	<option cost="'.$node->shipping->price.'" days="1-2" order-before="24"/>
//            	</delivery-options>
//  				';
//		}

		$product_type = str_replace(' / ', '|', $node->product_type);
		$genderParam = '';
		$ageParam = '';

		foreach ($this->gender as $key => $gender) {
			if (mb_strpos($product_type, $key) !== false) {
				$genderParam = '<param name="Пол">'.$gender.'</param>';
				break;
			}
		}

		foreach ($this->ages as $key => $age) {
			if (mb_strpos($product_type, $key) !== false) {
				$ageParam = '<param name="Возраст">'.$age.'</param>';
				break;
			}
		}

		$categories = $this->getCategories();

		$category = $categories[md5($product_type)];
		$rootCategoryKey = $category['root_key'];

		if (!array_key_exists($rootCategoryKey, $this->getRootCategories())) {
			echo 'Root category with given key not found: '.$rootCategoryKey."\n";
		}

		foreach ($references['reference'] as $reference) {
			$skus = $reference['skus'][0]['sku'];

			$pictures = '';
			for ($i = 1; $i <= 5; $i++) {
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

			$referenceSizes = [];

			foreach ($skus as $sku) {
				$sizeSystem = $node->system_size;

				$available = $sku['availability'][0] == 'in stock' ? 'true' : 'false';

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

//				if (strpos($sku['size'][0], 'a')) {
//					var_dump($sku['size'][0]);
//				}

				$sizes = explode('/', $sku['size'][0]);

				$size = trim(count($sizes) > 0 ? $sizes[0] : $sku['size'][0]);

				if (in_array($size, $this->intSizes)) {
					$sizeSystem = 'INT';
				}

				if (in_array(substr($size, -1), $this->monthSizes) ) {
					$sizeSystem = 'Months';
					$size = preg_replace('/'.$this->monthSizes[0].'/i', '', $size);
				}

				if (in_array($size, $referenceSizes)) {
					continue;
				}

				$referenceSizes[] = $size;

				// .LINK_COUNTER_APPENDIX_AVITO

				$this->rootCategories[$rootCategoryKey]['content'] .= '<offer id="'.$sku['code'][0].'" available="'.$available.'">
                <url>'.$reference['link'][0].'</url>  
                <price>'.$price.'</price>'
                .$oldprice.
                '<currencyId>RUR</currencyId>
                <categoryId>'.$categoryId.'</categoryId>
                <picture>'.$reference['image_link'][0].'</picture>
                '.$pictures
				.'	
                <store>true</store>
                <pickup>true</pickup>
                <delivery>true</delivery>'.
					$shipping
					.'<vendor>'.$node->brand.'</vendor>
				<vendorCode>'.trim($node->id).'</vendorCode>	
                <description>'.htmlspecialchars($node->description).'</description>
                <sales_notes>Оплата наличными и банковской картой.</sales_notes>
                <name>'.$title.'</name>
                
                <param name="Цвет">'.$color.'</param>
                <param name="Размер" unit="'.$sizeSystem.'">'.$size.'</param>
                '.$genderParam.$ageParam
				.'
            </offer>	
	';
				$this->j++;
			}
		}

	}

	public function getXML()
	{
		foreach ($this->rootCategories as $key => &$category) {
			$category['content'] = $category['content'] != ''
				? $this->getHeader($key).$category['content'].$this->getFooter()
				: '';
		}
		unset($category);

		return $this->rootCategories;
	}

	public function getCategories()
	{
		if (!$this->categories) {
			$this->categories = json_decode(file_get_contents(YANDEX_CATEGORIES_PATH), true);
		}

		return $this->categories;
	}

	public function getRootCategories() {
		if (!$this->rootCategories) {
			$this->rootCategories = array_filter($this->getCategories(), function($v) {
				return isset($v['is_root']) && $v['is_root'];
			});

			foreach ($this->rootCategories as &$category) {
				$category['content'] = '';
			}
			unset($category);
		}

		return $this->rootCategories;
	}

	public function parse()
	{
		$this->getRootCategories();

		$reader = new \XMLReader();
		$reader->open(FEED_YANDEX_PATH);
		$i = 0;

		while($reader->read()) {
			if($reader->nodeType == \XMLReader::ELEMENT) {
				if($reader->localName == 'googleShoppingProduct') {
					$i++;
					$this->generateItem(new \SimpleXMLIterator($reader->readOuterXml()));
				}
			}
		}

		echo sprintf("Feed file is parsed: products = %d pcs., skus = %d pcs.\n", $i, $this->j);
	}

}