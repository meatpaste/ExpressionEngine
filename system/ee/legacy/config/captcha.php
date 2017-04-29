<?php
/**
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2017, EllisLab, Inc. (https://ellislab.com)
 * @license   https://expressionengine.com/license
 */

// ------------------------------------------------------------------------

/**
 * ExpressionEngine CAPTCHA Word Dictionary
 *
 * @package		ExpressionEngine
 * @subpackage	Config
 * @category	Config
 * @author		EllisLab Dev Team
 * @link		https://ellislab.com
 */

ee()->load->library('logger');
ee()->logger->deprecated('3.4.0', 'ee()->config->loadFile("captcha") to load this config file', TRUE, 604800);

$words = ee()->config->loadFile('captcha');

// EOF
