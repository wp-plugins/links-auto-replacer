<?php
/*-----------------------------------------------------------------------------------*/
/* ProPanel Version 2.0
/*-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Admin Interface
/*-----------------------------------------------------------------------------------*/

function propanel_siteoptions_add_admin() {

    global $query_string;
   
    if ( isset($_REQUEST['page']) && $_REQUEST['page'] == 'lar_options_page' ) {
		if (isset($_REQUEST['of_save']) && 'reset' == $_REQUEST['of_save']) {
			$options =  get_option('of_template'); 
			propanel_of_reset_options($options,'lar_options');
			header("Location: admin.php?page=lar_options_page&reset=true");
			die;
		}
    }
		

	$tt_page = add_menu_page('Links Auto Replacer', 'Links Auto Replacer', 'manage_options','lar_options_page','lar_settings_page' ,'dashicons-admin-links');

	add_submenu_page( 'lar_options_page', 'Settings', 'Settings', 'manage_options', 'lar_options_page');
	$lar_links_manager_page =  add_submenu_page( 'lar_options_page', 'Links Manager', 'Links Manager', 'manage_options', 'lar_links_manager','lar_links_manager');
	$lar_help_support_page = add_submenu_page( 'lar_options_page', 'Help and Support', 'Help and Support', 'manage_options', 'lar_help','lar_help');


	add_action("admin_print_scripts-$tt_page", 'links_manager_scripts');
	add_action("admin_print_styles-$tt_page",'links_manager_styles');
	add_action("admin_print_styles-$lar_links_manager_page",'links_manager_styles');
	add_action("admin_print_scripts-$lar_links_manager_page", 'links_manager_scripts');
	add_action("admin_print_styles-$lar_help_support_page",'links_manager_styles');
	add_action("admin_print_scripts-$lar_help_support_page", 'links_manager_scripts');


} 

add_action('admin_menu', 'propanel_siteoptions_add_admin');









/*-----------------------------------------------------------------------------------*/
/* Build the Options Page
/*-----------------------------------------------------------------------------------*/
function lar_links_manager(){
	
	if($_POST and $_REQUEST['link_id']==''){ // add link
		global $wpdb;
		$link['keyword'] = $_POST['keyword'];
		$link['keyword_url'] = $_POST['keyword_url'];
		$link['dofollow'] = ($_POST['dofollow']  == 1)?1:0;
		$link['open_in'] = $_POST['target'];
		$link['cloack'] = ($_POST['cloack'] == 1 )?1:0;
		$link['slug'] = $_POST['slug'];
		$link['created'] = time();
		$link['updated'] = time();
		
		$link_id = $wpdb->insert($wpdb->prefix.'lar_links',$link);

		if(is_numeric($link_id)){
			ob_clean();
			wp_redirect('admin.php?page=lar_links_manager&success=true');
			exit;
		}
		
		
		
		

	}elseif($_REQUEST['link_id']!='' and $_POST){ // edit link

		global $wpdb; 
		$link['keyword'] = $_POST['keyword'];
		$link['keyword_url'] = $_POST['keyword_url'];
		$link['dofollow'] = ($_POST['dofollow']  == 'on')?1:0;
		$link['open_in'] = $_POST['target'];
		$link['cloack'] = ($_POST['cloack'] == 'on' )?1:0;
		$link['slug'] = $_POST['slug'];
		$link['updated'] = time();
		
		$link_id = $wpdb->update($wpdb->prefix.'lar_links',$link,array('id'=>$_REQUEST['link_id']));

		if($link_id !== false){
			
			wp_redirect('admin.php?page=lar_links_manager&edited=true');
			exit;
		}

	}
	if($_GET['link_id'] == ''){
		include_once 'pages/lar_links_manager.php';
	}else{
		include_once 'pages/lar_links_edit.php';
	}
	
}

function links_manager_styles() {
	wp_enqueue_style('links_manager_styles', plugins_url( 'css/links_manager.css' , __FILE__ ) );
	

}
function links_manager_scripts() {
		wp_register_script('notifyjs', plugins_url( 'js/notify.js' , __FILE__ ) , array( 'jquery' ));
		wp_enqueue_script('notifyjs');
}



function lar_help(){
	include_once 'pages/help_support.php';
}

function lar_settings_page(){
	if(isset($_POST['submit'])){
		if($_POST['lar_enable'] == 'on'){
			update_option('lar_enable' , 1 );
		}else{
			update_option('lar_enable' , 0 );
		}

		wp_redirect('admin.php?page=lar_options_page&edited=true');
		exit;
	}
	include_once 'pages/settings.php';
}