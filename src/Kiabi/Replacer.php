<?php

namespace Kiabi;


class Replacer
{
	public static $colors = [
		'бежевый', 'белый', 'бирюзовый', 'бордовый',
		'голубой', 'желтый', 'зеленый', 'золотистый',
		'коричневый', 'красный', 'оливковый', 'оранжевый',
		'разноцветный', 'розовый', 'рыжий', 'салатовый', 'светло-розовый',
		'серебристый', 'серый',	'синий', 'сиреневый',
		'темно-зеленый', 'темно-коричневый', 'темно-серый', 'темно-синий',
		'фиолетовый', 'хаки', 'черный', 'ярко-розовый'
	];
	protected $rules = [];
	protected $merchant;

	public function __construct(array $rules = [], $merchant = 'yandex')
	{
		$this->rules = $rules;
		$this->merchant = $merchant;
	}

	public function analize($text)
	{
		if (!in_array(trim($text), self::$colors)) {
			return true;
		}

		return false;
	}

	public function replace($text)
	{
		if ($this->analize($text)) {
			if (array_key_exists(md5($text), $this->rules) && '' != $this->rules[md5($text)][$this->merchant]) {
				return $this->rules[md5($text)][$this->merchant];
			}
		}

		return $text;
	}

	public function getColors()
	{
		return $this->colors;
	}
}