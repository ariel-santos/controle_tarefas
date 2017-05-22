<?php
    define('WP_USE_THEMES', false); 
    $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
    require_once( $parse_uri[0] . 'wp-load.php' );
    global $wpdb;

    $descricao = $_REQUEST['descricao'];
    
    $wpdb->insert(
        'area',
        array(
            'Descricao' => $descricao
        )
    );
    $resposta = array(
        "cod" => 0,
        "msg" => "Projeto cadastrado com sucesso !"
    );

    echo json_encode($resposta);
?>