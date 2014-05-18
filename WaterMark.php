<?php
/**
 * Created by PhpStorm.
 * User: sergKs
 * Date: 08.04.14
 * Time: 19:12
 */

namespace app\components;


use yii\base\Exception;

/**
 * Добавление подписи для изображений
 * формат штампа .png
 * формат изображений .jpeg, .png
 *
 * Пример:
 * $w = new WaterMark('stamp.png');
 * $w->setPosition(WaterMark::POS_RIGHT_DOWN);
 * $path = $w->setStamp('image.png', true);
 *
 * Class WaterMark
 * @package app\components
 */
class WaterMark
{
	/**
	 * константы положения штампа
	 */
	const POS_LEFT_UP = 0;
	const POS_LEFT_DOWN = 1;
	const POS_RIGHT_UP = 2;
	const POS_RIGHT_DOWN = 3;


	/**
	 * штамп
	 * @var null|resource
	 */
	private $stamp = null;

	/**
	 * позиция для штампа
	 * по умолчанию правый нижний угол
	 * @var int
	 */
	private $position = self::POS_RIGHT_DOWN;

	/**
	 * координата x
	 * @var int
	 */
	private $posX = 0;

	/**
	 * координата y
	 * @var int
	 */
	private $posY = 0;


	/**
	 * конструктор
	 * @param string $stamp путь до штампа
	 * @throws \yii\base\Exception
	 */
	public function __construct($stamp)
	{
		if (file_exists($stamp))
			$this->stamp = imagecreatefrompng($stamp);
		else {
			throw new Exception('File not found');
		}
	}

	/**
	 * добавляет штам к изображению
	 * @param string $image путь до изображения
	 * @param bool $flag =false удалять изображение до подписи
	 * @return int|string путь до нового файла
	 * @throws \yii\base\Exception
	 */
	public function setStamp($image, $flag = false)
	{
		if (file_exists($image)) {
			$type = exif_imagetype($image);
			if ($type == IMAGETYPE_JPEG)
				$img = imagecreatefromjpeg($image);
			else if ($type == IMAGETYPE_PNG)
				$img = imagecreatefrompng($image);
			else {
				throw new Exception('Invalid type of file');
			}

			$sx = imagesx($this->stamp);
			$sy = imagesy($this->stamp);
			$this->translatePosition(imagesx($img), imagesy($img), $sx, $sy);

			imagecopy(
				$img, $this->stamp,
				imagesx($img) - $sx - $this->posX,
				imagesy($img) - $sy - $this->posY,
				0,
				0,
				imagesx($this->stamp),
				imagesy($this->stamp)
			);

			$filename = dirname($image) . '/' . time();
			if ($type == IMAGETYPE_JPEG) {
				$filename .= '.jpg';
				imagejpeg($img, $filename);
			} else {
				$filename .= '.png';
				imagepng($img, $filename);
			}

			imagedestroy($img);
			if ($flag)
				unlink($image);

			return $filename;

		} else {
			throw new Exception('File not found');
		}
	}

	/**
	 * устанавливает позицию наложения штампа
	 * @param int $position
	 */
	public function setPosition($position)
	{
		$this->position = $position;
	}

	/**
	 * получает текущую позицию наложения штампа
	 * @return int
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * установка позиции наложени штампа для класса
	 * @param $width
	 * @param $height
	 * @param $sx
	 * @param $sy
	 * @return bool
	 */
	private function translatePosition($width, $height, $sx, $sy)
	{
		switch ($this->position) {
			case self::POS_RIGHT_DOWN :
			{
				$this->posX = 0;
				$this->posY = 0;
				break;
			}
			case self::POS_RIGHT_UP :
			{
				$this->posX = 0;
				$this->posY = $height - $sx;
				break;
			}
			case self::POS_LEFT_UP :
			{
				$this->posX = $width - $sx;
				$this->posY = $height - $sy;
				break;
			}
			case self::POS_LEFT_DOWN :
			{
				$this->posX = $width - $sx;
				$this->posY = 0;
				break;
			}
			default :
				return false;
		}

		return true;
	}
} 