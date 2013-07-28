<?php
/**
 * The default template for displaying a 'no results' message
 */
?>

<div class="post no-results">

<?php if( is_search() ) : ?>

	<h3 id="no-results-msg">
		<?php echo __( "Your search returned no results. Please try again.", 'particle' ); ?>
	</h3>

	<?php get_search_form(); ?>

<?php else : ?>

	<h3 id="no-results-msg">
		<?php echo __( "Nothing Here.", 'particle' ); ?>
	</h3>

	<?php get_search_form(); ?>

<?php endif; ?>

</div>