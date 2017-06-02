<?php
    define('WP_USE_THEMES', false);
    $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
    require_once( $parse_uri[0] . 'wp-load.php' );
    global $wpdb;

    $id = $_POST['mtd_ocorrencia_id'];
    $descricao = $_POST['mtd_descricao'];
    $descricao_cliente = $_POST['mtd_descricao_cliente'];
    $tempo = $_POST['mtd_tempo'];
    $vencimento = $_POST['mtd_vencimento'];
    $prioridade = $_POST['mtd_prioridade'];
    $area = $_POST['mtd_area'];

    $wpdb->update(
        'ocorrencia',
        array(
            'Descricao' => $descricao,
            'DescricaoCliente' => htmlspecialchars($descricao_cliente, ENT_NOQUOTES, "UTF-8"),
            'Vencimento' => $vencimento,
            'TempoPrevisto' => $tempo,
            'OcorrenciaPrioridade_idOcorrenciaPrioridade' => $prioridade,
            'Area_idArea' => $area,
        ), array(
            "idOcorrencia" => $id
        )
    );

    $resposta = array(
        "cod" => 0,
        "msg" => " Atualização realiza com sucesso! "
    );

    echo json_encode($resposta);
?>
