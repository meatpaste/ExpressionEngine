<?php
namespace EllisLab\ExpressionEngine\Service\Sidebar;

use EllisLab\ExpressionEngine\Service\View\ViewFactory;

/**
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2017, EllisLab, Inc. (https://ellislab.com)
 * @license   https://expressionengine.com/license
 */

// ------------------------------------------------------------------------

/**
 * ExpressionEngine BasicItem Class
 *
 * @package		ExpressionEngine
 * @category	Service
 * @author		EllisLab Dev Team
 * @link		https://ellislab.com
 */
class BasicItem extends ListItem {

	/**
	 * @var string $rel An <a> tag's rel attribute
	 */
	protected $rel;

	/**
	 * Marks the item as a delete action
	 *
	 * @param string $modal_name The name of the modal this delete action will trigger
	 * @return self This returns a reference to itself
	 */
	public function asDeleteAction($modal_name = '')
	{
		$this->addClass('remove ');
		$this->rel = $modal_name;

		return $this;
	}

	/**
	 * Renders this item. This should not be called directly. Instead use
	 * the Sidebar's render method.
	 *
	 * @see Sidebar::render
	 * @param ViewFactory $view A ViewFactory object to use with rendering
	 * @return string The rendered HTML of the item
	 */
	public function render(ViewFactory $view)
	{
		$class = $this->getClass();

		if ($class)
		{
			$class = ' class="' . $class . '"';
		}

		$attrs = '';

		if ($this->url_is_external)
		{
			$attrs .= ' rel="external"';
		}

		if ($this->rel)
		{
			$attrs .= ' class="m-link" rel="' . $this->rel . '"';
		}

		$vars = array(
			'text' => $this->text,
			'url' => $this->url,
			'attrs' => $attrs,
			'class' => $class
		);

		return $view->make('_shared/sidebar/basic_item')->render($vars);
	}

}

// EOF