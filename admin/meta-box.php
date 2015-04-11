<?php

/**
 * Adds a meta box to the post editing screen
 */
function lar_custom_meta() {
    $screens = array( 'post', 'page' );

    foreach ( $screens as $screen ) {
        add_meta_box( 'lar_meta', __( 'Disable Links Auto Replacer for this post', 'lar-links-auto-replacer' ), 'lar_meta_callback', $screen );
    }
}
add_action( 'add_meta_boxes', 'lar_custom_meta' );

/**
 * Outputs the content of the meta box
 */
function lar_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'lar_nonce' );

    $lar_disabled_meta = get_post_meta( $post->ID );
    
    ?>
 
    <p>
        
        <input type="checkbox" name="lar_disabled" id="lar_disabled" <?php if (   $lar_disabled_meta['lar_disabled'][0] == 'on' ) echo 'checked="checked"'; ?> />
   		<label for="lar_disabled" class="lar-row-title"><?php _e( 'Disable', 'lar-links-auto-replacer' )?></label>
    </p>
 
    <?php  
}



/**
 * Saves the custom meta input
 */
function lar_meta_save( $post_id ) {
 
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'lar_nonce' ] ) && wp_verify_nonce( $_POST[ 'lar_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
 
    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'lar_disabled' ] ) ) {

        update_post_meta( $post_id, 'lar_disabled',  'on'  );
    }else{
        update_post_meta( $post_id, 'lar_disabled',  'off'  );

    }
 
}
add_action( 'save_post', 'lar_meta_save' );