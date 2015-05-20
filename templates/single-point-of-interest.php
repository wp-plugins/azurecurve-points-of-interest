<?php

$options = get_option('azc_poi_options');

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
			
			
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if ( has_post_thumbnail() && ! post_password_required() && ! is_attachment() ) : ?>
		<div class="entry-thumbnail">
			<?php the_post_thumbnail(); ?>
		</div>
		<?php endif; ?>
		
		<h1 class="entry-title"><?php echo the_title(); ?></h1>
		
	<div class="entry-content">
	
	<table class='azc_poi'>
	<tr><th class='azc_poi'><?php _e('Type', 'azc_poi'); ?>:</th><td class='azc_poi'>
		<?php $assigned_type = wp_get_post_terms( get_the_ID(), 'point-of-interest-type' ); ?>
		<a href='<?php bloginfo('url'); ?>/point-of-interest-type/<?php echo str_replace( ' ', '-', strtolower( esc_html( $assigned_type[0]->name ) ) ); ?>/'>
		<?php
		echo esc_html( $assigned_type[0]->name );
		?>
	</a>
	</td></tr>
		<tr><th class='azc_poi'><?php _e('Owner', 'azc_poi'); ?>:</th><td class='azc_poi'>
		<?php $assigned_owner = wp_get_post_terms( get_the_ID(), 'point-of-interest-owner' ); ?>
		<a href='<?php bloginfo('url'); ?>/point-of-interest-owner/<?php echo str_replace( ' ', '-', strtolower( esc_html( $assigned_owner[0]->name ) ) ); ?>/'>
		<?php
		echo esc_html( $assigned_owner[0]->name );
		?>
	</a>
	</td></tr></td></tr>
	<tr><th class='azc_poi'><?php _e('Details', 'azc_poi'); ?>:</th><td class='azc_poi'><?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Continue reading %s', 'azc_poi' ),
				the_title( '<span class="screen-reader-text">', '</span>', false )
			)  . ' <span class="meta-nav">&rarr;</span>' );
		?></td></tr>
	<tr><th class='azc_poi'><?php _e('Continent', 'azc_poi'); ?>:</th><td class='azc_poi'>
		<?php $assigned_continent = wp_get_post_terms( get_the_ID(), 'point-of-interest-continent' ); ?>
		<a href='<?php bloginfo('url'); ?>/point-of-interest-continent/<?php echo str_replace( ' ', '-', strtolower( esc_html( $assigned_continent[0]->name ) ) ); ?>/'>
		<?php
		echo esc_html( $assigned_continent[0]->name );
		?>
	</a>
	</td></tr>
	<tr><th class='azc_poi'><?php _e('Country', 'azc_poi'); ?>:</th><td class='azc_poi'>
		<?php $assigned_country = wp_get_post_terms( get_the_ID(), 'point-of-interest-country' ); ?>
		<a href='<?php bloginfo('url'); ?>/point-of-interest-country/<?php echo str_replace( ' ', '-', strtolower( esc_html( $assigned_country[0]->name ) ) ); ?>/'>
		<?php
		echo esc_html( $assigned_country[0]->name );
		?>
	</a>
	</td></tr>
	<tr><th class='azc_poi'><?php _e('Region', 'azc_poi'); ?>:</th><td class='azc_poi'>
		<?php $assigned_region = wp_get_post_terms( get_the_ID(), 'point-of-interest-region' ); ?>
		<a href='<?php bloginfo('url'); ?>/point-of-interest-region/<?php echo str_replace( ' ', '-', strtolower( esc_html( $assigned_region[0]->name ) ) ); ?>/'>
		<?php
		echo esc_html( $assigned_region[0]->name );
		?>
	</a>
	</td></tr>
	<?php
	$location = esc_html( get_post_meta( get_the_ID(), 'point-of-interest-location', true ) );
	if (strlen($location) > 0){ ?>
		<tr><th class='azc_poi'><?php _e('Location', 'azc_poi'); ?>:</th><td class='azc_poi'><?php echo $location; ?></td></tr>
	<?php } ?>
	<?php
	$grid_reference = esc_html( get_post_meta( get_the_ID(), 'point-of-interest-grid-reference', true ) );
	if (strlen($grid_reference) > 0){ ?>
		<tr><th class='azc_poi'><?php _e('Grid Reference', 'azc_poi'); ?>:</th><td class='azc_poi'><?php echo grid_reference; ?></td></tr>
	<?php } ?>
	<?php
	$telephone = esc_html( get_post_meta( get_the_ID(), 'point-of-interest-telephone', true ) );
	if (strlen($telephone) > 0){ ?>
		<tr><th class='azc_poi'><?php _e('Telephone', 'azc_poi'); ?>:</th><td class='azc_poi'><?php echo telephone; ?></td></tr>
	<?php } ?>
	<?php
	$email = esc_html( get_post_meta( get_the_ID(), 'point-of-interest-email', true ) );
	if (strlen($email) > 0){ ?>
		<tr><th class='azc_poi'><?php _e('Email', 'azc_poi'); ?>:</th><td class='azc_poi'><?php echo email; ?></td></tr>
	<?php } ?>
	<?php
	$website = esc_html( get_post_meta( get_the_ID(), 'point-of-interest-website', true ) );
	if (strlen($website) > 0){ ?>
		<tr><th class='azc_poi'><?php _e('Website', 'azc_poi'); ?>:</th><td class='azc_poi'><a href='http://<?php echo website; ?>'><?php echo website; ?></a></td></tr>
	<?php } ?>
	<?php
	$twitter = esc_html( get_post_meta( get_the_ID(), 'point-of-interest-twitter', true ) );
	if (strlen($twitter) > 0){ ?>
		<tr><th class='azc_poi'><?php _e('Twitter', 'azc_poi'); ?>:</th><td class='azc_poi'><a href='https://twitter.com/<?php echo str_replace( '@', '', twitter ); ?>'><?php echo twitter; ?></a></td></tr>
	<?php } ?>
	<?php
	$facebook = esc_html( get_post_meta( get_the_ID(), 'point-of-interest-facebook', true ) );
	if (strlen($facebook) > 0){ ?>
		<tr><th class='azc_poi'><?php _e('Facebook', 'azc_poi'); ?>:</th><td class='azc_poi'><?php echo facebook; ?></td></tr>
	<?php } ?>
	<?php
	$linkedin = esc_html( get_post_meta( get_the_ID(), 'point-of-interest-linkedin', true ) );
	if (strlen($linkedin) > 0){ ?>
		<tr><th class='azc_poi'><?php _e('LinkedIn', 'azc_poi'); ?>:</th><td class='azc_poi'><?php echo linkedin; ?></td></tr>
	<?php } ?>
	</table>
		<?php
			wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'azc_poi' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
		?>
	</div><!-- .entry-content -->
	
		<div class="entry-meta">
			<?php twentythirteen_entry_meta(); ?>
			<?php edit_post_link( __( 'Edit', 'azc_poi' ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
	

	<footer class="entry-meta">
		<?php if ( comments_open() && ! is_single() ) : ?>
			<div class="comments-link">
				<?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a comment', 'azc_poi' ) . '</span>', __( 'One comment so far', 'azc_poi' ), __( 'View all comments', 'azc_poi' ) ); ?>
			</div><!-- .comments-link -->
		<?php endif; // comments_open() ?>

		<?php if ( is_single() && get_the_author_meta( 'description' ) && is_multi_author() ) : ?>
			<?php get_template_part( 'author-bio' ); ?>
		<?php endif; ?>
	</footer><!-- .entry-meta -->
</article><!-- #post -->


				<?php comments_template(); ?>

			<?php endwhile; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>