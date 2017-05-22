<?php
    define('WP_USE_THEMES', false); 
    $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
    require_once( $parse_uri[0] . 'wp-load.php' );
    global $wpdb;

    $id = $_POST['id'];

    $urls = $wpdb->get_results("SELECT Url FROM ocorrenciaanexo WHERE Ocorrencia_idOcorrencia = '$id' ");
    foreach($urls as $u){
        $url[] = $u->Url;
    }
    $detalhes = $wpdb->get_row("SELECT * FROM ocorrencia WHERE idOcorrencia = '$id' ");
    
    if( $wpdb->num_rows > 0 ){
        $resposta = array(
            "cod" => 0,
            "msg" => "Dados encontrados",
            "Descricao" => $detalhes->Descricao,
            "DescricaoCliente" => $detalhes->DescricaoCliente,
            "Vencimento" => $detalhes->Vencimento,
            "TempoPrevisto" => $detalhes->TempoPrevisto,
            "Urls" => $url
        );
    }else{
        $resposta = array(
            "cod" => 1,
            "msg" => "Dados nao encontrados!"
        );
    }

    echo json_encode($resposta);
?>