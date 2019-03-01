<?php

namespace NikolayS93\CleanAdminMenu;

// Exit if accessed directly
if( !defined('ABSPATH') ) {
	exit;
}

/**
 * @param $form
 * @param $page Wbcr_FactoryPages410_ImpressiveThemplate
 * @return mixed
 */
function nikcam_additionally_form_options($form, $page)
{
	$options = &$form[0]['items'];

	$options[] = array(
		'type' => 'html',
		'html' => '<div class="wbcr-clearfy-group-header">' . '<strong>' . __('Main admin menu', 'clean-admin-menu') . '</strong>' . '<p>' . __('In this group of settings, you can manage the main menu.', 'clean-admin-menu') . '</p>' . '</div>'
	);

	$options[] = array(
		'type' => 'dropdown',
		'name' => 'clean_admin_menu',
		'way' => 'buttons',
		'title' => __('Clean admin menu', 'clean-admin-menu'),
		'data' => array(
			array('enable', __('Default', 'clean-admin-menu')),
			array('for_all_users', __('For all users', 'clean-admin-menu')),
			array(
				'for_all_users_except_administrator',
				__('For all users except administrator', 'clean-admin-menu')
			)
		),
		'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
		'hint' => __('In some cases, you need to disable unused menu items. You can hide this.', 'clean-admin-menu'),
		'default' => 'enable',
	);

	$options[] = array(
		'type' => 'checkbox',
		'way' => 'buttons',
		'name' => 'clean_admin_menu_value',
		'title' => __('Show menu tools', 'clean-admin-menu'),
		'default' => false
	);

	return $form;
}

add_filter('wbcr_clr_additionally_form_options', __NAMESPACE__ . '\nikcam_additionally_form_options', 10, 2);
