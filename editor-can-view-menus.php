<?php

/**
 * Plugin Name: Editor Can Edit Menus
 * Plugin URI: http://joncarlmatthews.com/
 * Description: Gives Editors the capability to view and edit the WordPress Menus section without being able to view any of the other Theme options.
 * Version: 1.0.2
 * Author: Jon Carl Matthews
 * Author URI: http://joncarlmatthews.com/
 *
 * Coded to Zend's coding standards:
 * http://framework.zend.com/manual/en/coding-standard.html
 *
 * File format: UNIX
 * File encoding: UTF-8
 * File indentation: Spaces (4). No tabs
 *
 * Read this: http://www.joelonsoftware.com/articles/fog0000000069.html
 *
 */
 
// Is the current user in the admin section?
if (is_admin()){
    
    // Require pluggable.php for wp_get_current_user() function.
    require_once '../wp-includes/pluggable.php';
 
    // Is the current user an editor?
    if (current_user_can('editor')){
    
        // Fetch the editor's role.
        $role = get_role('editor');
    
        // Add "edit_theme_options" to the editor's role so they can edit theme
        // stuff.
        $role->add_cap('edit_theme_options');

        // Create an array of edit_theme_options sections that we don't want
        // the editor to view. This needs to be more intelligent, as editors
        // with genuine access to the following URIs will be denied access if 
        // this Plugin in installed and enabled. I will fix this in version 2.0.
        $denyUris = array('/wp-admin/themes.php',
                            '/wp-admin/widgets.php');
        
        // ...is the editor trying to view one of these sections?
        if ( (in_array($_SERVER['REQUEST_URI'], $denyUris))
                || (in_array($_SERVER['SCRIPT_NAME'], $denyUris)) ){
            header('Location: /wp-admin/nav-menus.php');
            return;
        }
        
        /**
         * The addMenuMenu function removes the menu page and adds the custom
         * Menus option.
         *
         * @return void
         */
        function addMenuMenu()
        {
            // Remove the "Appearance" top level menu.
            remove_menu_page('themes.php');
            
            // Add in bespoke menu to link to directly to the Menus page.
            add_menu_page('Menus', 
                            'Menus', 
                            'edit_theme_options', 
                            'nav-menus.php');
        }
        
        // add_action filter. 88 == :)
        add_action('admin_menu', 'addMenuMenu', 88);
        
    }
}