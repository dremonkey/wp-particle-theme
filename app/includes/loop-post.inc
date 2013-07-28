<?php
/**
 * The default template for displaying the content of a single post.
 */
?>

<article class="post <?php get_post_format(); ?>">
	<h1 class="title"><?php the_title(); ?></h1>
	<?php the_content() ?>
</article>