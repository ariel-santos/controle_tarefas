<?php
    define('WP_USE_THEMES', false); 
    $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
    require_once( $parse_uri[0] . 'wp-load.php' );
    global $wpdb;

    $nome = $_REQUEST['c_nome'];
    $login = $_REQUEST['c_login'];
    $senha = $_REQUEST['c_senha'];

    $password = wp_hash_password( $senha );

    $wpdb->insert(
        'wp_users',
        array(
            'user_login' => $login,
            'user_pass' => $password,
            'user_nicename' => $login,
            'user_email' => "",
            'user_url' => "",
            'user_registered' => "",
            'user_status' => 0,
            'display_name' => $nome
        )
    );

    $id = $wpdb->insert_id;

    $wpdb->insert(
        'usuario',
        array(
            'Nome' => $nome,
            'Login' => $login,
            'Senha' => $password
        )
    );
    
    setcookie("login", $login, time() + 3600);  
    setcookie("id", $id, time() + 3600);      

    $resposta = array(
        "cod" => 0,
        "msg" => "Usuario cadastrado com sucesso!"
    );

    echo json_encode($resposta);
?>