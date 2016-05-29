<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package sparkling
 */
?>

<?php
$tpl_dir = get_stylesheet_directory_uri();
//get current item name
$slug = pods_v( 'last', 'url' );
//get pods object
$pod = pods( 'product', $slug );
//get all attrbiutes;
$attributesSingle = ['name','id', 'famille', 'permalink', 'modified'];
$attributesMultiple = ['reviews', 'composition', 'images', 'marque', 'topics', 'ext_reviews', 'properties'];
$editLink = admin_url() . 'admin.php?page=pods-manage-product&action=edit&id=' . $pod->display('id');
foreach ($attributesSingle as $attribute){
	$$attribute = $pod->display($attribute);
}
foreach ($attributesMultiple as $attribute){
	$$attribute = $pod->field($attribute);
}
$haveReviews = count($reviews) > 0 && $reviews;
$haveTopics =  $topics && count($topics) > 0;
$base_url = site_url( 'products' );
//Calculated fields
$note = 0;
if($reviews){
	array_map(function($arg) use (&$note){
			$_note = pods_field('post', $arg['ID'],'note', true);
			if(is_numeric($_note)){
				$note += (float)$_note;
			}
		},
		$reviews);
		$note = $note/count($reviews);
}
?>
<?php
if( count($images) >0 ){ ?>
	<div class="single-featured top-image">
		<img src="<?= $images[0]['guid']?>" class=""/>
	</div>
	<?php }
	?>
<div class="post-inner-content">
<article id="product-<?= $id ?>" class="product-details">

	<?php
	if( count($images) >0 ){
		?>
		<div class="images col-xs-2">
			<?php
			//TODO : dont just make it as a link, but replace the major image
			foreach($images as $index=>$image){
				echo '<a href="'.$image['guid'].'">'.pods_image( $image['guid'], 'thumbnail' ).'</a>';
			}
			?>
		</div>
		<?php  	}?>
	<header class="entry-header page-header col-xs-10">

			<h1 class="entry-title row"><?= $name ?>
				<span class="pull-right">
					<span class="badge"><?= $famille ?></span>
					<a class="edit-link" href="<?= $editLink ?>">
						<i class="fa fa-pencil-square-o"></i>
					</a>
				</span>
			</h1>
			<div class="row">
				<h3 class="col-xs-8 no-margin-top"><small>by&nbsp;</small><a href="<?= $base_url ?>?type=product&filter_marque=<?= $marque['id'] ?>"><?= $marque['name'] ?></a> </h3>
				<?php if($reviews && $note > 0){ ?>
					<img class="col-xs-4" src="<?= plugin_dir_url(__FILE__).'img/'.$note ?>.png"/>
				<?php } ?>
			</div>
	</header><!-- .entry-header -->

	<ul class="nav nav-tabs">
	  <li role="presentation" class="active"><a href="#reviews" aria-controls="reviews" role="tab" data-toggle="tab">Reviews</a></li>
	  <li role="presentation"><a href="#specs" aria-controls="specs" role="tab" data-toggle="tab">Technical specs</a></li>
	</ul>
	<div class="tab-content entry-content row">
		<div role="tabpanel" class="reviews tab-pane active fade in" id="reviews">
		<?php if ( $haveTopics || $haveReviews) {
			?>
			<ul class="list-unstyled col-xs-10">
			<?php
			if ($haveReviews) {
					foreach($reviews as $review){
			?>
			<li class="row">
				<a href="<?= get_permalink($review['ID']) ?>">
					<span class="h4 col-xs-10"><?= $review['post_name'] ?> <small> by  <?= get_the_author_meta('display_name', $review['post_author']); ?></small></span>
					<span class="review-img col-xs-2"><?php echo pods_image(get_post_meta( $review['ID'], '_thumbnail_id', true)); ?></span>
					<blockquote><?php echo wp_trim_words( $review['post_content'] ); ?> </blockquote>
				 </a>
			</li>
			<?php }
			}
			if ($haveTopics) {
				foreach($topics as $topic){
			?>
			<li class="row">
				<a href="<?= $topic ?>">
					<span class="h4 col-xs-10">Sur le forum : <?= $anem ?> <small></small></span>
				 </a>
			</li>
			<?php
				}
			} ?>
			</ul>
			<?php } ?>
		</div>

		<div role="tabpanel" class="composition tab-pane fade" id="specs">
		<?php if ( count($composition) > 0 && $composition) {
			?>
			<h4>Composition</h4>
			<?php foreach($composition as $composant){
				?>
				<a href="<?= $composant['permalink'] ?>"><?= $composant['name'] ?></a>
			<?php }
		}?>
		<?php if ( count($properties) > 0 && $properties) {
			?>
			<h4> Properties </h4>
			<ul class="list-unstyled">
			<?php foreach($properties as $property){
				$propertyInstance = pods('propertyinstance', $property['id']);
				$propertyModel = pods('property', $propertyInstance->field('property')['id']);
				?>
				<li><?= $propertyModel->field('name').' '. $propertyInstance->field('value') . ' '.$propertyModel->field('unit') ?></li>
			<?php }?>
			</ul>
		<?php }?>
		</div>
		<small class="pull-right">Last update <?= $modified ?></small>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
</div>
<script type="text/javascript">
	$('.tab-content a').click(function (e) {
		e.preventDefault()
		$(this).tab('show')
	});
</script>
