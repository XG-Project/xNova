<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class bbCode
{
	public function bbCode ( $string )
	{
		$pattern = array(
		    '/\\n/',
		    '/\\r/',
		    '/\[list\](.*?)\[\/list\]/ise',
		    '/\[b\](.*?)\[\/b\]/is',
		    '/\[strong\](.*?)\[\/strong\]/is',
		    '/\[i\](.*?)\[\/i\]/is',
		    '/\[u\](.*?)\[\/u\]/is',
		    '/\[s\](.*?)\[\/s\]/is',
		    '/\[del\](.*?)\[\/del\]/is',
		    '/\[url=(.*?)\](.*?)\[\/url\]/ise',
		    '/\[email=(.*?)\](.*?)\[\/email\]/is',
		    '/\[img](.*?)\[\/img\]/ise',
		    '/\[color=(.*?)\](.*?)\[\/color\]/is',
		    '/\[quote\](.*?)\[\/quote\]/ise',
		    '/\[code\](.*?)\[\/code\]/ise',
		    '/\[font=(.*?)\](.*?)\[\/font\]/ise',
		    '/\[bg=(.*?)\](.*?)\[\/bg\]/ise',
		    '/\[size=(.*?)\](.*?)\[\/size\]/ise'
		);

		$replace = array(
		    '<br/>',
		    '',
		    '$this->sList(\'\\1\')',
		    '<b>\1</b>',
		    '<strong>\1</strong>',
		    '<i>\1</i>',
		    '<span style="text-decoration: underline;">\1</span>',
		    '<span style="text-decoration: line-through;">\1</span>',
		    '<span style="text-decoration: line-through;">\1</span>',
		    '$this->urlfix(\'\\1\',\'\\2\')',
		    '<a href="mailto:\1" title="\1">\2</a>',
		    '$this->imagefix(\'\\1\')',
		    '<span style="color: \1;">\2</span>',
		    '$this->sQuote(\'\1\')',
		    '$this->sCode(\'\1\')',
		    '$this->fontfix(\'\\1\',\'\\2\')',
		    '$this->bgfix(\'\\1\',\'\\2\')',
		    '$this->sizefix(\'\\1\',\'\\2\')'
		);

		return preg_replace($pattern, $replace, nl2br(htmlspecialchars(stripslashes($string))));
	}

	private function sCode($string)
	{
		$pattern =  '/\<img src=\\\"(.*?)img\/smilies\/(.*?).png\\\" alt=\\\"(.*?)\\\" \/>/s';
		$string = preg_replace($pattern, '\3', $string);
		return '<pre style="color: #DDDD00; background-color:gray ">' . trim($string) . '</pre>';
	}

	private function sQuote($string)
	{
		$pattern =  '/\<img src=\\\"(.*?)img\/smilies\/(.*?).png\\\" alt=\\\"(.*?)\\\" \/>/s';
		$string = preg_replace($pattern, '\3', $string);
		return '<blockquote><p style="color: #000000; font-size: 10pt; background-color:55AACC; font-family: Arial">' . trim($string) . '</p></blockquote>';
	}

	private function sList($string)
	{
		$tmp = explode('[*]', stripslashes($string));
		$out = NULL;
		foreach($tmp as $list) {
			if(strlen(str_replace('', '', $list)) > 0) {
				$out .= '<li>' . trim($list) . '</li>';
			}
		}
		return '<ul>' . $out . '</ul>';
	}

	private function imagefix($img)
	{
		if(substr($img, 0, 7) != 'http://')
		{
			$img = './images/' . $img;
		}
		return '<img src="' . $img . '" alt="' . $img . '" title="' . $img . '" />';
	}

	private function urlfix($url, $title)
	{
		$title = stripslashes($title);
		$url   = trim($url);
		return (substr ($url, 0, 5) == 'data:' || substr ($url, 0, 5) ==  'file:' || substr ($url, 0, 11) == 'javascript:' || substr  ($url, 0, 4) == 'jar:' || substr ($url, 0, 1) == '#') ? '' : '<a  href="' . $url . '" title="'.htmlspecialchars($title,  ENT_QUOTES).'">'.htmlspecialchars($title, ENT_QUOTES).'</a>';
	}

	private function fontfix($font, $title)
	{
		$title = stripslashes($title);
		return '<span style="font-family:' . $font . '">' . $title . '</span>';
	}

	private function bgfix($bg, $title)
	{
		$title = stripslashes($title);
		return '<span style="background-color:' . $bg . '">' . $title . '</span>';
	}

	private function sizefix($size, $text)
	{
		$title = stripslashes($text);
		return '<span style="font-size:' . $size . 'px">' . $title . '</span>';
	}
}
?>