<?php
function falarme_setup() {

	load_theme_textdomain( 'falarme' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'falarme-featured-image', 2000, 1200, true );
	add_image_size( 'falarme-thumbnail-avatar', 100, 100, true );
	$GLOBALS['content_width'] = 525;

	register_nav_menus( array(
		'top'    => __( 'Top Menu', 'falarme' ),
		'social' => __( 'Social Links Menu', 'falarme' ),
	) );

	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'audio'
	) );

	add_theme_support( 'custom-logo', array(
		'width'       => 250,
		'height'      => 250,
		'flex-width'  => true,
	) );

	add_theme_support( 'customize-selective-refresh-widgets' );
    add_editor_style( array( 'assets/css/editor-style.css' ) );
}

add_action( 'after_setup_theme', 'falarme_setup' );

require get_template_directory() . '/inc/customizer.php';


function falarme_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'falarme' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'falarme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'falarme_widgets_init' );

function falarme_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}

	$link = sprintf( '<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'falarme' ), get_the_title( get_the_ID() ) )
	);
	return ' &hellip; ' . $link;
}

add_filter( 'excerpt_more', 'falarme_excerpt_more' );

function falarme_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'falarme_javascript_detection', 0 );

function falarme_pingback_header(){
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}
add_action( 'wp_head', 'falarme_pingback_header' );

function falarme_colors_css_wrap() {
	if ( 'custom' !== get_theme_mod( 'colorscheme' ) && ! is_customize_preview() ) {
		return;
	}

	require_once( get_parent_theme_file_path( '/inc/color-patterns.php' ) );
	$hue = absint( get_theme_mod( 'colorscheme_hue', 250 ) );
?>
	<style type="text/css" id="custom-theme-colors" <?php if ( is_customize_preview() ) { echo 'data-hue="' . $hue . '"'; } ?>>
		<?php echo falarme_custom_colors_css(); ?>
	</style>
<?php }
add_action( 'wp_head', 'falarme_colors_css_wrap' );

function falarme_scripts() {
	wp_enqueue_style( 'falarme-style', get_stylesheet_uri() );
	wp_enqueue_style( 'materialize-style', get_template_directory_uri() .'/css/materialize.css');
	wp_enqueue_style( 'jquery-ui-style', get_template_directory_uri() .'/css/jquery-ui.min.css');

	wp_enqueue_script( 'html5', get_theme_file_uri( '/assets/js/html5.js' ), array(), '3.7.3' );
	wp_enqueue_script( 'jquery-js', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js' );


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'falarme_scripts' );

function falarme_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	if ( 740 <= $width ) {
		$sizes = '(max-width: 706px) 89vw, (max-width: 767px) 82vw, 740px';
	}

	if ( is_active_sidebar( 'sidebar-1' ) || is_archive() || is_search() || is_home() || is_page() ) {
		if ( ! ( is_page() && 'one-column' === get_theme_mod( 'page_options' ) ) && 767 <= $width ) {
			 $sizes = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
		}
	}
	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'falarme_content_image_sizes_attr', 10, 2 );

function falarme_header_image_tag( $html, $header, $attr ) {
	if ( isset( $attr['sizes'] ) ) {
		$html = str_replace( $attr['sizes'], '100vw', $html );
	}
	return $html;
}
add_filter( 'get_header_image_tag', 'falarme_header_image_tag', 10, 3 );

function falarme_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( is_archive() || is_search() || is_home() ) {
		$attr['sizes'] = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
	} else {
		$attr['sizes'] = '100vw';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'falarme_post_thumbnail_sizes_attr', 10, 3 );

function as_wp_media(){
    wp_enqueue_media();
    wp_register_script( 'as_wp_media_gallery', get_site_url().'/wp-content/plugins/as_open_media/open-media.js', array( 'jquery' ) );
    wp_localize_script( 'as_wp_media_gallery', 'meta_image',
        array(
            'title' => __( 'Escolha uma Imagem', 'prfx-textdomain' ),
            'button' => __( 'Usar Imagem', 'prfx-textdomain' ),
        )
    );
    wp_enqueue_script( 'as_wp_media_gallery' );
}

add_action( 'admin_enqueue_scripts', 'as_wp_media' );
