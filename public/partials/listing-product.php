<?php
/*
Template Name: Product listing
*/
get_header(); ?>

  <div id="primary" class="content-area col-xs-12">

    <main id="main" class="site-main row" role="main">
      <?php
      $pods = pods( 'product' );
      ?>
      <div class="well form-group">
        <h2>Search</h2>
      <?php  echo $pods->filters( array(
          'fields' => array( 'name', 'famille', 'marque' ),
          'label' => 'Rechercher'
        ) );
        ?>
      </div>
    <?php
      // Get the items, search is automatically handled
      $params = array( 'limit' => 3 );
      $pods->find($params);
      //Listing
      if ( $pods->total() > 0 ) {
        while ( $pods->fetch() ) {
          $name = $pods->display('name');
          $link = 'products/'.$pods->display('permalink');
          $brand = $pods->display('marque');
          $images = $pods->field('images');
      ?>
      <a href="<?= $link ?>" class="col-xs-6 col-md-4">
        <img src="<?= $images[0]['guid'] ?>" />
        <div class="well">
          <h3><?= $name ?></h3>
          <span><?= $brand ?></span>
        </div>
      </a>
      <?php
        }
      }
      ?>


    </main><!-- #main -->
    <div class="row">
      <?= $pods->pagination(); ?>
    </div>
  </div><!-- #primary -->

<?php get_footer(); ?>
