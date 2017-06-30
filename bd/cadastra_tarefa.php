<?php
    define('WP_USE_THEMES', false);
    $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
    require_once( $parse_uri[0] . 'wp-load.php' );
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }
    header('Content-Type: text/html; charset=utf-8');

    global $wpdb;

    $descricao = $_REQUEST['descricao'];
    $descricao_cli = $_REQUEST['descricao_cliente'];
    $user_id = $_REQUEST['id_user'];
    $tempo = $_REQUEST['tempo'];
    $vencimento = $_REQUEST['vencimento'];
    $prioridade = $_REQUEST['prioridade'];
    $projeto = $_REQUEST['area'];
    $responsavel_id = $_REQUEST['responsavel_id'];

    $files = $_FILES['file'];
    $upload_overrides = array( 'test_form' => false );

    $wpdb->insert(
        'ocorrencia',
        array(
            'Descricao' => $descricao,
            'DescricaoCliente' => htmlspecialchars($descricao_cli, ENT_NOQUOTES, "UTF-8"),
            'Vencimento' => $vencimento,
            'TempoPrevisto' => $tempo,
            'OcorrenciaPrioridade_idOcorrenciaPrioridade' => $prioridade,
            'OcorrenciaStatus_idOcorrenciaStatus' => '1',
            'Area_idArea' => $projeto,
            'Usuario_idUsuario' => $user_id,
            'Responsavel_id' => $responsavel_id
        )
    );

    $ocorrencia_id = $wpdb->insert_id;

    foreach( $files['name'] as $key => $value ){
        if( $files['name'][$key] ){
            $file = array(
                'name'     => $files['name'][$key],
                'type'     => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error'    => $files['error'][$key],
                'size'     => $files['size'][$key]
            );
            $movefile = wp_handle_upload( $file, $upload_overrides );
            if ( $movefile && ! isset( $movefile['error'] )){
                $wpdb->insert(
                    'ocorrenciaanexo',
                    array(
                        'Url' => htmlentities($movefile['url']),
                        'Ocorrencia_idOcorrencia' => $ocorrencia_id
                    )
                );
                $resposta_wp[] = "Arquivo $key enviado";
            }else{
                $resposta_wp[] = $movefile['error'];
            }
        }
    }

    if( $ocorrencia_id != NULL ){
        $resposta = array(
            "cod" => 0,
            "msg" => "Tarefa cadastrada com sucesso !",
            "resposta-wp" => $resposta_wp
        );
    }else{
        $resposta = array(
            "cod" => 1,
            "msg" => "Não foi possivel realizar o cadastro! ",
            "resposta-wp" => $resposta_wp
        );
    }

    echo json_encode($resposta);
?>
