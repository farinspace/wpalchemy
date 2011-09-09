<div class="my_meta_control">
 
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras orci lorem, bibendum in pharetra ac, luctus ut mauris.</p>

	<label>Select Single</label><br/>
	
	<?php $mb->the_field('s_single'); ?>
	<select name="<?php $mb->the_name(); ?>">
		<option value="">Select...</option>
		<option value="a"<?php $mb->the_select_state('a'); ?>>a</option>
		<option value="b"<?php $mb->the_select_state('b'); ?>>b</option>
		<option value="c"<?php $mb->the_select_state('c'); ?>>c</option>
	</select>

	<label>Select Single 2</label><br/>

	<?php $mb->the_field('s_single2'); ?>
	<select name="<?php $mb->the_name(); ?>">
		<option value="">Select...</option>
		<option value="x"<?php $mb->the_select_state('x'); ?>>x</option>
		<option value="y"<?php $mb->the_select_state('y'); ?>>y</option>
		<option value="z"<?php $mb->the_select_state('z'); ?>>z</option>
	</select>

	<label>Select Single 3 (select multiple)</label><br/>

	<?php $mb->the_field('s_single3', WPALCHEMY_FIELD_HINT_SELECT_MULTI); ?>
	<select name="<?php $mb->the_name(); ?>" multiple="multiple" size="5" style="height:75px;">
		<option value="e"<?php $mb->the_select_state('e'); ?>>e</option>
		<option value="f"<?php $mb->the_select_state('f'); ?>>f</option>
		<option value="g"<?php $mb->the_select_state('g'); ?>>g</option>
		<option value="h"<?php $mb->the_select_state('h'); ?>>h</option>
		<option value="i"<?php $mb->the_select_state('i'); ?>>i</option>
		<option value="j"<?php $mb->the_select_state('j'); ?>>j</option>
	</select>

	<label>Select Repeating Group</label><br/>

	<?php while($mb->have_fields_and_multi('s_group')): ?>
	<?php $mb->the_group_open(); ?>

		<?php $mb->the_field('i_field'); ?>
		<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>

		<?php $mb->the_field('s_field'); ?>
		<select name="<?php $mb->the_name(); ?>">
			<option value="">Select...</option>
			<option value="q"<?php $mb->the_select_state('q'); ?>>q</option>
			<option value="r"<?php $mb->the_select_state('r'); ?>>r</option>
			<option value="s"<?php $mb->the_select_state('s'); ?>>s</option>
		</select>

		<?php $mb->the_field('s_field2'); ?>
		<select name="<?php $mb->the_name(); ?>">
			<option value="">Select...</option>
			<option value="q"<?php $mb->the_select_state('q'); ?>>q</option>
			<option value="r"<?php $mb->the_select_state('r'); ?>>r</option>
			<option value="s"<?php $mb->the_select_state('s'); ?>>s</option>
		</select>

	<?php $mb->the_group_close(); ?>
	<?php endwhile; ?>

	<p><a href="#" class="docopy-s_group button">Add</a></p>

	<input type="submit" class="button-primary" name="save" value="Save">

</div>