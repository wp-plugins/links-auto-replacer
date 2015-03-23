<?php
/**
 * Links Auto Replacer
 *
 * 
 * Links Auto Replacer is a Wordpress plugin that helps you to replace a specific keywords to links automatically.
 *
 * @package   Links Auto Replacer
 * @author    Waseem Senjer <waseem.senjer@gmail.com>
 * @license   GPL-2.0+
 * @link      http://waseem-senjer.com
 * @copyright 2014 Waseem Senjer
 *
 * @wordpress-plugin
 * Plugin Name:       Links Auto Replacer
 * Plugin URI:        http://waseem-senjer.com/lar/
 * Description:       Auto replace your affiliate links and track them.
 * Version:           1.1.0
 * Author:            Waseem Senjer
 * Author URI:        http://waseem-senjer.com
 * Text Domain:       lar-links-auto-replacer
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/wsenjer/Links-Replacer
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


add_action('init', 'lar_action_init');
function lar_action_init(){
	load_plugin_textdomain('lar-links-auto-replacer', false, basename( dirname( __FILE__ ) ) . '/languages' );

}







register_activation_hook( __FILE__, 'lar_activate' );
function lar_activate() {
			global $wpdb;
			    $sql = '

						CREATE TABLE IF NOT EXISTS `'.$wpdb->prefix.'lar_links` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `keyword` text NOT NULL,
						  `keyword_url` text NOT NULL,
						  `dofollow` int(1) NOT NULL,
						  `open_in` varchar(255) NOT NULL,
						  `cloack` int(1) NOT NULL,
						  `slug` varchar(255) NOT NULL,
						  `created` int(11) NOT NULL,
						  `updated` int(11) NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			';

			$wpdb->query($sql);

			// Add the default options
			add_option('lar_enable' , 1);


			do_action('lar_plugin_activation');

			// rewrite rules
			//keywords_create_rewrite_rules();
			 global $wp_rewrite;
    		 $wp_rewrite->flush_rules();

}


/// Replace The links
if( get_option('lar_enable') == 1 ){
	add_filter('the_content','lar_auto_replace_links');
	add_filter('the_excerpt','lar_auto_replace_links');


}

function lar_auto_replace_links($content){
	global $wpdb; 
	global $post;

	$is_disabled =  get_post_meta( $post->ID, 'lar_disabled'  , true );
	
	if($is_disabled == 'on') return $content;


	$links = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'lar_links');
	
	foreach ($links as $link) {
		$dofollow = '';
		if($link->dofollow != 1){
			$dofollow = 'rel="nofollow"';
		}

		if ( get_option('permalink_structure') != '' ) {
			$url = ($link->slug != '')? site_url().'/go/'.$link->slug : $link->keyword_url;
		
		}else{
			$url = ($link->slug != '')? site_url().'/index.php?go='.$link->slug : $link->keyword_url;
		
		}

		
		
		$keywords = explode(',', $link->keyword);
		foreach($keywords as $keyword){
			$final_url = ' <a href="'.$url.'" '.$dofollow.' target="'.$link->open_in.'">'.$keyword.'</a> ';
			$post_content = $content;
			$content =  preg_replace('/\s'.$keyword.'\s/iu', $final_url, $post_content);
			
		}
		
		
	}

	// Replace Content Filter
	$content = apply_filters('lar_replace_content', $content);
	
	return $content;
}


/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if(is_admin()){

	require_once( plugin_dir_path( __FILE__ ).'/admin/admin-interface.php');
}



/*----------------------------------------------------------------------------*
 * @TODO Add rewrite rules
 *----------------------------------------------------------------------------*/
 


add_filter('rewrite_rules_array', 'lar_setup_rewrite_rules');
function lar_setup_rewrite_rules($rules)
{
    $newrules['^go/([^/]*)/?$'] = 'index.php?go=$matches[1]';
    $newrules['^index.php?go=([^/]*)?$'] = 'index.php?go=$matches[1]';
    return $newrules + $rules;
}




add_filter('query_vars', 'add_go_variable');
function add_go_variable($vars)
{
    array_push($vars, 'go');
    return $vars;
}





add_action('template_redirect','lar_redirect');

function lar_redirect(){
	global $wp_query;
	
	if(isset($wp_query->query_vars['go'])){
		global $wpdb;
		$link = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."lar_links WHERE slug='".$wp_query->query_vars['go']."'");
		if(!is_null($link)){
			wp_redirect($link->keyword_url);
			exit;
		}
		
	}
	
}
// Links Auto Replacer Pro Features
if ( file_exists( plugin_dir_path( __FILE__ ).'/pro/lar_pro.php' ) ){
	require_once( plugin_dir_path( __FILE__ ).'/pro/lar_pro.php');
}