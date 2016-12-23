<?php

namespace Kiabi;

class CategoryParser
{
	protected $content = '';

	public function getHeader()
	{
		$date = date('Y-m-d H:i');

		$content = '<?xml version="1.0" encoding="UTF-8"?>
<categories> 
';

		return $content;
	}

	public function getFooter()
	{
		return '</categories>';
	}


	public function generateItem($id, $name)
	{
		return '	<category id="'.$id.'">'.$name.'</category>
';
	}

	public function getXML() {
		return $this->getHeader().$this->content.$this->getFooter();
	}

	public function parse()
	{
		if (preg_match_all('/\<a.+\<\/a\>/', file_get_contents(MAINPAGE_PATH), $matches)) {
			foreach ($matches[0] as $link) {
				$link = preg_replace('/onclick=\"(.+);return false\"/', '', $link);

				$id = null;
				$name = null;

				if (preg_match('/href=\"(.+)_([0-9]+)\"/', $link, $matches)){
					$id = $matches[2];
				}

				if (preg_match('/\>(.+)\</', $link, $matches)){
					$name = $matches[1];
				}

				if ('#' == $name) {
					continue;
				}

				$this->content .= $this->generateItem($id, $name);
			}
		}
	}

}