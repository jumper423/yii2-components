<?php
/**
 * Created by PhpStorm.
 * User: sergKs
 * Date: 06.04.14
 * Time: 13:09
 */

namespace app\components;

/**
 * работа с изображениями тега img
 *
 * Пример:
 * $html = '<html><body><img src="image.png"></body></html>';
 * $imgs = new Imgs($html);
 * $images = $imgs->getImages();
 * $count = $imgs->delete();
 *
 * Class Imgs
 * @package app\components
 */
class Imgs
{
	/**
	 * @var string строка с HTML
	 */
	private $string = '';


	/**
	 * @param null $string строка c HTML
	 */
	public function __construct($string = null)
	{
		if (isset($string)) {
			$this->string = $string;
		}
	}

	/**
	 * @param $string string установка строки HTML
	 */
	public function setString($string)
	{
		$this->string = $string;
	}

	/**
	 * получение строки HTML
	 * @return string
	 */
	public function getString()
	{
		return $this->string;
	}

	/**
	 * получение атрибута src у вех тегов img
	 * @return array список атрибутов src
	 */
	public function getImages()
	{
		$rez = [];
		preg_match_all('/<img[^>]+src="?\'?([^"\']+)"?\'?[^>]*>/i', $this->string, $items);
		if (isset($items[1])) {
			foreach ($items[1] as $item) {
				$rez[] = $item;
			}
		}
		return $rez;
	}

	/**
	 * удаление всех изображений по атрибуту src
	 * @return int количество удалённых изображений
	 */
	public function delete()
	{
		preg_match_all('/<img[^>]+src="?\'?([^"\']+)"?\'?[^>]*>/i', $this->string, $items);
		if (isset($items[1])) {
			foreach ($items[1] as $item) {
				if ($item[0] == '')
					$path = substr_replace($item, '', 0, 1);
				else
					$path = $item;
				if (file_exists($path))
					unlink($path);
			}
			return count($items[1]);
		} else {
			return 0;
		}
	}

} 