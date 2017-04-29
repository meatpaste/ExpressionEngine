<?php

namespace EllisLab\Addons\Spam\Library\Vectorizers;

use \EllisLab\Addons\spam\Library\Vectorizer;

/**
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2017, EllisLab, Inc. (https://ellislab.com)
 * @license   https://expressionengine.com/license
 */

// ------------------------------------------------------------------------

/**
 * ExpressionEngine Spam Module
 *
 * @package		ExpressionEngine
 * @subpackage	Modules
 * @category	Modules
 * @author		EllisLab Dev Team
 * @link		https://ellislab.com
 */

class Spaces implements Vectorizer {

	/**
	 * Calculates the ratio of whitespace to non-whitespace
	 *
	 * @param string $source The source text
	 * @access public
	 * @return float The calculated ratio
	 */
	public function vectorize($source)
	{
		$whitespace = preg_match_all('/\s/u', $source);
		$characters  = mb_strlen($source);
		if ($characters !== 0)
		{
			$ratio = $whitespace / $characters;
		}
		else
		{
			$ratio = 1;
		}
		return $ratio;
	}

}

// EOF
