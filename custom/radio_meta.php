<div class="my_meta_control">
 
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
	Cras orci lorem, bibendum in pharetra ac, luctus ut mauris.</p>

	
	<label>Single Checkbox</label><br/>
	
	<?php $mb->the_field('r_single'); ?>
	<input type="radio" name="<?php $mb->the_name(); ?>" value="abc"<?php $mb->the_radio_state('abc'); ?>/> abc<br/>


	<label>Checkbox Group</label><br/>

	<?php $clients = array('a','b','c'); ?>

	<?php foreach ($clients as $i => $client): ?>
		<?php $mb->the_field('r_ex2'); ?>
		<input type="radio" name="<?php $mb->the_name(); ?>" value="<?php echo $client; ?>"<?php $mb->the_radio_state($client); ?>/> <?php echo $client; ?><br/>
	<?php endforeach; ?>


	<label>Repeating Field Checkbox</label><br/>

	<?php while($mb->have_fields_and_multi('r_ex3')): ?>
	<?php $mb->the_group_open(); ?>

		<?php $mb->the_field('r_ex3_name'); ?>
		<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>

		<?php $mb->the_field('r_ex3_r'); ?>
		<input type="radio" name="<?php $mb->the_name(); ?>" value="x"<?php $mb->the_radio_state('x'); ?>/> x<br/>
		<input type="radio" name="<?php $mb->the_name(); ?>" value="y"<?php $mb->the_radio_state('y'); ?>/> y<br/>
		<input type="radio" name="<?php $mb->the_name(); ?>" value="z"<?php $mb->the_radio_state('z'); ?>/> z<br/>

	<?php $mb->the_group_close(); ?>
	<?php endwhile; ?>

	<p><a href="#" class="docopy-r_ex3 button">Add Document</a></p>

</div>