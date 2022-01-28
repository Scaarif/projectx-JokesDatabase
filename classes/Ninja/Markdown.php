<?php 
namespace Ninja;

class Markdown
{
	private $string;

	public function __construct($markdown) {
		$this->string = $markdown;
	}

	public function toHtml() {
		//convert $this->string to html
		$text = htmlspecialchars($this->string, ENT_QUOTES, 'UTF-8');
		//strong (bold)
		$text = preg_replace('/__(.+?)__/s', '<strong>$1</strong>', $text);
		$text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text);

		//emphasis (italics)
		$text = preg_replace('/_([^_]+)_/', '<em>$1</em>', $text);
		$text = preg_replace('/\*([^\*]+)\*/', '<em>$1</em>', $text);
/*
		//convert Windows (\r\n) to unix (\n)
		$text = preg_replace('/\r\n/', "\n", $text);
		//Convert Macintosh (\r) to unix (\n)
		$text = preg_replace('/\r/', "\n", $text);

		//Paragraphs
		$text = '<p>'. preg_replace('/\n\n/', '<p></p>', $text).'</p>';
		//Line breaks
		$text = preg_replace('/\n/', '<br>', $text);
*/
		//A simpler way to achieve the same effect with paragraphs and line breaks...
		//Use PHP's str_replace fn - works a lot like preg_replace, only it searches for strings rather than regular expressions! **Its much more efficient. Should always be the first choice if an option.

		//convert Windows (\r\n) to unix (\n)
		$text = str_replace("\r\n", "\n", $text);
		//Convert Macintosh (\r) to unix (\n)
		$text = str_replace("\r", "\n", $text);

/*		//Paragraphs
		$text = '<p>'. str_replace("\n\n", '<p></p>', $text).'</p>';
		//Line breaks
		$text = str_replace("\n", '<br>', $text);

		//hyperlinks [linked text](link URL)
		/*$text = preg_replace('/\[([^\]]+)]\(([-a-z0-9._~:\/?#@!$&\'()*+,;=%]+)\)/i', '<a 
		href="$2">$1</a>', $text); */

		$text = preg_replace(
		'/\[([^\]]+)]\(([-a-z0-9._~:\/?#@!$&\'()*+,;=%]+)\)/i',
		'<a href="$2">$1</a>', $text);


		return $text;
	}
}