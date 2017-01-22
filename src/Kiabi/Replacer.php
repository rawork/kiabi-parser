<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 19/01/17
 * Time: 18:22
 */

namespace Kiabi;


class Replacer
{
	protected $colors;
	protected $rules = [];

	public function __construct()
	{
		$this->colors = [
			'бежевый', 'белый', 'бирюзовый', 'бордовый',
 			'голубой', 'желтый', 'зеленый', 'золотистый',
			'коричневый', 'красный', 'оливковый', 'оранжевый',
			'разноцветный', 'розовый', 'рыжий', 'салатовый', 'светло-розовый',
			'серебристый', 'серый',	'синий', 'сиреневый',
			'темно-зеленый', 'темно-коричневый', 'темно-серый', 'темно-синий',
			'фиолетовый', 'хаки', 'черный', 'ярко-розовый'
		];
	}

	public function analize($text)
	{
		if (!in_array(trim($text), $this->colors)) {
			return true;
		}

		return false;
	}

	public function replace($text)
	{
		if ($this->analize($text)) {
			if (array_key_exists(md5($text), $this->rules)) {
				return $this->rules[md5($text)];
			}
		}

		return $text;
	}
}