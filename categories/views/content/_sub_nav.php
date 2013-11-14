<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/content/categories') ?>" id="list"><?php echo lang('categories_list'); ?></a>
	</li>
	<?php if ($this->auth->has_permission('Categories.Content.Create')) : ?>
	<!--<li <?php echo $this->uri->segment(4) == 'create' ? 'class="active"' : '' ?> >
		<a href="<?php echo site_url(SITE_AREA .'/content/categories/create') ?>" id="create_new"><?php echo lang('categories_new'); ?></a>
	</li>-->
	<?php endif; ?>
</ul>