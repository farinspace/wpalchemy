<tr class="form-field">
	<th scope="row" valign="top"><label><?php _e('Sample') ?></label></th>
	<td>
		<input type="text" name="sample" value="<?php if (isset($term->sample)) echo $term->sample; ?>"><br />
		<span class="description">The post ID that will be the featured post when viewing this category.</span>
		<?php echo var_dump($term); ?>
	</td>
</tr>