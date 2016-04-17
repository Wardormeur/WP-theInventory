<?php
/*
Template Name: Product details
*/
get_header(); ?>

  <div id="primary" class="content-area col-xs-12">

    <main id="main" class="site-main" role="main">


        <?php load_template( plugin_dir_path(__FILE__). 'content-product.php' ); ?>

    </main><!-- #main -->

  </div><!-- #primary -->

<?php get_footer(); ?>
