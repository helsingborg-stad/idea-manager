<?php

namespace IdeaManager\Helper;

class File
{
	public static function cleanFileName($string)
	{
		$pathParts = pathinfo($string);

		$name = preg_replace('/([A-Za-z0-9]+-)/i', '', $pathParts['filename'], 1);
		$name = str_replace('_', ' ', $name);
		$name = str_replace('-', ' ', $name);

		return $name;
	}
}
