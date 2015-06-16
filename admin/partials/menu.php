<h2 class="nav-tab-wrapper">
	    <a href="<?php echo admin_url('admin.php?page=lar_settings') ?>" class="nav-tab <?php if($_GET['page'] == 'lar_settings'){ echo 'nav-tab-active'; } ?>"><?php echo __('Settings','links-auto-replacer'); ?></a>

		<?php foreach($this->tabs as $key => $tab): ?>
	    		<a href="<?php echo admin_url('admin.php?page='.$key) ?>" class="nav-tab <?php if($_GET['page'] == $key){ echo 'nav-tab-active'; } ?>"><?php echo __($tab['title'],'links-auto-replacer'); ?></a>
		<?php endforeach; ?>
</h2>
