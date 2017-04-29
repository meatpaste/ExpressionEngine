<?php

namespace EllisLab\ExpressionEngine\Service\Model\Column\Scalar;

use EllisLab\ExpressionEngine\Service\Model\Column\StaticType;

/**
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2017, EllisLab, Inc. (https://ellislab.com)
 * @license   https://expressionengine.com/license
 */

// ------------------------------------------------------------------------

/**
 * ExpressionEngine Model Integer Typed Column
 *
 * @package		ExpressionEngine
 * @subpackage	Model
 * @category	Service
 * @author		EllisLab Dev Team
 * @link		https://ellislab.com
 */
class Integer extends StaticType {

	/**
	 * Called when the user gets the column
	 */
	public static function get($data)
	{
		return static::intval($data);
	}

	/**
	 * Called when the user sets the column
	 */
	public static function set($data)
	{
		return $data;
	}

	/**
	 * Called when the column is fetched from db
	 */
	public static function load($db_data)
	{
		return static::intval($db_data);
	}

	/**
	 * Called before the column is written to the db
	 */
	public static function store($data)
	{
		return static::intval($data);
	}


	private static function intval($data)
	{
		return is_scalar($data) ? (int) $data : 0;
	}
}

// EOF
