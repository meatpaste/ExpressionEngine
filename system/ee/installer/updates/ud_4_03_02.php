<?php
/**
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2017, EllisLab, Inc. (https://ellislab.com)
 * @license   https://expressionengine.com/license
 */

namespace EllisLab\ExpressionEngine\Updater\Version_4_3_2;

/**
 * Update
 */
class Updater {

	var $version_suffix = '';

	/**
	 * Do Update
	 *
	 * @return TRUE
	 */
	public function do_update()
	{
		$steps = new \ProgressIterator(
			array(
				'memberDataTableCleanup',
			)
		);

		foreach ($steps as $k => $v)
		{
			$this->$v();
		}

		return TRUE;
	}

	/**
	* Fields created in v2 didn't have a m_field_ft_x column added,
	* so we need to add it for those legacy member fields
	*/
	private function memberDataTableCleanup()
	{
		$new_column = array();
		$id_ids = array();
		$ft_ids = array();

		$member_data_columns = ee()->db->list_fields('member_data');

		foreach ($member_data_columns as $column)
		{
			if (strncmp('m_field_id_', $column, 11) == 0)
			{
				$id_ids[] = substr($column, 11);
			}
			elseif (strncmp('m_field_ft_', $column, 11) == 0)
			{
				$ft_ids[] = substr($column, 11);
			}
		}

		$make = array_diff($id_ids, $ft_ids);

		foreach ($make as $id)
		{
			$new_column['m_field_ft_'.$id] = array('type' => 'tinytext');

			ee()->smartforge->add_column('member_data', $new_column, 'm_field_id_'.$id);
		}
	}

}

// EOF
