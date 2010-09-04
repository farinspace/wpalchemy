<div class="my_meta_control">
 
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
	Cras orci lorem, bibendum in pharetra ac, luctus ut mauris.</p>

	
	<label>Single Checkbox</label><br/>
	
	<?php $mb->the_field('cb_single'); ?>
	<input type="checkbox" name="<?php $mb->the_name(); ?>" value="abc"<?php $mb->the_checkbox_state('abc'); ?>/> abc<br/>


	<label>Checkbox Group</label><br/>

	<?php $clients = array('a','b','c'); ?>

	<?php while ($mb->have_fields('cb_ex',count($clients))): ?>

	<?php $client = $clients[$mb->get_the_index()]; ?>

	<input type="checkbox" name="<?php $mb->the_name(); ?>" value="<?php echo $client; ?>"<?php $mb->the_checkbox_state($client); ?>/> <?php echo $client; ?><br/>

	<?php endwhile; ?>


	<label>Checkbox Group 2</label><br/>

	<?php $clients = array('a','b','c'); ?>

	<?php foreach ($clients as $i => $client): ?>
		<?php $mb->the_field('cb_ex2'); ?>
		<input type="checkbox" name="<?php $mb->the_name(); ?>" value="<?php echo $client; ?>"<?php $mb->the_checkbox_state($client); ?>/> <?php echo $client; ?><br/>
	<?php endforeach; ?>


	<label>Repeating Field Checkbox</label><br/>

	<?php while($mb->have_fields_and_multi('cb_ex3')): ?>
	<?php $mb->the_group_open(); ?>

		<a href="#" class="dodelete button" style="float:right;">Remove</a>

		<?php $mb->the_field('cb_ex3_name'); ?>
		<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>

		<?php $mb->the_field('cb_ex3_cb'); ?>
		<input type="checkbox" name="<?php $mb->the_name(); ?>" value="xyz"<?php $mb->the_checkbox_state('xyz'); ?>/> xyz<br/>

	<?php $mb->the_group_close(); ?>
	<?php endwhile; ?>

	<p><a href="#" class="docopy-cb_ex3 button">Add</a></p>

</div>