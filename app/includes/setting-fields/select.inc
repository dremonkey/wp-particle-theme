<?php
/**
 * Template for a standard form select field
 */
?>

<div class="field-wrapper">

	<select name="<?php echo $name ?>">

		<?php foreach ( $options as $option ) : ?>
			
			<?php $selected = $value == $option ? 'selected="selected"' : ''; ?>
			<option value='<?php echo $option ?>' <?php echo $selected ?> >
				<?php echo $option ?>
			</option>

		<?php endforeach; ?>

	</select>

	<p class="description" for="<?php echo $name ?>">
		<?php echo $desc ?>
	</p>
	
</div>