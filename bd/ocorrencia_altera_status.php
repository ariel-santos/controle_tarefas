<?php
    define('WP_USE_THEMES', false); 
    $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
    require_once( $parse_uri[0] . 'wp-load.php' );
    global $wpdb;

    $id = $_POST['id'];
    $status = $_POST['status'];

    $update = $wpdb->query(
        $wpdb->prepare("
            UPDATE ocorrencia SET OcorrenciaStatus_idOcorrenciaStatus = '$status' WHERE idOcorrencia = '$id'
        ")
    );

    if( false === $update){
        $resposta = array(
            "cod" => 1,
            "msg" => "Dados nao encontrados!"
        );
    }else{
        $resposta = array(
            "cod" => 0,
            "msg" => "Status alterado!"
        );
    }

    echo json_encode($resposta);
?>