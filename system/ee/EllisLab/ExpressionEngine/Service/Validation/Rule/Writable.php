<?php

namespace EllisLab\ExpressionEngine\Service\Validation\Rule;

use EllisLab\ExpressionEngine\Library\Filesystem\Filesystem;
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
 * ExpressionEngine Writable Validation Rule
 *
 * @package		ExpressionEngine
 * @subpackage	Validation\Rule
 * @category	Service
 * @author		EllisLab Dev Team
 * @link		https://ellislab.com
 */
class Writable extends ValidationRule {

	protected $fs;
	protected $all_values = array();

	public function validate($key, $value)
	{
		return $this->getFilesystem()->isWritable(parse_config_variables($value, $this->all_values));
	}

	public function getLanguageKey()
	{
		return 'invalid_path';
	}

	protected function getFilesystem()
	{
		if ( ! isset($this->fs))
		{
			$this->fs = new Filesystem();
		}

		return $this->fs;
	}

	public function setAllValues(array $values)
	{
		$this->all_values = $values;
	}
}

// EOF
