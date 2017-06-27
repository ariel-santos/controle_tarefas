<?php
    define('WP_USE_THEMES', false);
    $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
    require_once( $parse_uri[0] . 'wp-load.php' );
    global $wpdb;

    $acao = $_POST['acao'];

    switch ($acao) {
        case 'cadastrar':
            $descricao = $_POST['descricao'];
            $cor = $_POST['cor'];
            $empresa_id = $_POST['empresa_id'];

            $wpdb->insert(
                "area",
                array(
                    "Descricao" => $descricao,
                    "cor" => $cor,
                    "empresa_id" => $empresa_id
                )
            );

            $resposta = array(
                "cod" => 0,
                "msg" => "Dados cadastrados com sucesso"
            );

        break;

        default:
            $resposta = array(
                "cod" => 999,
                "msg" => "Nao foi possivel realizar sua solicitacao"
            );
        break;
    }
    $resposta['acao'] = $acao;
    echo json_encode($resposta);
?>
