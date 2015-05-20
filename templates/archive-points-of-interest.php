<?php

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

		<?php if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title"><?php
					_e( 'Points of Interest Archive', 'azc_poi' );
				?></h1>
			</header><!-- .archive-header -->

	<div class="entry-content">
    <table class='azc_poi'>
		<!-- Display table headers -->
		<tr>
			<th class="azc_poi_archive"><strong><?php _e('Name', 'azc_poi'); ?></strong></th>
			<th class="azc_poi_archive"><strong><?php _e('Type', 'azc_poi'); ?></strong></th>
			<th class="azc_poi_archive"><strong><?php _e('Owner', 'azc_poi'); ?></strong></th>
			<th class="azc_poi_archive"><strong><?php _e('Continent', 'azc_poi'); ?></strong></th>
			<th class="azc_poi_archive"><strong><?php _e('Country', 'azc_poi'); ?></strong></th>
			<th class="azc_poi_archive"><strong><?php _e('Region', 'azc_poi'); ?></strong></th>
		</tr>
		<!-- Start the Loop -->
		<?php while ( have_posts() ) : the_post(); ?>
			<tr>
				<td class="azc_poi_archive"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>
				<td class="azc_poi_archive"><?php 
					$assigned_type = wp_get_post_terms( get_the_ID(), 'point-of-interest-type' ) ; ?>
					<a href='<?php bloginfo('url'); ?>/point-of-interest-type/<?php echo esc_html( $assigned_type[0]->slug ); ?>'>
						<?php echo esc_html( $assigned_type[0]->name ); ?>
					</a>
				</td>
				<td class="azc_poi_archive"><?php 
					$assigned_owner = wp_get_post_terms( get_the_ID(), 'point-of-interest-owner' ); ?>
					<a href='<?php bloginfo('url'); ?>/point-of-interest-owner/<?php echo esc_html( $assigned_owner[0]->slug ); ?>'>
						<?php echo esc_html( $assigned_owner[0]->name ); ?>
					</a>
				</td>
				<td class="azc_poi_archive"><?php 
					$assigned_continent = wp_get_post_terms( get_the_ID(), 'point-of-interest-continent' ); ?>
					<a href='<?php bloginfo('url'); ?>/point-of-interest-continent/<?php echo esc_html( $assigned_continent[0]->slug ); ?>'>
						<?php echo esc_html( $assigned_continent[0]->name ); ?>
					</a>
				</td>
				<td class="azc_poi_archive"><?php 
					$assigned_country = wp_get_post_terms( get_the_ID(), 'point-of-interest-country' ); ?>
					<a href='<?php bloginfo('url'); ?>/point-of-interest-country/<?php echo esc_html( $assigned_country[0]->slug ); ?>'>
						<?php echo esc_html( $assigned_country[0]->name ); ?>
					</a>
				</td>
				<td class="azc_poi_archive"><?php 
					$assigned_region = wp_get_post_terms( get_the_ID(), 'point-of-interest-region' ); ?>
					<a href='<?php bloginfo('url'); ?>/point-of-interest-region/<?php echo esc_html( $assigned_region[0]->slug ); ?>'>
						<?php echo esc_html( $assigned_region[0]->name ); ?>
					</a>
				</td>
			</tr>
		<?php endwhile; ?>
    </table>

	</div><!-- .entry-content -->
	
			<?php twentythirteen_paging_nav(); ?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>