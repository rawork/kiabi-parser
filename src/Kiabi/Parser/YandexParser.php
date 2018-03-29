<?php

namespace Kiabi\Parser;

use Kiabi\Cutter;
use Kiabi\Replacer;

class YandexParser
{
	protected $feedPath;
	protected $categoriesPath;
	protected $utmMark;
	protected $addUtmMark;

    protected $content = '';
	protected $categories;
	protected $types = [];
	protected $j = 0;
	protected $cutter;
	protected $replacer;
	protected $k = 0;
	protected $deliveryPrice = 299;

	protected $intSizes = ['2XS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', '2XL', 'XXXL', '3XL'];
	protected $monthSizes = ['m', 'M'];
	protected $sisiSizes = ['A', 'B', 'C', 'D', 'E'];
	protected $ages = ['Муж' => 'Взрослый', 'Жен' => 'Взрослый', 'Малыш' => 'Для малышей', 'Дев' => 'Детский', 'Мальч' => 'Детский'];
	protected $gender = ['Муж' => 'Мужской', 'Жен' => 'Женский', 'Дев' => 'Женский', 'Мальч' => 'Мужской'];

    /**
     *  Перечень существительных-товаров
     * @var array
     */
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

    /**
     *  Перечень видов материалов
     * @var array
     */
	protected $materials = [
		'ХЛОПОК',
		'ПОЛИЭСТЕР',
		'ЭЛАСТАН',
		'ПОЛИАМИД',
		'ТКАНЬ',
		'ЭЛАСТОДИН',
		'ВИСКОЗА',
		'НЕЙЛОН',
		'АКРИЛ',
		'МЕТАЛЛИЧЕСКОЕ ВОЛОКНО',
		'НАТУРАЛЬНАЯ КОЖА',
		'БУМАГА',
		'СОЛОМА',
		'КАРТОН',
		'ЛИОСЕЛ',
		'КОЖА',
		'ПЛАСТИК',
		'МЕТАЛЛ',
		'МОДАЛ',
//		'ХИМИЧЕСКИЕ МАТЕРИАЛЫ',
		'ВОЛОКНА'
	];

	protected $titles2 = [];

	public function __construct($feedPath, $categoriesPath, $utmMark,  Cutter $cutter, Replacer $replacer, $addUtmMark = true)
	{
		$this->feedPath = $feedPath;
        $this->categoriesPath = $categoriesPath;
        $this->utmMark = $utmMark;
        $this->addUtmMark = $addUtmMark;
	    $this->cutter = $cutter;
		$this->replacer = $replacer;

		$this->titles2 = array_map( function($a) { return mb_convert_case($a, MB_CASE_TITLE); }, $this->titles);
	}

	protected function getHeader()
	{
		$date = date('Y-m-d H:i');

		$content = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="'.$date.'"> 
  <shop>
	<name>'.STORE_TITLE.'</name>
	<company>'.COMPANY_TITLE.'</company>
	<url>'.STORE_URL.(LINK_COUNTER_APPENDIX_YANDEX == $this->utmMark ? '?utm_source=YandexMarket' : '').'</url>
	<currencies>
		<currency id="RUR" rate="1"/>
	</currencies>
	<categories>
		';

		foreach ($this->getCategories() as $category) {
		    $parentText = $category['parent_key'] ? ' parentId="'.$this->categories[$category['parent_key']]['id'].'"' : '';
            $content .= '		<category id="'.$category['id'].'"'.$parentText.'>'.htmlspecialchars($category['title']).'</category>
            ';
		}

		$content .= '	</categories>';

		// информация о локальной доставке для все товаров отключена (настройки в личном кабинете)
        /*$content .= '
	<delivery-options>
	   	<option cost="'.$this->deliveryPrice.'" days="1-2" order-before="24"/>
	</delivery-options>';*/

        $content .= '	<offers>';

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

		// Отключена обрезка заголовков товаров
//		if (preg_match("/".implode('|', $this->titles)."/", $title, $matches)) {
//			$title = mb_convert_case($matches[0], MB_CASE_TITLE);
//		} else if (preg_match('/'.implode('|', $this->titles2).'/', $title, $matches)) {
//			$title = $matches[0];
//		}

		return htmlspecialchars($title);
	}

	public function generateItem(\SimpleXMLIterator $node)
	{
		$content = '';
        $shipping = '';

        // Информация о локальной доставке конкретного товара ОТКЛЮЧЕНА (настройки в партнерском кабинете)
//		if (isset($node->shipping)) {
//			$shipping = '
//				<delivery-options>
//                	<option cost="'.$this->deliveryPrice.'" days="1-2" order-before="24"/>
//            	</delivery-options>
//  				';
//		}

        if (preg_match('/[а-яё]/iu', $node->title)) {

        } else {
            echo 'NOT RUSSIAN TITLE: ID = '.$node->id.', TITLE = '.$node->title."\n";
            return '';
        }

		$title = $this->getTitle($node->title);

        if ($this->utmMark != LINK_COUNTER_APPENDIX_YANDEX_SMARTBANNER) {
            $title .= ' '.$node->brand;
        }

		$description = trim($node->description);
		$description = $description ? $description : $node->title;

        $description = str_replace('Перед такой ценой устоять невозможно, поэтому рекомендуем взять сразу несколько расцветок, чтобы разнообразить гардероб малыша.', '', $description);
        $description = str_replace('Перед такой ценой устоять невозможно!', '', $description);
        $description = str_replace('Перед такой ценой и расцветками устоять невозможно!', '', $description);
        $description = str_replace('При такой цене устоять невозможно!', '', $description);
        $description = str_replace('по доступной цене', '', $description);
        $description = str_replace('по выгодной цене', '', $description);
		$description = str_replace('по низкой цене', '', $description);
        $description = str_replace('по низким ценам', '', $description);
        $description = str_replace('низкой цене', '', $description);
        $description = str_replace('низкая цена', '', $description);
        $description = str_replace('низкой ценой', '', $description);
        $description = str_replace('низкую цену', '', $description);
        $description = str_replace('низким ценам', '', $description);
		
		$references = $this->sxiToArray($node->references->children());

		$product_type = str_replace(' / ', '|', $node->product_type);
		$genderParam = '';
		$ageParam = '';
		$ageValue = false;

		foreach ($this->gender as $key => $gender) {
			if (mb_strpos($product_type, $key) !== false) {
				$genderParam = '<param name="Пол">'.$gender.'</param>';
				break;
			}
		}

		foreach ($this->ages as $key => $age) {
			if (mb_strpos($product_type, $key) !== false) {
				$ageParam = '<param name="Возраст">'.$age.'</param>';
                $ageValue = $age;
				break;
			}
		}

		$categories = $this->getCategories();

		foreach ($references['reference'] as $reference) {
			$skus = $reference['skus'][0]['sku'];

			$material = $reference['material'][0];
			$materialTag = '';

			if ($material) {
				$searchMaterial = mb_strtoupper($material);
				$realMaterials = [];
				foreach ($this->materials as $materialVariant) {
					if (mb_strstr($searchMaterial, $materialVariant)) {
						$realMaterials[] = mb_ucfirst($materialVariant);
						if (count($realMaterials) >=3 ) {
							break;
						}
					}
				}

				if (count($realMaterials) > 0) {
					$realMaterial = implode(', ', $realMaterials);
					$materialTag = '<param name="Материал">'.$realMaterial.'</param>';
				} else {
//					echo 'Not found: '.$material."\n";
				}

			}

			$pictures = '';
			for ($i = 1; $i <= 5; $i++) {
				if (!empty($reference['additionnal_image_link'.$i.'_https'][0])) {
					$pictures .= '<picture>'.$reference['additionnal_image_link'.$i.'_https'][0].'</picture>';
				}
			}

			$color = $reference['color'][0];
			if ($this->replacer->analize($color)) {
				$color = $this->replacer->replace($color);
			}

			$referenceSizes = [];

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

                $sizeSystem = $node->system_size;
                $sizeSystem = 'RU';

				// обрабатываем размеры перечисленные через / , должен быть один размер
				$sizes = explode('/', $sku['size'][0]);
				$size = trim(count($sizes) > 0 ? $sizes[0] : $sku['size'][0]);
                $complexSize = '';
				if (in_array($size, $this->intSizes)) {
					$sizeSystem = 'INT';
				} elseif (in_array(substr($size, -1), $this->monthSizes) && strlen($size) > 1 ) {
					$sizeSystem = 'Months';
					$size = preg_replace('/'.$this->monthSizes[0].'/i', '', $size);
				} elseif ($ageValue == 'Для малышей' && intval($size) > 50 ) {
                    $sizeSystem = 'Height';
                } elseif ($ageValue == 'Детский' && intval($size) > 80 ) {
                    $sizeSystem = 'Рост';
                } elseif (strpos($title, 'носк')) {
                    $sizeSystem = 'EU';
                } elseif (in_array(substr($size, -1), $this->sisiSizes) && strlen($size) > 1) {
				    $sizeSystem = false;
				    $size0 = substr($size, -1);
				    $size1 = str_replace($size0, '', $size);
				    $complexSize = '<param name="Чашка" unit="RU">'.$size0.'</param><param name="Обхват груди" unit="см">'.$size1.'</param>';
                }

				if (in_array($size, $referenceSizes)) {
					continue;
				}

                $sizeUnits = ' unit="'.$sizeSystem.'"';

                if (in_array($this->utmMark, array(LINK_COUNTER_APPENDIX_YANDEX_SMARTBANNER, LINK_COUNTER_APPENDIX_YANDEX_SMARTBANNER2))) {
                    $sizeUnits = '';
                }

				if ($sizeSystem) {
				    $sizeParam = '<param name="Размер"'.$sizeUnits.'>'.$size.'</param>';
                } else {
                    $sizeParam = $complexSize;
                }


				$referenceSizes[] = $size;

				$currentUtm = ($this->addUtmMark ? str_replace('{offer_id}', $sku['code'][0], $this->utmMark) : '');

				$content .= '<offer id="'.$sku['code'][0].'" available="'.$available.'"><url>'.$reference['link_coloris_https'][0].$currentUtm.'</url><price>'.$price.'</price>'.$oldprice.'<currencyId>RUR</currencyId><categoryId>'.$categoryId.'</categoryId><picture>'.$reference['image_link_https'][0].'</picture>'.$pictures.'<store>true</store><pickup>true</pickup><delivery>true</delivery>'.$shipping.'<name>'.$title.'</name><vendor>'.$node->brand.'</vendor><description><![CDATA['.$description.']]></description><sales_notes>Оплата наличными и банковской картой.</sales_notes><param name="Цвет">'.$color.'</param> '.$sizeParam.$genderParam.$ageParam.$materialTag .'</offer>'."\n";
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
			$this->categories = json_decode(file_get_contents($this->categoriesPath), true);
		}

		return $this->categories;
	}

	public function parse()
	{
		$reader = new \XMLReader();
		$reader->open($this->feedPath);
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