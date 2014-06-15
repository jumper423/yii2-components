<?php
/**
 * Created by PhpStorm.
 * User: sergKs
 * Date: 10.05.14
 * Time: 18:19
 */

/**
 * Отображение произвольного списка данных
 *
 * Пример:
 *
 * RenderWidget::widget([
 *     'list' => $list,
 *     'fields' => [
 *        'id',
 *        'name'
 *     ],
 *     'template' => '<li><h2>{id}</h2><p>{name}</p></li>'
 * ]);
 *
 * Class RenderWidget
 */
class RenderWidget
{
	/**
	 * список элементов
	 * @var array
	 */
	public static $list = [];

	/**
	 * список полей элемента
	 * @var array
	 */
	public static $fields = [];

	/**
	 * тег-контейнер
	 * @var string
	 */
	public static $tag = 'ul';

	/**
	 * шаблон отображения
	 * @var string
	 */
	public static $template = '<li></li>';

	/**
	 * класс-css
	 * @var string
	 */
	public static $class = 'list';


	/**
	 * инициализация параметров
	 * @param array $options список параметров
	 */
	protected static function init($options = [])
	{
		if (isset($options['list']))
			self::$list = $options['list'];
		if (isset($options['fields']))
			self::$fields = $options['fields'];
		if (isset($options['tag']))
			self::$tag = $options['tag'];
		if (isset($options['template']))
			self::$template = $options['template'];
		if (isset($options['class']))
			self::$class = $options['class'];
	}

	/**
	 * отображение элементов
	 * @param array $options список параметров
	 */
	public static function widget($options = [])
	{
		self::init($options);
		echo '<', self::$tag, ' class="', self::$class, '">', PHP_EOL;
		try {
			foreach (self::$list as $item) {
				echo self::renderItem($item), PHP_EOL;
			}
		} catch (Exception $e) {
			print_r($e);
		}
		echo '</', self::$tag, '>', PHP_EOL;
	}

	/**
	 * отображение одного элемента
	 * @param array $item элемент
	 * @return string созданный элемент по шаблону
	 */
	protected static function renderItem($item = [])
	{
		$arr = [];
		for ($i = 0; $i < count(self::$fields); $i++) {
			$arr['{' . self::$fields[$i] . '}'] = $item[self::$fields[$i]];
		}
		return strtr(self::$template, $arr);
	}
} 