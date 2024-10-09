<?php

/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package elevation
 */

get_header();

$term = get_queried_object();
$above_content = get_field('team_info_above', $term);
$below_content = get_field('team_info_below', $term);

?>

<?php
get_template_part('template-parts/content', 'banner');
?>

<main id="primary" class="site-main container with-banner">
  <div class="block ">

    <?php if (have_posts()) : ?>

      <?php
      if ($above_content) {
        echo apply_filters('the_content', get_post_field('post_content', $above_content));
      }
      ?>
      <div class="block__team block__team--license ">

        <div class="block__team-row row row-cols-xl-1 row-cols-md-2 row-cols-1">

       
      <?php
      /* Start the Loop */
      while (have_posts()) :
        the_post();

        /*
            * Include the Post-Type-specific template for the content.
            * If you want to override this in a child theme, then include a file
            * called content-___.php (where ___ is the Post Type name) and that will be used instead.
            */
        get_template_part('template-parts/content-team', get_post_type());

      endwhile;

      the_posts_pagination();

    else :

      get_template_part('template-parts/content', 'none');

      echo '</div></div>';

    endif;
      ?>

      <?php
      if ($below_content) {
        echo apply_filters('the_content', get_post_field('post_content', $below_content));
      }
      ?>

  </div>
</main><!-- #main -->

<?php
get_sidebar();
get_footer();
