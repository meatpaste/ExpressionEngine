<?php

namespace EllisLab\ExpressionEngine\Service\Validation\Rule;

use EllisLab\ExpressionEngine\Service\Validation\ValidationRule;

/**
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2017, EllisLab, Inc. (https://ellislab.com)
 * @license   https://expressionengine.com/license
 */

// ------------------------------------------------------------------------

/**
 * ExpressionEngine Valid Base 64 Value Validation Rule
 *
 * @package		ExpressionEngine
 * @subpackage	Validation\Rule
 * @category	Service
 * @author		EllisLab Dev Team
 * @link		https://ellislab.com
 */
class ValidBase64 extends ValidationRule {

	public function validate($key, $value)
	{
		return (bool) preg_match('/^[a-zA-Z0-9\/\+=]+$/', $value);
	}

	public function getLanguageKey()
	{
		return 'valid_base64';
	}
}

// EOF
