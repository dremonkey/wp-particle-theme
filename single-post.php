<?php
/**
 * The single post template file.
 *
 * Will be used for post, attachment, and custom post types, unless a more specific file matches.
 * Does not include a page.
 *
 * @see http://codex.wordpress.org/Template_Hierarchy
 */
?>

<?php get_header(); ?>

<div class="container">

	<div class="row">

		<section class="span8">
			<?php // Start the Loop
			if ( have_posts() ) : the_post(); ?>
				<?php _include( 'loop-post', get_post_format() ); ?>
			<?php else : ?>
				<?php _include( 'no-results' ); ?>
			<?php endif; ?>
		</section>

		<?php _include_sidebar( 'post' ) ?>

	</div><!-- .row -->

</div><!-- .container -->

<?php get_footer(); ?>