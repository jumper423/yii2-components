<?php
/**
 * Created by PhpStorm.
 * User: sergKs
 * Date: 06.04.14
 * Time: 14:22
 */

namespace app\components;

/**
 * валидация файлов
 *
 * $file = new File('model', 'field');
 * $file->setTypes(['image/jpeg', 'image.png']);
 * $file->setSize(1024);
 * if ($file->validate()) {
 *        //...
 * } else {
 *    echo $file->getErrorString();
 *      //...
 * }
 *
 * Class File
 * @package app\components
 */
class File
{
	/**
	 * константы
	 */
	const ERROR_MODEL_NAME = 0;
	const ERROR_FIELD = 1;
	const ERROR_TYPE = 2;
	const ERROR_SIZE = 3;

	/**
	 * Mime-типы файла
	 * @var array
	 */
	private $types = [];

	/**
	 * имя модели
	 * @var string
	 */
	private $modelName = '';

	/**
	 * имя поля
	 * @var string
	 */
	private $field = '';

	/**
	 * размер файла
	 * @var int
	 */
	private $size = 0;

	/**
	 * ошибка
	 * @var null
	 */
	private $error = '';


	/**
	 * конструктор
	 * @param $modelName string имя модели
	 * @param $filed string имя поля
	 */
	public function __construct($modelName, $filed)
	{
		$this->modelName = $modelName;
		$this->field = $filed;
	}

	/**
	 * валидация
	 * @return bool
	 */
	public function validate()
	{
		if (isset($_FILES[$this->modelName])) {
			if (isset($_FILES[$this->modelName]['name'][$this->field])) {
				//проверка типов
				foreach ($this->types as $type) {
					if ($_FILES[$this->modelName]['type'][$this->field] == $type) {
						//проверка размера
						if ($_FILES[$this->modelName]['size'][$this->field] <= $this->size) {
							return true;
						} else {
							$this->error = self::ERROR_SIZE;
							return false;
						}
					}
				}
				$this->error = self::ERROR_TYPE;
				return false;
			} else {
				$this->error = self::ERROR_FIELD;
				return false;
			}
		} else {
			$this->error = self::ERROR_MODEL_NAME;
			return false;
		}
	}

	/**
	 * получение ошибки
	 * @return int
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * полчение ошибки в виде строки
	 * @return string
	 */
	public function getErrorString()
	{
		switch ($this->error) {
			case 0 :
				return 'Неккоректное имя модели';
				break;
			case 1 :
				return 'Неккоректное имя поля';
				break;
			case 2 :
				return 'Неправильный формат файла';
				break;
			case 3 :
				return 'Неверный размер файла';
				break;
			default :
				return 'Ошибок нет';
		}
	}

	/**
	 * установка типов
	 * @param $types array
	 */
	public function setTypes($types)
	{
		$this->types = $types;
	}

	/**
	 * установка имени модели
	 * @param $modelName string
	 */
	public function setModelName($modelName)
	{
		$this->modelName = $modelName;
	}

	/**
	 * установка имени поля
	 * @param $field string
	 */
	public function setField($field)
	{
		$this->field = $field;
	}

	/**
	 * установка максимального размера файла
	 * @param $size int
	 */
	public function setSize($size)
	{
		$this->size = $size;
	}

	/**
	 * получение типов
	 * @return array
	 */
	public function getTypes()
	{
		return $this->types;
	}

	/**
	 * получение имени модели
	 * @return string
	 */
	public function getModelName()
	{
		return $this->modelName;
	}

	/**
	 * получение имени поля
	 * @return string
	 */
	public function getField()
	{
		return $this->field;
	}

	/**
	 * получение размера файла
	 * @return int
	 */
	public function getSize()
	{
		return $this->size;
	}
} 