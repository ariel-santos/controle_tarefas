<?php
    define('WP_USE_THEMES', false);
    $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
    require_once( $parse_uri[0] . 'wp-load.php' );
    global $wpdb;

    $login = $_REQUEST['login'];
    $pass = $_REQUEST['senha'];

    $user = $wpdb->get_row("SELECT * FROM usuario WHERE Login = '$login' LIMIT 1");
    if(null === $user){
        $resposta = array(
            "cod" => 1,
            "msg" => "Senha e/ou usuario não existe!"
        );
    }else{
        $hash = $user->Senha;

        if( wp_check_password($pass, $hash) ) {
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
                "cod" => 2,
                "msg" => "Senha e/ou usuario não existe! $hash"
            );
        }
    }
    echo json_encode($resposta);
?>
