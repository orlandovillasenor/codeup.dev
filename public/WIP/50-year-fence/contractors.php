<?php
/**
 * Template Name: Contractors
 * @package WordPress
 * @subpackage SASCO
 * @desc A page. See single.php is for a blog post layout.
 */
?>
<?php get_header(); ?>
<div class="container_16 banner">
        <div class="banner_inner">
                <?php if(has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('full'); ?>
                <?php else : ?>
                        <img src="<?php bloginfo( 'template_directory' ); ?>/img/banner.jpg" alt="" />
                <?php endif; ?>
        </div>
</div>
<div role="main" class="container_16 main">
        <div class="grid_10 content">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php the_content(); ?>
            <?php endwhile; endif; wp_reset_postdata(); ?>
            <div id="container">
                <div id="content">
                    <?php 
                        $args = array(
                         'post_type' => 'contractors',
                         'orderby' => 'title',
                         'order' => 'ASC',
                         'nopaging' => true
                        );
                        $temp = $contractors; // assign ordinal query to temp variable for later use  
                        $contractors = null;
                        $contractors = new WP_Query($args);
                    ?> 
                    <?php while ( $contractors->have_posts() ) : $contractors->the_post(); ?>
                    <h5> <?php the_title(); ?> </h5> 
                    <div>
                        <p> <?php echo (types_render_field('location')); ?> </p>
                        <p> <?php echo (types_render_field('phone')); ?> </p>
                        <p> <?php echo (types_render_field('contact-name')); ?> </p>
                        <p> <?php echo (types_render_field('link-url')); ?> </p>
                    </div>
                    <?php endwhile; ?>
                    <?php $contractors = $temp; ?> 
                </div><!-- #content -->
            </div><!-- #container -->
        </div>
    
        <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>