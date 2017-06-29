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

        case 'delete':
            $id = $_POST['id'];
            $wpdb->delete(
                "area",
                array(
                    "idArea" => $id
                )
            );
            $resposta = array(
                "cod" => 0,
                "msg" => "Dados Apagados com sucesso"
            );
        break;

        case 'detalhe':
            $id = $_POST['id'];
            $projeto = $wpdb->get_row("SELECT * FROM area WHERE idArea = $id ");

            $resposta = array(
                "cod" => 0,
                "msg" => "Dados do projeto",
                "projeto_id" => $projeto->idArea,
                "projeto_descricao" => $projeto->Descricao,
                "projeto_cor" => $projeto->cor
            );
        break;

        case 'update':
            $projeto_id = $_POST['projeto_id'];
            $descricao = $_POST['descricao'];
            $cor = $_POST['cor'];

            $wpdb->update(
                'area',
                array(
                    "Descricao" => $descricao,
                    "cor" => $cor
                ),
                array(
                    "idArea" => $projeto_id
                )
            );

            $resposta = array(
                "cod" => 0,
                "msg" => "Dados Atualizados",
            );
        break;

        default:
            $resposta = array(
                "cod" => 999,
                "msg" => "Nao foi possivel realizar sua solicitacao"
            );
        break;
    }

    echo json_encode($resposta);
?>
