<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
    <head>
        <?php get_header('includes'); ?>
    </head>
    <body>
        <?php get_header('topo'); ?>
        <div class="row container">
            <div class="col s12">
                Pagina Home
            </div>
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					?>
						<div class="col s12 m4">
							<?php
								the_post_thumbnail();
								the_title();
								the_content();
							?>
						</div>
					<?php
				endwhile;

				the_posts_pagination( array(
					'prev_text' => twentyseventeen_get_svg( array( 'icon' => 'arrow-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous page', 'twentyseventeen' ) . '</span>',
					'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'twentyseventeen' ) . '</span>' . twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyseventeen' ) . ' </span>',
				) );

			else :
				?>
					<div class="col s12">
						<h3>Não encontrado</h3>
					</div>
				<?php
			endif;
			?>
        </div>
        <?php get_footer(); ?>
    </body>
</html>
