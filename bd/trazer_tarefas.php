<?php
    define('WP_USE_THEMES', false); 
    $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
    require_once( $parse_uri[0] . 'wp-load.php' );
    global $wpdb;

    $id = $_POST['user_id'];
    $contador = $_POST['contador'];
    $x = 0;

    $tarefas = $wpdb->get_results("SELECT * FROM ocorrencia WHERE Usuario_idUsuario = $id AND idOcorrencia > $contador");
    
    foreach($tarefas as $t){
        $descricao = $t->Descricao;
        $descricaoCliente = $t->DescricaoCliente;
        $vencimento = $t->Vencimento;
        $tempo = $t->TempoPrevisto;
        $prioridade = $t->OcorrenciaPrioridade_idOcorrenciaPrioridade;
        $area = $t->Area_idArea;
                
        $x++;
        $resposta[$x] = array(
            "Descricao" => $descricao,
            "DescricaoCliente" => $descricaoCliente,
            "Vencimento" => $vencimento,
            "TempoPrevisto" => $tempo,
            "Prioridade" => $prioridade,
            "Area" => $area
        );
    }

    echo json_encode($resposta);
?>