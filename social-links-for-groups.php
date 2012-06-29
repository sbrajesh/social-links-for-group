<?php
/**
 * Plugin Name: Social Links for Group
 * Author: Brajesh Singh
 * Version:1.0
 * Description: Just an example plugin to allow social links (I have left the front end display to theme author for now)
 * 
 */

//Just a small plugin to show how to associate social links on group

add_action('bp_init','bp_social_links_for_group_load',0);

function bp_social_links_for_group_load(){
    if(bp_is_active('groups'))
       include_once(plugin_dir_path(__FILE__).'group-ext.php');
}

?>