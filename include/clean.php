<?php

namespace NikolayS93\CleanAdminMenu;


// $defaults = apply_filters( 'project_settings_activate', array(
//     'menu'     => 'edit-comments.php,users.php,tools.php,',
//     'sub_menu' => 'index.php>index.php,index.php>update-core.php,edit.php?post_type=shop_order>edit.php?post_type=shop_order,edit.php?post_type=shop_order>edit.php?post_type=shop_coupon,edit.php?post_type=shop_order>admin.php?page=wc-reports,options-general.php>options-discussion.php,',
// ) );
// self::load_file_if_exists( $dir_include . '/hooks/hide-menus.php' );
// self::load_file_if_exists( $dir_include . '/hooks/clear-dash.php' );

add_action( 'admin_menu', function() {
    if( isset($_GET['page']) && 'advanced-wbcr_clearfy' === $_GET['page'] ) return;

    $condition = get_option( 'wbcr_clearfy_clean_admin_menu', '' );

    if( !$condition ) return;
    if( 'for_all_users_except_administrator' == $condition && current_user_can( 'activate_plugins' )) return;

    $menus = Utils::get();

    if( isset($menus['menu']) && is_array($menus['menu']) ) {
        foreach ($menus['menu'] as $menu)
        {
            $menu = str_replace("admin.php?page=", "", $menu);

            switch ($menu) {
                case 'edit.php?post_type=shop_order': $menu = 'woocommerce'; break;
            }
            remove_menu_page($menu);
        }
    }

    if( isset($menus['sub_menu']) && is_array($menus['sub_menu']) ) {
        foreach ($menus['sub_menu'] as $submenu)
        {
            $parent = str_replace("admin.php?page=", "", $submenu->parent);
            $obj = str_replace("admin.php?page=", "", $submenu->obj);

            switch ($parent) {
                case 'edit.php?post_type=shop_order': $parent = 'woocommerce'; break;
            }

            remove_submenu_page($parent, $obj);
        }
    }
}, 99 );
