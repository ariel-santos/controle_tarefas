<?php
    define('WP_USE_THEMES', false); 
    $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
    require_once( $parse_uri[0] . 'wp-load.php' );
    global $wpdb;

    $login = $_REQUEST['user'];
    $pass = $_REQUEST['senha'];
    
    $user = $wpdb->get_row("SELECT * FROM usuario WHERE Login = '$login' LIMIT 1");
            
    $hash = $user->Senha;
    $wp_hasher = new PasswordHash(8, TRUE);

    if( $wp_hasher->CheckPassword($pass, $hash) ) {
        setcookie("login", $login, time() + 3600, "/");  
        setcookie("id", $user->idUsuario, time() + 3600, "/");      
        
        $resposta = array(
            "cod" => 0,
            "msg" => "Acessando..."
        );
    }else{
        setcookie("login", "", time() - 3600, "/");  
        setcookie("id", "", time() - 3600, "/");  
        
        $resposta = array(
            "cod" => 1,
            "msg" => "Usuario e senha não encontrados! "
        );
    }

    echo json_encode($resposta);
?>