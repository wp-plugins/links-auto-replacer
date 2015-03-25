<?php




/*-----------------------------------------------------------------------------------*/
/* Admin Interface
/*-----------------------------------------------------------------------------------*/

require 'meta-box.php';
require 'classes/class.base62.php';

add_action('admin_menu', 'propanel_siteoptions_add_admin');

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
		

	$tt_page = add_menu_page(apply_filters('lar_plugin_name','Links Auto Replacer'),
	 apply_filters('lar_plugin_name','Links Auto Replacer'),
	  'manage_options','lar_options_page','lar_settings_page' ,'dashicons-admin-links');

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










/*-----------------------------------------------------------------------------------*/
/* Build the Options Page
/*-----------------------------------------------------------------------------------*/
function lar_links_manager(){
	$lar_include = apply_filters('lar_include_path',$lar_include);
	$last_link_id = get_links_last_id(); 

	if($_POST and $_REQUEST['link_id']==''){ // add link
		global $wpdb;
		$link['keyword'] = $_POST['keywords'];
		$link['keyword_url'] = $_POST['keyword_url'];
		$link['dofollow'] = ($_POST['dofollow']  == 1)?1:0;
		$link['open_in'] = $_POST['target'];
		$link['cloack'] = ($_POST['cloack'] == 1 )?1:0;
		$link['slug'] = $_POST['slug'];
		$link['created'] = time();
		$link['updated'] = time();

		// Create Link Filter
		$link = apply_filters('lar_create_link',$link);

		$link_id = $wpdb->insert($wpdb->prefix.'lar_links',$link);

		if(is_numeric($link_id)){
			ob_clean();
			wp_redirect('admin.php?page=lar_links_manager&success=true');
			exit;
		}
		
		
		
		

	}elseif($_REQUEST['link_id']!='' and $_POST){ // edit link

		global $wpdb; 
		if(function_exists('gmp_strval')){
			$last_link_id = base62encode($_REQUEST['link_id'] + 100); 
		
		}else{
		
			$last_link_id = base62::encode($_REQUEST['link_id'] + 100); 

		}
		

		$link['keyword'] = $_POST['keywords'];
		$link['keyword_url'] = $_POST['keyword_url'];
		$link['dofollow'] = ($_POST['dofollow']  == 'on')?1:0;
		$link['open_in'] = $_POST['target'];
		$link['cloack'] = ($_POST['cloack'] == 'on' )?1:0;
		$link['slug'] = $_POST['slug'];
		$link['updated'] = time();

		// Update Link Filter
		$link = apply_filters('lar_update_link',$link);

		$link_id = $wpdb->update($wpdb->prefix.'lar_links',$link,array('id'=>$_REQUEST['link_id']));

		if($link_id !== false){
			
			wp_redirect('admin.php?page=lar_links_manager&edited=true');
			exit;
		}

	}
	if($_GET['link_id'] == ''){

		include_once $lar_include . 'pages/lar_links_manager.php';
	}else{
		include_once $lar_include . 'pages/lar_links_edit.php';
	}
	
}

function links_manager_styles() {
	wp_enqueue_style('links_manager_styles', plugins_url( 'css/links_manager.css' , __FILE__ ) );
	wp_enqueue_style('select2', plugins_url( 'css/select2.min.css' , __FILE__ ) );
	wp_enqueue_style('lar_menu', plugins_url( 'menu/css/styles.css' , __FILE__ ) );
	

}
function links_manager_scripts() {
		wp_register_script('notifyjs', plugins_url( 'js/notify.js' , __FILE__ ) , array( 'jquery' ));
		wp_enqueue_script('notifyjs');
		wp_register_script('select2-js', plugins_url( 'js/select2.min.js' , __FILE__ ) , array( 'jquery' ));
		wp_enqueue_script('select2-js');
}



function lar_help(){
	$lar_include = apply_filters('lar_include_path',$lar_include);
	include_once $lar_include . 'pages/help_support.php';
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
	$lar_include = apply_filters('lar_include_path',$lar_include);
	include_once $lar_include . 'pages/settings.php';
}


	add_action( 'wp_ajax_delete_link', 'lar_delete_link_callback' );

	function lar_delete_link_callback() {

		$link_id = intval( $_POST['link_id'] );

		// Before Delete a Link Action
		do_action('lar_before_delete_link',$link_id);

		global $wpdb; // this is how you get access to the database

		

		$wpdb->delete($wpdb->prefix.'lar_links',array('id'=>$link_id));

	    // After Deleting a Link Action    
		do_action('lar_after_delete_link',$link_id);

		die(); // this is required to terminate immediately and return a proper response
	}



function get_links_last_id(){
	global $wpdb;
	$id = $wpdb->get_var(  
            "
                SELECT ID 
                FROM ".$wpdb->prefix."lar_links
                ORDER BY ID DESC limit 0,1
            "
         );
	if(function_exists('gmp_strval')){

		return ($id == null)?base62encode(100):base62encode($id+100);
	}else{
		return ($id == null)?base62::encode(100):base62::encode($id+100);
	}
	
}

if(function_exists('gmp_strval')){
	function base62encode($data) {
		$outstring = '';
		$l = strlen($data);
		for ($i = 0; $i < $l; $i += 8) {
			$chunk = substr($data, $i, 8);
			$outlen = ceil((strlen($chunk) * 8)/6); //8bit/char in, 6bits/char out, round up
			$x = bin2hex($chunk);  //gmp won't convert from binary, so go via hex
			$w = gmp_strval(gmp_init(ltrim($x, '0'), 16), 62); //gmp doesn't like leading 0s
			$pad = str_pad($w, $outlen, '0', STR_PAD_LEFT);
			$outstring .= $pad;
		}
		return $outstring;
	}
}

