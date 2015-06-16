<?php



class Lar_Link{


	/**
	* Get the final url that will be replaced in the frontend.
	* @param	 integer link ID
	* @return	 string the final url.
	* @since    2.0.0
	**/
	public function get_final_url( $link_id ){
		$link_meta = get_post_meta( $link_id );
		if($link_meta[PLUGIN_PREFIX.'link_type'][0] == 'external' OR $link_meta[PLUGIN_PREFIX.'link_type'][0] == ''){
				if ( get_option('permalink_structure') != '' ) {
					$url = ($link_meta[PLUGIN_PREFIX.'slug'][0]!= '')? site_url().'/go/'.$link_meta[PLUGIN_PREFIX.'slug'][0] : $link_meta[PLUGIN_PREFIX.'url'][0];
				
				}else{
					$url = ($link_meta[PLUGIN_PREFIX.'slug'][0] != '')? site_url().'/index.php?go='.$link_meta[PLUGIN_PREFIX.'slug'][0] : $link_meta[PLUGIN_PREFIX.'url'][0];
				
				}
		}elseif($link_meta[PLUGIN_PREFIX.'link_type'][0] == 'internal'){ // if internal link
					$url = get_permalink($link_meta[PLUGIN_PREFIX.'internal_url'][0]);
		}
		return $url;
	}
}