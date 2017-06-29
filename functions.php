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

/*	Registrando usuario do falarme */
add_action('init', 'colaborador_register');
function colaborador_register() {
    $labels = array(
        'name' => _x('Colaborador', 'post type general name'),
        'singular_name' => _x('Colaborador ', 'post type singular name'),
        'add_new' => _x('Adicionar Novo', 'Colaborador item'),
        'add_new_item' => __('Adicionar Novo Colaborador'),
        'edit_item' => __('Editar Colaborador'),
        'new_item' => __('Novo Colaborador'),
        'view_item' => __('Ver Colaborador'),
        'search_items' => __('Procurar Noticia'),
        'not_found' =>  __('Nada Encontrado'),
        'not_found_in_trash' => __('Nada Encontrado na Lixeira'),
        'parent_item_colon' => ''
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_icon'   => 'dashicons-businessman',
        'supports' => array('title','editor', 'thumbnail')
    );
    register_post_type( 'colaborador' , $args );
}

add_action('add_meta_boxes', 'colaborador_metabox');

function colaborador_metabox(){
    add_meta_box(
        'colaborador',
        'Dados colaborador',
        'colaborador_html',
        'colaborador',
        'normal',
        'low'
    );
}

add_action('save_post', 'save_colaborador');

function save_colaborador($post_id){
    global $wpdb;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	$post_id = $_POST['post_ID'];
	$post_type = $_POST['post_type'];

	if($post_type == 'colaborador'){
		$login = $_POST['login'];
		$email = $_POST['email'];
		$senha = $_POST['senha'];
		$ativo = $_POST['ativo'];
		$cor = $_POST['cor'];
		$empresa = explode("-", $_POST['empresa']);

		$status = $_POST['original_post_status'];

		$hash = wp_hash_password($senha);

		if( $status == 'publish' ){
			$wpdb->update(
				'usuario',
				array(
					'email' => $email,
					'Login' => $login,
					'cor' => $cor,
					'ativo' => $ativo,
					'empresa_id' => $empresa[0],
					'Senha' => $hash
				),
				array(
					'post_id' => $post_id
				)
			);
		}else{
			$wpdb->insert(
				'usuario',
				array(
					'post_id' => $post_id,
					'email' => $email,
					'Login' => $login,
					'cor' => $cor,
					'ativo' => $ativo,
					'empresa_id' => $empresa[0],
					'Senha' => $hash
				)
			);
		}
	}

}

function colaborador_html($post){
    global $wpdb;
    $post_id = get_the_ID();
	$usuario = $wpdb->get_row(" SELECT * FROM usuario WHERE post_id = $post_id ");
	$empresa_id = $usuario->empresa_id;
	if( "" == $usuario->cor ){
		$usuario_cor = "#123456";
	}else{
		$usuario_cor = $usuario->cor;
	}

	$empresas_sql = $wpdb->get_results(" SELECT ID, post_title FROM wp_posts WHERE post_type = 'empresa' AND post_status = 'publish' ");
		foreach ($empresas_sql as $es) {
			$index = $es->ID . "-" . $es->post_title;
			$empresa[$index] = "";
		}

		$minha_empresa = $wpdb->get_var("
			SELECT concat(e.post_id , '-', wpp.post_title) as empresa_nome
			FROM empresa e, wp_posts wpp
			WHERE e.post_id = $empresa_id
			AND wpp.ID = e.post_id
			LIMIT 1
		");
	?>
		<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/materialize.min.js"></script>
		<link type="text/css" rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/materialize.css" >
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/farbtastic_color_picker.js"></script>
		<link type="text/css" rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/farbtastic_color_picker.css" >

		<script>
			jQuery(document).ready(function(){
				jQuery('input.empresa_autocomplete').autocomplete({
				    data: <?php echo json_encode($empresa); ?>
			  	});

				jQuery('#picker').farbtastic('#cor');
				jQuery('select').material_select();
			});
		</script>
		<div class="wrap">
			<div class="row">
				<div class="input-field col s2">
					<select name="ativo" id="ativo">
						<option value="0" <?php selected( $usuario->ativo, 0 );  ?>>Não</option>
				      	<option value="1" <?php selected( $usuario->ativo, 1 );  ?>>Sim</option>
				    </select>
					<label>Ativo</label>
				</div>
				<div class="input-field col s6">
		          	<input type="text" id="empresa" name="empresa" class="empresa_autocomplete" placeholder="Pesquisar pela empresa " value="<?php echo $minha_empresa; ?>">
		          	<label for="empresa">Empresa</label>
		        </div>
				<div class="input-field col s4">
		          	<input id="login" name="login" type="text" value="<?php echo $usuario->Login; ?>">
		          	<label for="login">Login</label>
		        </div>

				<div class="input-field col s4">
					<input id="cor" name="cor" type="text" value="<?php echo $usuario_cor; ?>">
					<label for="cor">COLABORADOR</label>
				</div>

				<div class="input-field col s4">
		          	<input id="email" name="email" type="text" value="<?php echo $usuario->email; ?>">
		          	<label for="email">Email</label>
		        </div>
				<div class="input-field col s4">
		          	<input id="senha" name="senha" type="text" placeholder="sua senha sera criptografada ">
		          	<label for="senha">Senha</label>
		        </div>

			</div>
			<div class="col s12">
				<div id="picker"></div>
			</div>
		</div>
	<?php
}

/*	Registrando empresa do falarme */
add_action('init', 'empresa_register');
function empresa_register(){
    $labels = array(
        'name' => _x('Empresa', 'post type general name'),
        'singular_name' => _x('Empresa ', 'post type singular name')
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_icon'   => 'dashicons-store',
        'supports' => array('title','editor', 'thumbnail')
    );
    register_post_type( 'empresa' , $args );
}

add_action('add_meta_boxes', 'empresa_metabox');

function empresa_metabox(){
    add_meta_box(
        'empresa',
        'Dados empresa',
        'empresa_html',
        'empresa',
        'normal',
        'low'
    );
}

add_action('save_post', 'save_empresa');

function save_empresa($post_id){
    global $wpdb;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	$post_id = $_POST['post_ID'];
	$post_type = $_POST['post_type'];

	if($post_type == 'empresa'){
		$telefone = $_POST['telefone'];
		$site = $_POST['site'];

		$wpdb->replace(
			'empresa',
			array(
				'post_id' => $post_id,
				'telefone' => $telefone,
				'site' => $site
			)
		);
	}
}

function empresa_html($post){
    global $wpdb;
    $post_id = get_the_ID();
	$empresa = $wpdb->get_row("SELECT * FROM empresa WHERE post_id = $post_id ");
	?>
		<style>
			.pointer{ cursor: pointer; }
			#TB_ajaxContent{ width: 100% !important; height: 100% !important; }
		</style>
		<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/materialize.min.js"></script>
	    <link type="text/css" rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/materialize.css" >
		<!-- Color picker -->
		<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/farbtastic_color_picker.js"></script>
	    <link type="text/css" rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/farbtastic_color_picker.css" >
	    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script>
			jQuery(document).ready(function(){
				jQuery('#picker').farbtastic('#cor');
				jQuery('select').material_select();
				jQuery('.tooltipped').tooltip({delay: 50});
			});

			function projeto_abrir_modal(){
				jQuery(".btn-modal-cadastro").attr("onclick", "modal_projeto_cadastrar");
				jQuery("#TB_ajaxContent input").val("");
			}

			function modal_projeto_cadastrar(){
				dados = jQuery("#TB_ajaxContent input").serialize();
				url = "<?php echo get_template_directory_uri(); ?>/adm/empresa/projeto.php";
				jQuery.post(url , dados, function(data){
					Materialize.toast(data.msg, 4000);
					if(data.cod == 0 ){
						setTimeout(function(){
							location.reload();
						}, 1500);
					}
				}, "json");
			}

			function projeto_apagar(id){
				url = "<?php echo get_template_directory_uri(); ?>/adm/empresa/projeto.php";
				dados = {id: id, acao: "delete" };
				jQuery.post(url, dados, function(data){
					Materialize.toast(data.msg, 4000);
					if(data.cod == 0 ){
						setTimeout(function(){
							location.reload();
						}, 1000);
					}
				}, "json");
			}

			function projeto_detalhe(id){
				url = "<?php echo get_template_directory_uri(); ?>/adm/empresa/projeto.php";
				dados = {id: id, acao: "detalhe" };
				jQuery.post(url, dados, function(data){
					console.log(data);
					jQuery(".btn-add-projeto").trigger("click");
					jQuery("#TB_ajaxContent label").addClass("active");
					jQuery("#TB_ajaxContent p b").html("Atualização de Projeto");
					jQuery("#TB_ajaxContent input[name=acao]").val("update");
					jQuery("#TB_ajaxContent input[name=projeto_id]").val(data.projeto_id);
					jQuery("#TB_ajaxContent input[name=descricao]").val(data.projeto_descricao);
					jQuery("#TB_ajaxContent input[name=cor]").val(data.projeto_cor);
					jQuery("#TB_ajaxContent input[name=cor]").css("background", data.projeto_cor);
					jQuery(".btn-modal-cadastro").attr("onclick", "projeto_editar()");
					jQuery(".btn-modal-cadastro").text("Atualiar");
				}, "json");
			}
			function projeto_editar(){
				url = "<?php echo get_template_directory_uri(); ?>/adm/empresa/projeto.php";
				dados = jQuery("#TB_ajaxContent input").serialize();

				jQuery.post(url, dados, function(data){
					console.log(data);
					if(data.cod == 0){
						location.reload();
					}
				}, "json" );
			}

		</script>
		<div class="wrap">
			<div class="row">
				<div class="input-field col s4">
					<input id="telefone" name="telefone" type="text" value="<?php echo $empresa->telefone; ?>">
		          	<label for="telefone">Telefone</label>
		        </div>
				<div class="input-field col s4">
					<input id="site" name="site" type="text" value="<?php echo $empresa->site; ?>">
		          	<label for="site">Site</label>
		        </div>
			</div>
			<!-- botao que ativa modal de cadastro de projeto -->
			<div class="row">
				<?php add_thickbox(); ?>
				<a href="#TB_inline?&inlineId=modal_projeto" class="thickbox btn black btn-add-projeto">+ Projeto</a>
			</div>

			<div class="row container-projetos">
				<?php
					$projetos = $wpdb->get_results("SELECT * FROM area WHERE empresa_id = $post_id ");
					if(!empty($projetos)){
					?>
					<h1>Projetos relacionados</h1>
					<table class="striped">
				   		<thead>
					 		<tr>
								<th>Identificacao</th>
						 		<th>Descricao</th>
						 		<th>Cor</th>
								<th>Editar</th>
								<th>Excluir</th>
					 		</tr>
				   		</thead>
						<tbody>
							<?php
								foreach ($projetos as $p) {
									?>
									<tr>
										<td><?php echo $p->idArea; ?></td>
										<td><?php echo $p->Descricao; ?></td>
										<td> <div style="width:20px; height:20px; background:<?php echo $p->cor; ?>;"></div> </td>
										<td><i class="material-icons pointer" onclick="projeto_detalhe(<?php echo $p->idArea; ?>)">mode_edit</i></td>
										<td><i class="material-icons pointer" onclick="projeto_apagar(<?php echo $p->idArea; ?>)">delete</i></td>
								  	</tr>
									<?php
								}
							?>
						</tbody>
 					</table>
				<?php } ?>
			</div>
		</div>

		<!--        MODAL PARA CADASTRO DE FOTO         -->
        <div id="modal_projeto" style="display:none;">
            <div class="row modal-container-cadastro">
                <div class="col s12">
                    <p><b> Cadastro de Projeto </b></p>
                </div>
				<form>
				<input type="hidden" name="acao" value="cadastrar">
				<input type="hidden" name="empresa_id" value="<?php echo $post_id; ?>">
				<input type="hidden" name="projeto_id" value="">

                <div class="col s12">
                    <div class="input-field col s12 m8">
                        <input type="text" name="descricao" id="descricao">
                        <label class="label_toggle"> Descrição/Nome </label>
                    </div>
					<div class="input-field col s12 m4">
						<select name="letra_cor">
					      	<option value="#000000"> Preto </option>
					      	<option value="#ffffff" selected> Branco </option>
					    </select>
						<label> Cor da Letra </label>
					</div>
					<div class="col s12"> Cor do Projeto </div>
                    <div class="input-field col s12 m8">
                        <input type="text" name="cor" id="cor" value="#123456">
                    </div>
                </div>
				</form>
				<div class="col s12 m6">
					<div id="picker"></div>
				</div>
            </div>
            <div class="row center">
                <a href="#!" class="btn black btn-modal-cadastro" onclick="modal_projeto_cadastrar()">Cadastrar</a>
            </div>
        </div>
	<?php
}
