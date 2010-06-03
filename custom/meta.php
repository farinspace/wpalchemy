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

</div>