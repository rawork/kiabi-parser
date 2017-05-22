<?php

namespace Kiabi;

use cijic\phpMorphy\Morphy;

class GoogleParser
{
	protected $content = '';
	protected $j = 0;
	protected $k = 0;
	protected $m = 0;
	protected $deliveryPrice = 299;
	protected $morphy;

	protected $titles = [
		'балетки', 'боди', 'болеро', 'борсалино', 'ботинки', 'брюки', 'бюстгальтер',
		'ветровка', 'водолазка', 'галстук-бабочка', 'галстук', 'джеггинсы', 'джегинсы',
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

	protected $nouns = [
		'БАЛЕТКИ', 'БЕЙСБОЛКА', 'БЕРЕТ', 'БЕРМУДЫ', 'БЛУЗКА', 'БОДИ', 'БОЛЕРО', 'БОРСАЛИНО',
		'БОРТИК', 'БОСОНОЖКИ', 'БОТИНКИ', 'БРЮКИ', 'БЮСТГАЛЬТЕР', 'БЮСТЬЕ', 'ВЕТРОВКА', 'ВОДОЛАЗКА',
		'ВЬЕТНАМКИ', 'ГАЛСТУК', 'ДЖЕГГИНСЫ', 'ДЖЕГИНСЫ', 'ДЖЕМПЕР', 'ДЖИНСЫ', 'ЖАКЕТ', 'ЖИЛЕТ',
		'ЗОНТ',  'КАПРИ', 'КАРДИГАН', 'КЕДЫ', 'КЕПКА', 'КЛЕЕНКА', 'КОЛГОТКИ', 'КОМБИНЕЗОН',
		'КОНВЕРТ', 'КОСТЮМ', 'КОРОБКА', 'КРОССОВКИ', 'КУПАЛЬНИК', 'КУРТКА', 'ЛЕГГИНСЫ',
		'ЛЕГИНСЫ', 'ЛОДОЧКИ', 'МАЙКА', 'МОКАСИНЫ', 'НАГРУДНИК', 'НАМАТРАСНИК', 'ОДИ', ' ОЧКИ',
		'ПАЛЬТО', 'ПАНАМА', 'ПАРКА', 'ПЕНЬЮАР', 'ПЕСОЧНИК', 'ПИДЖАК', 'ПИЖАМА', 'ПЛАВКИ',
		'ПЛАТОК', 'ПЛАТЬЕ', 'ПЛАЩ', 'ПЛЕД', 'ПОВЯЗКА',   'ПОКРЫВАЛО', 'ПОЛО ', 'ПОЛОТЕНЦЕ',
		'ПУЛОВЕР', 'ПОЯС', 'ПУХОВИК', 'РЕМЕНЬ', 'РУБАШКА', 'РУКАВИЧКИ', 'РЮКЗАК', 'САНДАЛИИ', 'САПОГИ',
		'САПОЖКИ', 'САРАФАН', 'СВИТЕР', 'СВИТШОТ', 'СЛЮНЯВЧИК', 'СОРОЧКА', 'СУМКА', 'СУМОЧКА',
		'ТАПОЧКИ', 'ТЕРМОБЕЛЬЁ', 'ТОЛСТОВКА', 'ТОП ', 'ТРЕГИНСЫ', 'ТРЕНЧ', 'ТРУСИКИ', 'ТРУСЫ',
		'ТУНИКА', 'ТУФЛИ', 'ФУТБОЛКА', 'ХАЛАТ', 'ШАПКА', 'ШАПОЧКА', 'ШАРОВАРЫ', 'ШАРФ', 'ШЛЯПА',
		'ШОРТЫ', 'ШТАНЫ', 'ЭСПАДРИЛЬИ', 'ЮБКА',

		'КОЛГОТОК',
		'ЛЕГИНС',
		'МАЕК',
		'НОСКОВ',
		'ПЕЛЕНОК',
		'ПИЖАМ',
		'ПОЛОТЕНЦА',
		'ПРОСТЫНЕЙ',
		'РЕМНЕЙ',
		'ТРУСИКОВ',
		'ТРУСОВ',
		'ТУНИК',
		'ФУТБОЛОК',
		'ФУТБОЛКИ',
		'ШОРТ',
		'ТОПОВ',
		'МИТЕНОК',
	];

	protected $endings = [
		['ые', 'ие'],
		['ая', 'яя'],
		[],
		['ые', 'ие'],
		['ая', 'яя'],
		['ое', 'ее'],
		['ое', 'ее'],
		['ое', 'ее'],
		[],
		['ые', 'ие'],
		['ые', 'ие'],
		['ые', 'ие'],
		[],
		['ое', 'ее'],
		['ая', 'яя'],
		['ая', 'яя'],
		['ые', 'ие'],
		[],
		['ые', 'ие'],
		['ые', 'ие'],
		[],
		['ые', 'ие'],
		[],
		[],
		[],
		['ые', 'ие'],
		[],
		['ые', 'ие'],
		['ая', 'яя'],
		['ая', 'яя'],
		['ые', 'ие'],
		[],
		[],
		[],
		['ая', 'яя'],
		['ые', 'ие'],
		[],
		['ая', 'яя'],
		['ые', 'ие'],
		['ые', 'ие'],
		['ые', 'ие'],
		['ая', 'яя'],
		['ые', 'ие'],
		[],
		[],
		['ое', 'ее'],
		['ые', 'ие'],
		['ое', 'ее'],
		['ая', 'яя'],
		['ая', 'яя'],
		[],
		[],
		[],
		['ая', 'яя'],
		['ые', 'ие'],
		[],
		['ое', 'ее'],
		[],
		[],
		[],
		['ое', 'ее'],
		['ое', 'ее'],
		['ое', 'ее'],
		[],
		[],
		[],
		[],
		['ая', 'яя'],
		['ые', 'ие'],
		[],
		['ые', 'ие'],
		['ые', 'ие'],
		['ые', 'ие'],
		[],
		[],
		[],
		[],
		['ая', 'яя'],
		['ая', 'яя'],
		['ая', 'яя'],
		['ые', 'ие'],
		['ое', 'ее'],
		['ая', 'яя'],
		[],
		['ые', 'ие'],
		[],
		['ые', 'ие'],
		['ые', 'ие'],
		['ая', 'яя'],
		['ые', 'ие'],
		['ая', 'яя'],
		[],
		['ая', 'яя'],
		['ая', 'яя'],
		['ые', 'ие'],
		[],
		['ая', 'яя'],
		['ые', 'ие'],
		['ые', 'ие'],
		['ые', 'ие'],
		['ая', 'яя'],
		['ых', 'их'],
		['ых', 'их'],
		['ых', 'их'],
		['ых', 'их'],
		['ых', 'их'],
		['ых', 'их'],
		[], // ПОЛОТЕНЦА
		['ых', 'их'],
		['ых', 'их'],
		['ых', 'их'],
		['ых', 'их'],
		['ых', 'их'],
		['ых', 'их'],
		['ой', 'ей'], // ФУТБОЛКИ
		['ых', 'их'],
		['ых', 'их'],
		['ых', 'их'],
	];

	protected $genders = [
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужский', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужское', 'female' => 'женское', 'unisex' => 'унисекс'],
		['male' => 'мужское', 'female' => 'женское', 'unisex' => 'унисекс'],
		['male' => 'мужское', 'female' => 'женское', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужское', 'female' => 'женское', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужское', 'female' => 'женское', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужское', 'female' => 'женское', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужское', 'female' => 'женское', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужское', 'female' => 'женское', 'unisex' => 'унисекс'],
		['male' => 'мужское', 'female' => 'женское', 'unisex' => 'унисекс'],
		['male' => 'мужское', 'female' => 'женское', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужское', 'female' => 'женское', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужские', 'female' => 'женские', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],

		['male' => 'мужских', 'female' => 'женских', 'unisex' => 'унисекс'],
		['male' => 'мужских', 'female' => 'женских', 'unisex' => 'унисекс'],
		['male' => 'мужских', 'female' => 'женских', 'unisex' => 'унисекс'],
		['male' => 'мужских', 'female' => 'женских', 'unisex' => 'унисекс'],
		['male' => 'мужских', 'female' => 'женских', 'unisex' => 'унисекс'],
		['male' => 'мужских', 'female' => 'женских', 'unisex' => 'унисекс'],
		['male' => 'мужское', 'female' => 'женское', 'unisex' => 'унисекс'],
		['male' => 'мужских', 'female' => 'женских', 'unisex' => 'унисекс'],
		['male' => 'мужских', 'female' => 'женских', 'unisex' => 'унисекс'],
		['male' => 'мужских', 'female' => 'женских', 'unisex' => 'унисекс'],
		['male' => 'мужских', 'female' => 'женских', 'unisex' => 'унисекс'],
		['male' => 'мужских', 'female' => 'женских', 'unisex' => 'унисекс'],
		['male' => 'мужских', 'female' => 'женских', 'unisex' => 'унисекс'],
		['male' => 'мужское', 'female' => 'женское', 'unisex' => 'унисекс'],
		['male' => 'мужских', 'female' => 'женских', 'unisex' => 'унисекс'],
		['male' => 'мужских', 'female' => 'женских', 'unisex' => 'унисекс'],
		['male' => 'мужских', 'female' => 'женских', 'unisex' => 'унисекс'],
	];

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
	protected $categories = [];
	protected $searchTexts = [
		176 => 'галстук|Галстук', 179 => 'подтяжки|Подтяжки', 169 => 'ремен|Ремен',
		2271 => 'плать|Плать', 1581 => 'юбк|Юбк', 5624=> 'варежк|Варежк|перчатк|Перчатк|шарф|Шарф', 5625 => 'шапк|Шапк|шапочк|Шапочк',
	];

	public function __construct()
	{
		$this->titles2 = array_map( function($a) { return mb_convert_case($a, MB_CASE_TITLE); }, $this->titles);
		$this->getCategories();
		$this->morphy = new Morphy('ru');
	}

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

	private function sxiToArray($sxi)
	{
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

	public function getCategories()
	{
		if (!$this->categories) {
			$this->categories = json_decode(file_get_contents(GOOGLE_CATEGORIES_JSON_PATH), true);
		}

		return $this->categories;
	}

	public function generateItem(\SimpleXMLIterator $node)
	{
		$content = '';

		//$title = $this->getTitle($node->title);
		$title = htmlspecialchars($node->title);

		// Ищем главное слово-товар в названии для подстановки пола и цвета
		$nounFound = false;
		$nounPosition = -1;
		$nounIndex = -1;
		$searchTitle =  mb_strtoupper($title);
		foreach ($this->nouns as $nounKey => $noun) {
			$nounPosition = mb_strpos($searchTitle, $noun);
			$nounIndex = $nounKey;
			if ($noun && $nounPosition !== false) {
				$nounFound = true;
				//echo $noun."\n";
				break;
			}
		}

		if (!$nounFound) {
			// todo collect all titles with ids and send by email
			echo $node->id.' Title = '.$title."\n";
			$this->m++;
		}

		if (!$title) {
			return '';
		}

		// Проверка на наличие французский товаров
//		if (preg_match("/[а-яё]/iu", $title, $matches, PREG_OFFSET_CAPTURE)) {
//
//		} else {
//			echo $title."\n";
//		}

		$references = $this->sxiToArray($node->references->children());

		$shipping = '';
		if (isset($node->shipping)) {
			$shipping = '<g:shipping>
  			<g:country>'.$node->shipping->country.'</g:country>
  			<g:service>'.$node->shipping->service.'</g:service>
  			<g:price>'.$this->deliveryPrice.' RUB</g:price>
		</g:shipping>';
		}

		$product_type = str_replace(' / ', '&gt;', $node->product_type);
		$types  = $types0 = array_map('trim', explode('&gt;', $product_type));

		if (count($types) > 1 && strpos($types[1], trim($types[0])) !== false) {
			unset($types[1]);
			$product_type = implode(' &gt; ', array_map('ucfirst', $types));
		} else {
			$product_type = str_replace('&gt;', ' &gt; ', $product_type);
		}

		$key = md5(implode('|', $types0));

		$googleProductCategory = '';
		$age = '';
		$gender = '';
		$genderGroup = '';
		$ageGroup = '';

		if (array_key_exists($key, $this->categories)) {
			$category = $this->categories[$key];

			if (isset($category['age'])) {
				$ageGroup = $category['age'];
				$age = "<g:age_group>$ageGroup</g:age_group>";
			}
			if (isset($category['gender'])) {
				$genderGroup = $category['gender'];
				$gender = "<g:gender>$genderGroup</g:gender>";
			}

			if ($category['google_id']){
				$categoryId = 0;
				$categoryIds = array_map('trim', explode(',', $category['google_id']));

				if(count($categoryIds) == 1) {
					$categoryId = $categoryIds[0];
				} else {
					foreach ($categoryIds as $key => $id) {
						// todo correct this $id not found for some products
						if (!array_key_exists($id, $this->searchTexts)) {
							continue;
						}
						$searchText = $this->searchTexts[$id];
						if (preg_match("/($searchText)/", $title, $matches)) {
							$categoryId = $id;
							break;
						}
					}
				}

				if ($categoryId != 0) {
					$googleProductCategory = "<g:google_product_category>$categoryId</g:google_product_category>";
				}
			}
		}

		$description = trim($node->description);
		$description = htmlspecialchars($description ? $description : $node->title);

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
					$realMaterial = implode('/', $realMaterials);
					$materialTag = '<g:material>'.$realMaterial.'</g:material>';
				} else {
//					echo 'Not found: '.$material."\n";
				}

			}

//			echo $reference['material'][0]."\n";

			if (!$age || !$gender) {
				$this->k++;
			}

			// формируем корректный цвет в соответствии со спецификацией
			$multipleColor = false;
			$color = $standardColor = $reference['color'][0];
			if (mb_strstr($color, '/')) {
				$multipleColor = true;
			} else {
				// ищем составной цвет, например, светло-синий
				$colors = explode('-', $color);
				if (count($colors) > 1) {
					$standardColor = $colors[1];
//				echo 'Var 1:'.$color.'='.$standardColor."\n";
				} else {
					//  ищем цвет-свловосочетание, как правило, первое слово - это цвет
					$colors2 = explode(' ', $color);
					$standardColor = mb_strlen($colors2[0]) > 1 ? $colors2[0] : (isset($colors2[1]) ? $colors2[1] : $color);
//				echo 'Var 2:'.$color.'='.$standardColor."\n";
				}
			}

			// насыщаем название предложения цветом и полом
			$currentTitle = $title;
			if($nounFound && $title && !$multipleColor) {
				$nounRoot = $this->morphy->getPseudoRoot(mb_strtoupper($standardColor));
				if ($nounRoot[0] != 'ДЖИНСОВ' && $nounRoot[0] != null && mb_strlen($nounRoot[0]) != 1) {
					$nounEnding = $nounRoot[0] == 'СИН' ? 1 : 0;
					if (count($this->endings[$nounIndex]) > 0) {
						if ($nounPosition === 0) {
							$nounColorRoot = mb_ucfirst($nounRoot[0]);
						} else {
							$nounColorRoot = mb_strtolower($nounRoot[0]);
						}
						$currentTitle = trim(mb_substr_replace($currentTitle, $nounColorRoot.($nounRoot[0] != mb_strtoupper($standardColor) ? $this->endings[$nounIndex][$nounEnding] : '').' ', $nounPosition, $nounPosition));
					} else {
						$currentTitle = trim(mb_substr_replace($currentTitle, ($nounPosition === 0 ? $standardColor : mb_strtolower($standardColor)).' ', $nounPosition, $nounPosition));
					}

					$currentTitle = mb_ucfirst($currentTitle);

//				var_dump($nounColorRoot, $nounEnding, $currentTitle);

				}

			}

			if ($title && $nounFound && $genderGroup && $genderGroup != 'unisex') {
				if ($ageGroup && $ageGroup == 'adult' && $this->genders[$nounIndex][$genderGroup] != '') {
					$currentTitle .= ', '.$this->genders[$nounIndex][$genderGroup];
				} else {
					if($genderGroup == 'male') {
						$currentTitle .= ', для мальчиков';
					}
					if($genderGroup == 'female') {
						$currentTitle .= ', для девочек';
					}
				}
			}

			echo $title.' => '.$currentTitle."\n";

			foreach ($skus as $sku) {

				$content .= '<entry>
		<g:id>'.$reference['item_group_id'][0].'-'.$sku['code'][0].'</g:id>
		<g:title>'.$currentTitle.'</g:title>
		<g:description>'.$description.'</g:description>
		<g:link>'.$reference['link'][0].'</g:link>
		<g:mobile_link>'.$reference['mobile_link'][0].'</g:mobile_link>
		<g:image_link>'.$reference['image_link'][0].'</g:image_link>
		<g:condition>'.$node->condition.'</g:condition>
		<g:availability>'.$sku['availability'][0].'</g:availability>
		<g:price>'.$sku['price'][0].' RUB</g:price>
		<g:sale_price>'.$sku['sale_price'][0].' RUB</g:sale_price>
		<g:product_type>'.$product_type.'</g:product_type>
		<g:brand>'.$node->brand.'</g:brand>
		<g:color>'.$standardColor.'</g:color>
		<g:size>'.$sku['size'][0].'</g:size>
		<g:gtin>'.$sku['gtin'][0].'</g:gtin>
		<g:size_system>'.$node->system_size.'</g:size_system>
		<g:item_group_id>'.$reference['item_group_id'][0].'</g:item_group_id>
		'.$shipping.$age.$gender.$googleProductCategory.$materialTag.'
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

		echo sprintf("Feed file is parsed: products = %d pcs., skus = %d pcs. NO AGE & GENDER = %d\n", $i, $this->j, $this->k);
		echo sprintf("Titles without color & gender %d pcs.\n", $this->m);
	}

}