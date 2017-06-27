<?php
    define('WP_USE_THEMES', false);
    $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
    require_once( $parse_uri[0] . 'wp-load.php' );
    global $wpdb;

    $id = $_POST['id'];
    $login = $_POST['login'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $hash = wp_hash_password($senha);
    $empresa = explode("-", $_POST['empresa']);

    $wpdb->update(
        'usuario',
        array(
            'email' => $email,
            'Login' => $login,
            'Senha' => $hash
        ),
        array(
            "post_id" => $id
        )
    );

    $resposta = array(
        "cod" => 0,
        "msg" => "Dados atualizados com sucesso"
    );

    echo json_encode($resposta);
?>
