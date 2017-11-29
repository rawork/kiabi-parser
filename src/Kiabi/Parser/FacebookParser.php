<?php

namespace Kiabi\Parser;

use cijic\phpMorphy\Morphy;

class FacebookParser
{
	protected $content = '';
	protected $skuAmount = 0;
	protected $deliveryPrice = 299;
	protected $morphy;
	protected $feedPath;
	protected $utmMark;
    protected $utmMarkMobile;
	protected $addUtmMark;

	protected $titles = [
		'балетки', 'бейсболка', 'берет', 'бермуды-чинос', 'бермуды', 'блузка', 'боди', 'болеро', 'борсалино', 'босоножки',
		'ботинки', 'пижамные брюки', 'брюки-шаровары', 'брюки-чинос', 'брюки', /*'бюстгальтер-бандо', 'бюстгальтер', 'бюстье',*/
		'ветровка', 'водолазка', 'вьетнамки', 'галстук-бабочка', 'галстук', 'джеггинсы', 'джегинсы',
		'джемпер-пончо', 'джемпер', 'джинсы', 'жакет', 'жилет', 'зонт', 'капри-шаровары', 'капри', 'кардиган',
		'кеды', 'кепка', 'колготки', 'комбинация', 'комбинезон-шорты', 'комбинезон', 'косметичка', 'кроссовки', 'купальник',
		'купальные трусики', 'куртка бомбер', 'куртка-бомбер','куртка', 'леггинсы', 'легинсы', 'лонгслив', 'майка',
		'митенки', 'мокасины', 'накидка', 'наматрасник', 'носки', 'ночная рубашка', 'пальто', 'панама',
		'парка', 'пеньюар', 'пиджак', 'пижама', 'плавки', 'платок', 'платье-джемпер', 'платье-колокольчик',
		'платье-комбинезон', 'платье-рубашка', 'платье-футляр', 'платье', 'плащ-накидка', 'плащ',
		'плед', 'повязка', 'покрывало', 'ползунки', 'шарф-снуд', 'футболка', 'ночная рубашка', 'рубашка', 'рубашка-поло',
		'полотенце-накидка', 'полотенце', 'полусапоги', 'пончо',  'пуловер', 'пуховик', 'ремень', 'рукавички', 'рюкзак',
		'сандалии', 'сапоги', 'сапожки', 'сарафан', 'свитер', 'свитшот', 'слюнявчик',
		'спортивный костюм', 'сумка', 'сумочка', 'тапочки-сапожки', 'тапочки', 'тельняшка', 'толстовка', 'топ', 'трегинсы', 'тренч',
		'тренчкот',
		'купальные трусики', 'трусики-стринги', 'трусики-танга', 'трусики-шортики', 'трусики-шортики', 'трусики-шорты',
		'трусики', 'трусы-боксеры', 'туника', 'туфли-лодочки', 'туфли', 'халат', 'шапка', 'шапочка', 'шаровары', 'шарф',
		'шляпа', 'шортики', 'шорты-бермуды', 'шорты', 'штаны', 'эспадрильи', 'юбка', 'пояс', 'поло',
	];

	protected $nouns = [
		0 => 'БАЛЕТКИ', 1 => 'БЕЙСБОЛКА', 2 => 'БЕРЕТ', 3 => 'БЕРМУДЫ', 4 => 'БЛУЗКА', 5 => 'БОДИ', 6 => 'БОЛЕРО', 7 => 'БОРСАЛИНО',
		8 => 'БОРТИК', 9 => 'БОСОНОЖКИ', 10 => 'БОТИНКИ', 11 => 'БРЮКИ', 12 => 'БЮСТГАЛЬТЕР', 13 => 'БЮСТЬЕ', 14 => 'ВЕТРОВКА',
		15 => 'ВОДОЛАЗКА', 16 => 'ВЬЕТНАМКИ', 17 => 'ГАЛСТУК', 18 => 'ДЖЕГГИНСЫ', 19 => 'ДЖЕГИНСЫ', 20 => 'ДЖЕМПЕР',
		21 => 'ДЖИНСЫ', 22 => 'ЖАКЕТ', 23 => 'ЖИЛЕТ', 24 => 'ЗОНТ',  25 => 'КАПРИ', 26 => 'КАРДИГАН', 27 => 'КЕДЫ',
		28 => 'КЕПКА', 29 => 'КЛЕЕНКА', 30 => 'КОЛГОТКИ', 31 => 'КОМБИНЕЗОН', 32 => 'КОНВЕРТ', 33 => 'КОСТЮМ',
		34 => 'КОРОБКА', 35 => 'КРОССОВКИ', 36 => 'КУПАЛЬНИК', 37 => 'КУРТКА', 38 => 'ЛЕГГИНСЫ', 39 => 'ЛЕГИНСЫ',
		40 => 'ЛОДОЧКИ', 41 => 'МАЙКА', 42 => 'МОКАСИНЫ', 43 => 'НАГРУДНИК', 44 => 'НАМАТРАСНИК', 45 => 'ОДИ',
		46 => ' ОЧКИ', 47 => 'ПАЛЬТО', 48 => 'ПАНАМА', 49 => 'ПАРКА', 50 => 'ПЕНЬЮАР', 51 => 'ПЕСОЧНИК', 52 => 'ПИДЖАК',
		53 => 'ПИЖАМА', 54 => 'ПЛАВКИ', 55 => 'ПЛАТОК', 56 => 'ПЛАТЬЕ', 57 => 'ПЛАЩ', 58 => 'ПЛЕД', 59 => 'ПОВЯЗКА',
		60 => 'ПОКРЫВАЛО', 61 => 'ПОЛО', 62 => 'ПОЛОТЕНЦЕ', 63 => 'ПУЛОВЕР', 64 => 'ПОЯС', 65 => 'ПУХОВИК', 66 => 'РЕМЕНЬ',
		67 => 'РУБАШКА', 68 => 'РУКАВИЧКИ', 69 => 'РЮКЗАК', 70 => 'САНДАЛИИ', 71 => 'САПОГИ', 72 => 'САПОЖКИ',
		73 => 'САРАФАН', 74 => 'СВИТЕР', 75 => 'СВИТШОТ', 76 => 'СЛЮНЯВЧИК', 77 => 'СОРОЧКА', 78 => 'СУМКА', 79 => 'СУМОЧКА',
		80 => 'ТАПОЧКИ', 81 => 'ТЕРМОБЕЛЬЁ', 82 => 'ТОЛСТОВКА', 83 => 'ТОП', 84 => 'ТРЕГИНСЫ', 85 => 'ТРЕНЧ', 86 => 'ТРУСИКИ',
		87 => 'ТРУСЫ', 88 => 'ТУНИКА', 89 => 'ТУФЛИ', 90 => 'ФУТБОЛКА', 91 => 'ХАЛАТ', 92 => 'ШАПКА', 93 => 'ШАПОЧКА',
		94 => 'ШАРОВАРЫ', 95 => 'ШАРФ', 96 => 'ШЛЯПА', 97 => 'ШОРТЫ', 98 => 'ШТАНЫ', 99 => 'ЭСПАДРИЛЬИ', 100 => 'ЮБКА',

		101 => 'КОЛГОТОК',
		102 => 'ЛЕГИНС',
		103 => 'МАЕК',
		104 => 'НОСКОВ',
		105 => 'ПЕЛЕНОК',
		106 => 'ПИЖАМ',
		107 => 'ПОЛОТЕНЦА',
		108 => 'ПРОСТЫНЕЙ',
		109 => 'РЕМНЕЙ',
		110 => 'ТРУСИКОВ',
		111 => 'ТРУСОВ',
		112 => 'ТУНИК',
		113 => 'ФУТБОЛОК',
		114 => 'ФУТБОЛКИ',
		115 => 'ШОРТ',
		116 => 'ТОПОВ',
		117 => 'МИТЕНОК',
		118 => 'ПЛАТЬЕМ',
		119 => 'ПЛАТЬЕВ',
		120 => 'КОСМЕТИЧКА',
		121 => 'ТЕЛЬНЯШКА',
		122 => 'НАКИДКА',
		123 => 'ЛОНГСЛИВ',
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
		['ым', 'им'],
		['ых', 'их'],
		['ая', 'яя'],
		['ая', 'яя'],
		['ая', 'яя'],
		[],
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
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
		['male' => 'мужских', 'female' => 'женских', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужская', 'female' => 'женская', 'unisex' => 'унисекс'],
		['male' => 'мужской', 'female' => 'женский', 'unisex' => 'унисекс'],
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

	protected $messages = [
		'cut' => [],
		'titles' => [],
		'colors' => [],
		'age_gender' => [],
	];

	protected $categories = [];
	protected $searchTexts = [
		176 => 'галстук|Галстук',
		179 => 'подтяжки|Подтяжки',
		169 => 'ремен|Ремен',
		2271 => 'плать|Плать',
		1581 => 'юбк|Юбк',
		5624=> 'варежк|Варежк|перчатк|Перчатк|шарф|Шарф',
		5625 => 'шапк|Шапк|шапочк|Шапочк',
	];

	public function __construct($feedPath, $utmMark = '', $utmMarkMobile = '', $addUtmMark = false)
	{
		$this->feedPath = $feedPath;
        $this->utmMark = $utmMark;
        $this->utmMarkMobile = $utmMarkMobile;
        $this->addUtmMark = $addUtmMark;

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

		if (mb_strstr($title, 'Комплект') || mb_strstr($title, 'комплект')) {
			return htmlspecialchars($title);
		}

		if (mb_strstr($title, 'Набор') || mb_strstr($title, 'набор')) {
			return htmlspecialchars($title);
		}

		foreach ($this->titles as $titleVariant) {
			if (mb_strstr($title, $titleVariant) || mb_strstr($title, mb_ucfirst($titleVariant))) {
				return htmlspecialchars(mb_ucfirst($titleVariant));
			}
		}

		$this->messages['cut'][] = 'TITLE NOT CUT: '.$title;

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

		if (preg_match('/[а-яё]/iu', $node->title)) {

        } else {
            $this->messages['titles'][] = 'NOT RUSSIAN TITLE: ID = '.$node->id.', TITLE = '.$node->title;
		    return '';
        }

		$title = $this->getTitle($node->title);

//		echo $node->title." => ".$title."\n";

//		$title = htmlspecialchars($node->title);

//		echo $title."\n";

		// Ищем главное слово-товар в названии для подстановки пола и цвета
		$nounFound = false;
		$nounPosition = -1;
		$nounIndex = -1;
		$searchTitle =  mb_strtoupper($title);
		foreach ($this->nouns as $nounKey => $noun) {
			$nounPosition = mb_strpos($searchTitle, $noun.' ');
			if ($noun && $nounPosition !== false) {
				$nounFound = true;
				$nounIndex = $nounKey;
				//echo $noun."\n";
				break;
			} else {
				$nounPosition = mb_strpos($searchTitle, ''.$noun);
				if ($noun && $nounPosition !== false) {
					$nounFound = true;
					$nounIndex = $nounKey;
					//echo $noun."\n";
					break;
				}
			}
		}

		if (!$nounFound) {
			$this->messages['titles'][] = 'NOUN NOT FOUND: ID = '.$node->id.', TITLE = '.$title;
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

        $currentProductType = array_pop($types);

		$product_type = htmlspecialchars($product_type);

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
				$this->messages['age_gender'][] = 'CATEGORY AGE & GENDER EMPTY:'.$reference['item_group_id'][0];
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

			// насыщаем название предложения цветом
			$currentTitle = $title;
			if($nounFound && $title && !$multipleColor && !mb_strstr($title, 'Комплект') && !mb_strstr($title, 'Набор')) {
				$nounRoot = $this->morphy->getPseudoRoot(mb_strtoupper($standardColor));

				if ($nounRoot[0] != null && mb_strlen($nounRoot[0]) != 1 && $nounRoot[0] != 'ХАКИ') {
					$nounEnding = $nounRoot[0] == 'СИН' ? 1 : 0;

					if (!mb_strstr($searchTitle, ' '.$nounRoot[0])
						&& !mb_strstr($searchTitle, $nounRoot[0].'ЫЕ')
						&& !mb_strstr($searchTitle, $nounRoot[0].'АЯ')) {
						if (count($this->endings[$nounIndex]) > 0) {

							$nounColorRoot = mb_strtolower($nounRoot[0]);

							$currentTitle = trim(mb_substr_replace(
								$currentTitle,
								$nounColorRoot.($nounRoot[0] != mb_strtoupper($standardColor)
									? $this->endings[$nounIndex][$nounEnding]
									: '').' ',
								$nounPosition,
								$nounPosition
							));
						} else {
							$currentTitle = trim(mb_substr_replace(
								$currentTitle,
								($nounPosition === 0 ? $standardColor : mb_strtolower($standardColor)).' ',
								$nounPosition,
								$nounPosition
							));
						}
					} else {
						$this->messages['colors'][] = 'COLOR IN TITLE: '.$searchTitle.' => '.$currentTitle;
					}
				}
			}

			// насыщаем название предложения полом
			if ($title && $nounFound && $genderGroup && $genderGroup != 'unisex'
				&& !mb_strstr($title, 'Комплект') && !mb_strstr($title, 'Набор')) {
				if ($ageGroup && $ageGroup == 'adult' && $this->genders[$nounIndex][$genderGroup] != '') {
					$currentTitle = $this->genders[$nounIndex][$genderGroup].' '.$currentTitle;
				} else {
					if($genderGroup == 'male') {
						$currentTitle .= ' для мальчиков';
					}
					if($genderGroup == 'female') {
						$currentTitle .= ' для девочек';
					}
				}
			}

			$currentTitle = mb_ucfirst($currentTitle);

//			echo $node->title." => ".$title.' => '.$currentTitle."\n";

            $sku = array_shift($skus);

            $currentUtmMark = $this->addUtmMark ? str_replace('{reference_id}', $node->id, $this->utmMark) : '';

            $content .= '<entry>
		<g:id>'.'["'.$reference['item_group_id'][0].'"]'.'</g:id>
		<g:title>'.htmlspecialchars($currentTitle).'</g:title>
		<g:description>'.$description.'</g:description>
		<g:link>'.$reference['link_coloris_https'][0].$currentUtmMark.'</g:link>
		<g:mobile_link>'.$reference['mobile_link'][0].($this->addUtmMark ? $this->utmMarkMobile : '').'</g:mobile_link>
		<g:image_link>'.$reference['image_link_https'][0].'</g:image_link>
		<g:condition>'.$node->condition.'</g:condition>
		<g:availability>'.$sku['availability'][0].'</g:availability>
		<g:price>'.$sku['price'][0].' RUB</g:price>
		<g:sale_price>'.$sku['sale_price'][0].' RUB</g:sale_price>
		<g:product_type>'.$product_type.'</g:product_type>
		<g:brand>'.$node->brand.'</g:brand>
		<g:size_system>'.$node->system_size.'</g:size_system>
		'.$shipping.$age.$gender.$googleProductCategory.$materialTag.'
	</entry>
	';
            $this->skuAmount++;

            break;
		}

		return $content;
	}

	public function getXML() {
		return $this->getHeader().$this->content.$this->getFooter();
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

		foreach ($this->messages['cut'] as $message) {
			echo $message."\n";
		}

		foreach ($this->messages['titles'] as $message) {
			echo $message."\n";
		}

		foreach ($this->messages['colors'] as $message) {
			echo $message."\n";
		}

//		foreach ($this->messages['age_gender'] as $message) {
//			echo $message."\n";
//		}

		echo sprintf("Feed file is parsed: products = %d pcs., skus = %d pcs.\n", $i, $this->skuAmount);
		echo sprintf("Title is not cut = %d pcs.\n", count($this->messages['cut']));
		echo sprintf("Age & Gender not found = %d pcs.\n", count($this->messages['age_gender']));
		echo sprintf("Titles without color & gender = %d pcs.\n", count($this->messages['colors']));

		return $this->messages;
	}

}