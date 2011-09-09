<div class="my_meta_control">
 
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
	Cras orci lorem, bibendum in pharetra ac, luctus ut mauris.</p>


	<label>Single checkbox test</label><br/>
	
	<?php $mb->the_field('cb_single'); ?>
	<input type="checkbox" name="<?php $mb->the_name(); ?>" value="abc"<?php $mb->the_checkbox_state('abc'); ?>/> abc<br/>


	<label>Group checkbox test #1</label><br/>

	<?php $items = array('a', 'b', 'c'); ?>

	<?php while ($mb->have_fields('cb_ex', count($items))): ?>
	
		<?php $item = $items[$mb->get_the_index()]; ?>

		<input type="checkbox" name="<?php $mb->the_name(); ?>" value="<?php echo $item; ?>"<?php $mb->the_checkbox_state($item); ?>/> <?php echo $item; ?><br/>

	<?php endwhile; ?>


	<label>Group checkbox test #2</label><br/>

	<?php $items = array('a', 'b', 'c'); ?>

	<?php foreach ($items as $i => $item): ?>

		<!-- because I am not using a while loop with "have_fields()", I must 
		define the field name with "the_field()", the addition of the field hint
		"WPALCHEMY_FIELD_HINT_CHECKBOX_MULTI" is used, in this case, to add
		array brackets "[]" to the field name (cb_ex2[]) -->
		<?php $mb->the_field('cb_ex2', WPALCHEMY_FIELD_HINT_CHECKBOX_MULTI); ?>
	
		<input type="checkbox" name="<?php $mb->the_name(); ?>" value="<?php echo $item; ?>"<?php $mb->the_checkbox_state($item); ?>/> <?php echo $item; ?><br/>
		
	<?php endforeach; ?>


	<label>Group checkbox test #3</label><br/>

	<?php $items = array('a', 'b', 'c'); ?>

	<?php foreach ($items as $i => $item): ?>

		<?php $mb->the_field('cb_ex3'); ?>

		<!-- similar to test #2, the same thing can be accomplished by simply
		adding array brackets "[]" to the name -->
		<input type="checkbox" name="<?php $mb->the_name(); ?>[]" value="<?php echo $item; ?>"<?php $mb->the_checkbox_state($item); ?>/> <?php echo $item; ?><br/>

	<?php endforeach; ?>


	<label>Repeating field with checkbox test</label><br/>

	<?php while($mb->have_fields_and_multi('cb_ex4')): ?>
	<?php $mb->the_group_open(); ?>

		<a href="#" class="dodelete button" style="float:right;">Remove</a>

		<?php $mb->the_field('cb_ex4_name'); ?>
		<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>

		<?php $mb->the_field('cb_ex4_cb'); ?>
		<input type="checkbox" name="<?php $mb->the_name(); ?>" value="xyz"<?php $mb->the_checkbox_state('xyz'); ?>/> xyz<br/>

		<br/>
		<br/>

		<?php $items = array('a', 'b', 'c'); ?>
		
		<?php foreach ($items as $i => $item): ?>

			<!-- because I am not using a while loop with "have_fields()", I must 
			define the field name with "the_field()", the addition of the field hint
			"WPALCHEMY_FIELD_HINT_CHECKBOX_MULTI" is used, in this case, to add
			array brackets "[]" to the field name (cb_ex2[]) -->
			<?php $mb->the_field('cb_ex5', WPALCHEMY_FIELD_HINT_CHECKBOX_MULTI); ?>

			<input type="checkbox" name="<?php $mb->the_name(); ?>" value="<?php echo $item; ?>"<?php $mb->the_checkbox_state($item); ?>/> <?php echo $item; ?><br/>
			
		<?php endforeach; ?>

		<br/>
		<br/>

		<?php $items = array('a', 'b', 'c'); ?>

		<?php foreach ($items as $i => $item): ?>

			<!-- because I am not using a while loop with "have_fields()", I must 
			define the field name with "the_field()", the addition of the field hint
			"WPALCHEMY_FIELD_HINT_CHECKBOX_MULTI" is used, in this case, to add
			array brackets "[]" to the field name (cb_ex2[]) -->
			<?php $mb->the_field('cb_ex6', WPALCHEMY_FIELD_HINT_CHECKBOX_MULTI); ?>
		
			<input type="checkbox" name="<?php $mb->the_name(); ?>" value="<?php echo $item; ?>"<?php $mb->the_checkbox_state($item); ?>/> <?php echo $item; ?><br/>
			
		<?php endforeach; ?>

	<?php $mb->the_group_close(); ?>
	<?php endwhile; ?>

	<p><a href="#" class="docopy-cb_ex4 button">Add</a></p>

	<input type="submit" class="button-primary" name="save" value="Save">

</div>