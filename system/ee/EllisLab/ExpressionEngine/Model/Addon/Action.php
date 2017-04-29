<?php

namespace EllisLab\ExpressionEngine\Model\Addon;

use EllisLab\ExpressionEngine\Service\Model\Model;

/**
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2017, EllisLab, Inc. (https://ellislab.com)
 * @license   https://expressionengine.com/license
 */

// ------------------------------------------------------------------------

/**
 * ExpressionEngine Action Model
 *
 * @package		ExpressionEngine
 * @subpackage	Addon
 * @category	Model
 * @author		EllisLab Dev Team
 * @link		https://ellislab.com
 */
class Action extends Model {

	protected static $_primary_key = 'action_id';
	protected static $_table_name = 'actions';

	protected static $_validation_rules = array(
		'csrf_exempt' => 'enum[0,1]'
	);

	protected $action_id;
	protected $class;
	protected $method;
	protected $csrf_exempt;
}

// EOF
