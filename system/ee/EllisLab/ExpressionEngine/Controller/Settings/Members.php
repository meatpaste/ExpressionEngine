<?php

namespace EllisLab\ExpressionEngine\Controller\Settings;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use CP_Controller;

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2003 - 2014, EllisLab, Inc.
 * @license		https://ellislab.com/expressionengine/user-guide/license.html
 * @link		http://ellislab.com
 * @since		Version 3.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * ExpressionEngine CP Members Settings Class
 *
 * @package		ExpressionEngine
 * @subpackage	Control Panel
 * @category	Control Panel
 * @author		EllisLab Dev Team
 * @link		http://ellislab.com
 */
class Members extends Settings {

	/**
	 * General Settings
	 */
	public function index()
	{
		$groups = ee('Model')->get('MemberGroup')->order('group_title', 'asc')->all();

		$member_groups = array();
		foreach ($groups as $group)
		{
			$member_groups[$group->group_id] = $group->group_title;
		}

		ee()->load->model('member_model');
		$themes = $this->member_model->get_theme_list(PATH_MBR_THEMES);

		$member_themes = array();
		foreach ($themes as $file => $name)
		{
			$member_themes[$file] = $name;
		}

		$vars['sections'] = array(
			array(
				array(
					'title' => 'allow_member_registration',
					'desc' => 'allow_member_registration_desc',
					'fields' => array(
						'allow_member_registration' => array('type' => 'yes_no')
					)
				),
				array(
					'title' => 'req_mbr_activation',
					'desc' => 'req_mbr_activation_desc',
					'fields' => array(
						'req_mbr_activation' => array(
							'type' => 'select',
							'choices' => array(
								'none' => lang('req_mbr_activation_opt_none'),
								'email' => lang('req_mbr_activation_opt_email'),
								'manual' => lang('req_mbr_activation_opt_manual')
							)
						)
					)
				),
				array(
					'title' => 'require_terms_of_service',
					'desc' => 'require_terms_of_service_desc',
					'fields' => array(
						'require_terms_of_service' => array('type' => 'yes_no')
					)
				),
				array(
					'title' => 'allow_member_localization',
					'desc' => 'allow_member_localization_desc',
					'fields' => array(
						'allow_member_localization' => array('type' => 'yes_no')
					)
				),
				array(
					'title' => 'default_member_group',
					'desc' => 'default_member_group_desc',
					'fields' => array(
						'default_member_group' => array(
							'type' => 'select',
							'choices' => $member_groups
						)
					)
				),
				array(
					'title' => 'member_theme',
					'desc' => 'member_theme_desc',
					'fields' => array(
						'member_theme' => array(
							'type' => 'select',
							'choices' => $member_themes
						)
					)
				)
			),
			'member_listing_settings' => array(
				array(
					'title' => 'memberlist_order_by',
					'desc' => 'memberlist_order_by_desc',
					'fields' => array(
						'memberlist_order_by' => array(
							'type' => 'select',
							'choices' => array(
								'total_posts' => lang('memberlist_order_by_opt_posts'),
								'screen_name' => lang('memberlist_order_by_opt_screenname'),
								'total_entries' => lang('memberlist_order_by_opt_entries'),
								'join_date' => lang('memberlist_order_by_reg_date'),
								'total_comments' => lang('memberlist_order_by_opt_comments')
							)
						)
					)
				),
				array(
					'title' => 'memberlist_sort_order',
					'desc' => 'memberlist_sort_order_desc',
					'fields' => array(
						'memberlist_sort_order' => array(
							'type' => 'select',
							'choices' => array(
								'asc' => lang('memberlist_sort_order_opt_asc'),
								'desc' => lang('memberlist_sort_order_opt_desc')
							)
						)
					)
				),
				array(
					'title' => 'memberlist_row_limit',
					'desc' => 'memberlist_row_limit_desc',
					'fields' => array(
						'memberlist_row_limit' => array(
							'type' => 'select',
							'choices' => array('10' => '10', '20' => '20',
								'30' => '30', '40' => '40', '50' => '50',
								'75' => '75', '100' => '100')
						)
					)
				)
			),
			'registration_notify_settings' => array(
				array(
					'title' => 'new_member_notification',
					'desc' => 'new_member_notification_desc',
					'fields' => array(
						'new_member_notification' => array(
							'type' => 'inline_radio',
							'choices' => array(
								'y' => 'enable',
								'n' => 'disable'
							)
						)
					)
				),
				array(
					'title' => 'mbr_notification_emails',
					'desc' => 'mbr_notification_emails_desc',
					'fields' => array(
						'mbr_notification_emails' => array('type' => 'text')
					)
				)
			)
		);

		$base_url = ee('CP/URL', 'settings/members');

		ee()->form_validation->set_rules(array(
			array(
				'field' => 'mbr_notification_emails',
				'label' => 'lang:mbr_notification_emails',
				'rules' => 'valid_emails'
			)
		));

		ee()->form_validation->validateNonTextInputs($vars['sections']);

		if (AJAX_REQUEST)
		{
			ee()->form_validation->run_ajax();
			exit;
		}
		elseif (ee()->form_validation->run() !== FALSE)
		{
			if ($this->saveSettings($vars['sections']))
			{
				ee()->view->set_message('success', lang('preferences_updated'), lang('preferences_updated_desc'), TRUE);
			}

			ee()->functions->redirect($base_url);
		}
		elseif (ee()->form_validation->errors_exist())
		{
			ee()->view->set_message('issue', lang('settings_save_error'), lang('settings_save_error_desc'));
		}

		ee()->view->base_url = $base_url;
		ee()->view->ajax_validate = TRUE;
		ee()->view->cp_page_title = lang('member_settings');
		ee()->view->save_btn_text = 'btn_save_settings';
		ee()->view->save_btn_text_working = 'btn_saving';

		ee()->cp->render('settings/form', $vars);
	}
}
// END CLASS

/* End of file Members.php */
/* Location: ./system/EllisLab/ExpressionEngine/Controller/Settings/Members.php */