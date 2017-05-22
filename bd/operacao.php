<?php
    define('WP_USE_THEMES', false); 
    $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
    require_once( $parse_uri[0] . 'wp-load.php' );
    global $wpdb;
    
    date_default_timezone_set('America/Sao_Paulo');

    $id = $_POST['id'];
    $status = $_POST['status'];
    $data = date("Y-m-d H:i:s");

    //status 4 alterar termino a cada 1 min

    $interferencia = $wpdb->get_row(" SELECT * FROM operacao WHERE Ocorrencia_idOcorrencia = $id ");

    if( $wpdb->num_rows > 0 ){
        $update = $wpdb->query(
            $wpdb->prepare("
                UPDATE operacao SET Termino = '$data' WHERE Ocorrencia_idOcorrencia = '$id'
            ")
        );
        $resposta = array(
            "cod" => 0,
            "msg" => "Operacao atualizada! "
        );
    }else{
        $wpdb->insert(
            "operacao",
            array(
                "Data" => $data,
                "Inicio" => $data,
                "Ocorrencia_idOcorrencia" => $id
            )
        );
        $resposta = array(
            "cod" => 0,
            "msg" => "Operacao cadastrada! "
        );
    }

    echo json_encode($resposta);
?>