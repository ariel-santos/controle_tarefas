
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
					while ( have_posts() ) : the_post();

						the_title();
						the_post_thumbnail();
						the_content();

						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					endwhile;
				?>
            </div>
        </div>
        <?php get_footer(); ?>
    </body>
</html>
