<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * 
 * @see http://codex.wordpress.org/Template_Hierarchy
 */
?>

<?php get_header(); ?>

<div class="container">

	<div class="row">

		<section class="span8">

			<?php if ( have_posts() ) : ?>

				<?php // Start the Loop ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php _include( 'loop-post', get_post_format() ); ?>

				<?php endwhile; ?>

			<?php else : ?>

				<?php _include( 'no-results' ); ?>

			<?php endif; ?>

		</section>

		<?php _include_sidebar( 'default' ) ?>

	</div><!-- .row -->

</div><!-- .container -->

<?php get_footer(); ?>