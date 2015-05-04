<?php include 'lar_menu.php'; ?>
<div id='lar_main_wrap'>

	 <h1><?php echo __('Links Auto Replacer Options','lar-links-auto-replacer'); ?></h1>

	<h2 class="lar_subheading"><?php echo __('Manage Options','lar-links-auto-replacer'); ?></h2>
	<div id="lar_add_links_form">
		<form action="<?php echo admin_url('admin.php?page=lar_options_page&noheader=true'); ?>" method="post">
			<p>

			<input id="lar_enable" type="checkbox" name="lar_enable" <?php if(get_option('lar_enable') ==1){ echo 'checked="checked"'; } ?> />
			<label for="lar_enable"><?php echo __('Enable Auto Replcement','lar-links-auto-replacer'); ?></label>
			</p>
			<input type="submit" name="submit" value="<?php echo __('Save Settings','lar-links-auto-replacer'); ?>" class="button button-primary" />
		</form>
	</div>

</div>

<script>
	jQuery(document).ready(function(){
		 <?php if ( isset($_REQUEST['edited']) && $_REQUEST['edited'] == 'true' ) { ?>

 			jQuery.notify("<?php echo __('Settings have been successfully updated!!','lar-links-auto-replacer'); ?>",{ globalPosition:"top center",className:'success'});
      	
      	<?php } ?>
	});	


</script>