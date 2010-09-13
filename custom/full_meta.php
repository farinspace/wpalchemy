<div class="my_meta_control">
 
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
	Cras orci lorem, bibendum in pharetra ac, luctus ut mauris.</p>
 
	<label>Name</label>
 
	<p>
		<input type="text" name="<?php $metabox->the_name('name'); ?>" value="<?php $metabox->the_value('name'); ?>"/>
		<span>Enter in a name</span>
	</p>
 
	<label>Description <span>(optional)</span></label>
 
	<p>
		<?php $metabox->the_field('description'); ?>
		<textarea name="<?php $metabox->the_name(); ?>" rows="3"><?php $metabox->the_value(); ?></textarea>
		<span>Enter in a description</span>
	</p>

	<label>Authors <span>(Enter in each authors name)</span></label>
 
	<?php while($metabox->have_fields('authors',3)): ?>
	<p>
		<input type="text" name="<?php $metabox->the_name(); ?>" value="<?php $metabox->the_value(); ?>"/>
	</p>
	<?php endwhile; ?>
 
	<label>Info</label>
 
	<p>
		<!-- instead of using helper methods, you can also use array notation: name="custom_meta[info]" -->
		<input type="text" name="<?php $metabox->the_id(); ?>[info]" value="<?php if(!empty($meta['info'])) echo $meta['info']; ?>"/>
		<span>Enter in the info</span>
	</p>

	<label>Links <span>(Enter in the link title and url)</span></label>
 
	<?php while($metabox->have_fields('links',5)): ?>
	<p>
		<?php $metabox->the_field('title'); ?>
		<input type="text" name="<?php $metabox->the_name(); ?>" value="<?php $metabox->the_value(); ?>"/>

		<input type="text" name="<?php $metabox->the_name('url'); ?>" value="<?php $metabox->the_value('url'); ?>"/>

		<br/><?php $metabox->the_field('nofollow'); ?>
		<input type="checkbox" name="<?php $metabox->the_name(); ?>" value="1"<?php if ($metabox->get_the_value()) echo ' checked="checked"'; ?>/> Use <code>nofollow</code>

		<?php $selected = ' selected="selected"'; ?>

		<br/><?php $metabox->the_field('target'); ?>
		<select name="<?php $metabox->the_name(); ?>">
		<option value=""></option>
		<option value="_self"<?php if ($metabox->get_the_value() == '_self') echo $selected; ?>>_self</option>
		<option value="_blank"<?php if ($metabox->get_the_value() == '_blank') echo $selected; ?>>_blank</option>
		<option value="_parent"<?php if ($metabox->get_the_value() == '_parent') echo $selected; ?>>_parent</option>
		<option value="_top"<?php if ($metabox->get_the_value() == '_top') echo $selected; ?>>_top</option>
		</select>
	</p>
	<?php endwhile; ?>

	<p>And one field and field groups will initially display a 
	single field, when new values are added and extra field is 
	displayed allowing a user to add another value, and so on.</p>

	<label>And One... <span>(Enter in a value)</span></label>
 
	<?php while($metabox->have_fields_and_one('and_one')): ?>
	<p>
		<input type="text" name="<?php $metabox->the_name(); ?>" value="<?php $metabox->the_value(); ?>"/>
	</p>
	<?php endwhile; ?>

	<label>And One Group <span>(Enter in the link title and description)</span></label>
 
	<?php while($metabox->have_fields_and_one('and_one_group')): ?>
	<p>
		<?php $metabox->the_field('title'); ?>
		<input type="text" name="<?php $metabox->the_name(); ?>" value="<?php $metabox->the_value(); ?>"/>
		
		<?php $metabox->the_field('description'); ?>
		<input type="text" name="<?php $metabox->the_name(); ?>" value="<?php $metabox->the_value(); ?>"/>
	</p>
	<?php endwhile; ?>

	<h4>Documents</h4>
 
	<a style="float:right; margin:0 10px;" href="#" class="dodelete-docs button">Remove All</a>
 
	<p>Add documents to the library by entering in a title, 
	URL and selecting a level of access. Upload new documents 
	using the "Add Media" box.</p>
 
	<?php while($mb->have_fields_and_multi('docs')): ?>
	<?php $mb->the_group_open(); ?>
 
		<?php $mb->the_field('title'); ?>
		<label>Title and URL</label>
		<p><input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/></p>
 
		<?php $mb->the_field('link'); ?>
		<p><input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/></p>
 
		<?php $mb->the_field('access'); ?>
		<p><strong>Access:</strong>
			<input type="radio" name="<?php $mb->the_name(); ?>" value="admin"<?php echo $mb->is_value('admin')?' checked="checked"':''; ?>/> Admin
			<input type="radio" name="<?php $mb->the_name(); ?>" value="editor"<?php echo $mb->is_value('editor')?' checked="checked"':''; ?>/> Editor
			<input type="radio" name="<?php $mb->the_name(); ?>" value="subscriber"<?php echo $mb->is_value('subscriber')?' checked="checked"':''; ?>/> Subscriber
 
			<a href="#" class="button" style="margin-left:10px;" onclick="jQuery(this).siblings().removeAttr('checked'); return false;">Remove Access</a>
			<a href="#" class="dodelete button">Remove Document</a>
		</p>
 
	<?php $mb->the_group_close(); ?>
	<?php endwhile; ?>
 
	<p style="margin-bottom:15px; padding-top:5px;"><a href="#" class="docopy-docs button">Add Document</a></p>

</div>