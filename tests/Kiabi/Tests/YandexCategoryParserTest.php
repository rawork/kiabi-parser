<?php


namespace Kiabi\Tests;

use Kiabi\YandexCategoryParser;
use PHPUnit\Framework\TestCase;

class YandexCategoryParserTest extends TestCase
{
	public function testPushAndPop()
	{
		$stack = [];
		$this->assertEquals(0, count($stack));

		array_push($stack, 'foo');
		$this->assertEquals('foo', $stack[count($stack)-1]);
		$this->assertEquals(1, count($stack));

		$this->assertEquals('foo', array_pop($stack));
		$this->assertEquals(0, count($stack));
	}

	public function testParse() {

		$xml = '';

		$key1 = md5('1');
		$key2 = md5('1|2');
		$key3 = md5('1|2|3');
		$key4 = md5('1|2|4');

		$categories = [
			$key1 => ["id" => 1, "title" => "1", "parent_id" => 0, "type" => "1"],
			$key2 => ["id" => 2, "title" => "2", "parent_id" => 1, "type" => "1|2"],
			$key3 => ["id" => 3, "title" => "3", "parent_id" => 2, "type" => "1|2|3"],
			$key4 => ["id" => 4, "title" => "4", "parent_id" => 2, "type" => "1|2|4"]
		];

		$parser = new YandexCategoryParser(__DIR__.'/categories.test.xml', $categories);

		$parser->parse();

		$result = $parser->getCategories();

		$this->assertEquals(8, count($result));
	}
}