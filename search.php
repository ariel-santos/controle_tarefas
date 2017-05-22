<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
    <head>
        <?php get_header('includes'); ?>
    </head>
    <body>
        <?php get_header('topo'); ?>
        <div class="row container">
            <div class="col s12">
				<?php
					if ( have_posts() ) :
						while ( have_posts() ) : the_post();
							the_title();
							the_post_thumbnail();
							the_content();
						endwhile;
					else :
						?>
							<p>Nadaa encontrado </p>
						<?php
							get_search_form();

					endif;
				?>
            </div>
        </div>
        <?php get_footer(); ?>
    </body>
</html>
<?php get_header(); ?>
