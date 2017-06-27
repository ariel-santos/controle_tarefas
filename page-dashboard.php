<?php
    /*
        Template name: Dashboard
    */
    if( $_COOKIE['id'] == "" ){
        $url = get_site_url() . "/login" ;
        wp_redirect($url);
        exit;
    }
?>
<html>
    <head>
        <?php get_header('includes'); ?>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
            jQuery( function(){
                // funcao para container de ocorrencia
                jQuery( ".sortable" ).sortable({
                    revert: true,
                    placeholder: "grey",
                    connectWith: ".connectedSortable",
                    // funcao para contador de nova ocorrencia
                    stop: function(){
                        id = jQuery(this).attr("id");
                        area = id.split('_');
                        n = +jQuery("span#badge_"+area[1]+" b").text();
                        if( area[0] == 'executando' ){
                            n = n - 1;
                            if( n == 0 ){
                                jQuery("span#badge_"+area[1]).addClass("hide");
                            }
                            jQuery("span#badge_"+area[1]+" b").text(n);
                        }
                        if( area[0] == 'executar' ){
                            n = n + 1;
                            if( n > 0 ){
                                jQuery("span#badge_"+area[1]).removeClass("hide");
                            }
                            jQuery("span#badge_"+area[1]+" b").text(n);
                        }

                    }
                });
                // 'arraastar' ocorrencia
                jQuery( ".draggable" ).draggable({
                    connectToSortable: ".sortable",
                    revert: "invalid",
                    stop: function(){
                        id = jQuery(this).attr("data-ocorrenciaId");
                        var status = jQuery(this).closest('ul').attr('data-areaStatus');
                        ocorrencia_muda_status(id, status);
                        operacao(id, status);
                    }
                });
                jQuery( "ul, li" ).disableSelection();
                atualiza();
            });

            // recarregar pagina a cada 1hora
            function atualiza(){
                setInterval(function(){
                    location.reload();
                }, 300000);
            }

            function operacao(id, status){
                url = "<?php echo get_template_directory_uri(); ?>/bd/operacao.php";
                jQuery.post(url, {id: id, status: status}, function(data){
                    console.log(data);
                }, "json");
            }

            // alterar status de ocorrencia
            function ocorrencia_muda_status(id, status){
                url = "<?php echo get_template_directory_uri(); ?>/bd/ocorrencia_altera_status.php";
                jQuery.post(url, {id: id, status: status}, function(data){
                    console.log(data);
                }, "json");
            }
            // cadastro de ocorrencia
            function ocorrencia_cadastrar(){
                dados = jQuery("form#fm_ocorrencia").serialize();
                jQuery.post("<?php echo get_template_directory_uri(); ?>/bd/cadastra_tarefa.php", dados, function(data){
                    console.log(data);
                }, "json");

                titulo = jQuery("input#descricao").val();
                jQuery("#modal_add_tarefa").modal("close");

                jQuery("td:first-child ul.sortable").append('<li class="draggable ui-state-default">'+titulo+'</li>');
                jQuery(".sortable").sortable('refresh');
            }


            function pesquisar_tarefas(){
                contador = jQuery("input#contador_tarefas").val();
                jQuery.post("<?php echo get_template_directory_uri(); ?>/bd/trazer_tarefas.php", {user_id:1, contador: contador}, function(data){
                    console.log(data);
                    jQuery.each(data, function(i, item){
                        jQuery("tr.area_"+item.Area+" td:first-child ul.sortable")
                            .append('<li class="draggable ui-state-default">'+item.Descricao+'</li>');

                        console.log("Area: " + item.Area + " \n Descricao: " + item.Descricao );
                    });
                }, "json");
            }

            // buscar dados da ocorrencia
            function ocorrencia_detalhe(id){
                url = "<?php echo get_template_directory_uri(); ?>/bd/ocorrencia_detalhe.php";
                jQuery("#modal_tarefa_detalhes #container_img").empty();

                jQuery.post(url, {id: id}, function(data){
//                    console.log(data);
                    jQuery("#modal_tarefa_detalhes label").addClass("active");
                    jQuery("#modal_tarefa_detalhes input#mtd_ocorrencia_id").val(data.idOcorrencia);
                    jQuery("#modal_tarefa_detalhes input#mtd_descricao").val(data.Descricao);
                    jQuery("#modal_tarefa_detalhes textarea#mtd_descricao_cliente").val(data.DescricaoCliente);
                    jQuery("#modal_tarefa_detalhes input#mtd_tempo").val(data.TempoPrevisto);
                    jQuery("#modal_tarefa_detalhes input#mtd_vencimento").val(data.Vencimento);
                    jQuery("#modal_tarefa_detalhes #mtd_area").val(data.area);
                    jQuery("#modal_tarefa_detalhes #mtd_prioridade").val(data.prioridade);
                    if(data.Urls != null){
                        data.Urls.forEach(function(valor, chave){
                            jQuery("#modal_tarefa_detalhes #container_img").append("<div class='col s12 m3'><img class='responsive-img pointer' src='"+valor+"'></div>");
                            jQuery("#modal_tarefa_detalhes #container_img img:last-child").attr("onclick","window.open('"+valor+"', '_blank')");
                        });
                    }
                }, "json");
                jQuery('#modal_tarefa_detalhes').modal('open');
            }

            function ocorrencia_update(){
                url = "<?php echo get_template_directory_uri(); ?>/bd/ocorrencia_detalhe_update.php";
                dados = jQuery("#modal_tarefa_detalhes form#fm_mtd").serialize();
                jQuery.post(url, dados, function(data){
                    // console.log(data);
                    setTimeout(function(){
                        location.reload();
                    }, 500);
                }, "json");
            }
        </script>
        <style>
            .draggable{  border:1px solid silver; width: 50%; min-height: 1px; height: auto; padding: 0.5em; float: left; margin: 10px 10px 10px 0; color:white; cursor: pointer; }
/*            #page-dashboard table tr td ul.sortable{ width: 48%; margin: 0 1%; padding: 0;  float: left;}*/
            ul.sortable{ width:48%; margin: 0 1%; padding: 0;  float: left; min-height: 150px; }
            ul.l100{ width:98%; margin: 0 1%; padding: 0;  float: left; min-height: 150px;}
            ul.sortable li{padding: 10px; border-radius: 5px; width: 96%; margin: 10px 2%;}
        </style>
    </head>
    <body>
        <?php get_header('topo'); ?>
        <div class="row">
            <div class="col s12">
                <div class="row container" id="page-dashboard">
                    <div class="row black center white-text">
                        <div class="col s12 m4"> <p><b> Executar </b></p> </div>
                        <div class="col s12 m4"> <p><b> Executando </b></p> </div>
                        <div class="col s12 m4"> <p><b> Executado </b></p> </div>
                    </div>
                    <div class="row">
                        <ul class="collapsible" data-collapsible="accordion">
                            <?php
                                    $user_id = $_COOKIE['id'];

                                    $areas = $wpdb->get_results("
                                        SELECT Area_idArea, a.Descricao
                                        FROM ocorrencia o, area a
                                        WHERE Usuario_idUsuario = '$user_id'
                                        AND o.Area_idArea = a.idArea
                                        GROUP BY Area_idArea
                                    ");

                                    foreach($areas as $a){
                                        $area_id = $a->Area_idArea;

                                        // Loop para organizar ocorrencias
                                        $tarefas = $wpdb->get_results("
                                            SELECT o.*, op.Cor
                                            FROM ocorrencia o, ocorrenciaprioridade op
                                            WHERE o.Area_idArea = '$area_id'
                                            AND o.Usuario_idUsuario = '$user_id'
                                            AND o.OcorrenciaPrioridade_idOcorrenciaPrioridade = op.idOcorrenciaPrioridade
                                            AND o.Vencimento >= DATE_SUB(CURDATE(), INTERVAL 20 DAY)
                                        ");

                                        foreach( $tarefas as $t ){
                                            switch ($t->OcorrenciaStatus_idOcorrenciaStatus) {
                                                case 1:
                                                    $executar[] = array(
                                                        "id" => $t->idOcorrencia,
                                                        "descricao" => $t->Descricao,
                                                        "cor" => $t->Cor
                                                    );
                                                    break;
                                                case 2:
                                                    $executando[] = array(
                                                        "id" => $t->idOcorrencia,
                                                        "descricao" => $t->Descricao,
                                                        "cor" => $t->Cor
                                                    );
                                                    break;
                                                case 3:
                                                    $executado[] = array(
                                                        "id" => $t->idOcorrencia,
                                                        "descricao" => $t->Descricao,
                                                        "cor" => $t->Cor
                                                    );
                                                    break;
                                            }
                                        }
                                    ?>
                                    <li>
                                        <div class="collapsible-header">
                                            <i class="material-icons">swap_vert</i>
                                            <h5>
                                                <?php
                                                    $cont = count($executar);
                                                    if( $cont > 0 ){ $esconde = ""; }else{ $esconde = "hide"; }
                                                ?>
                                                <span class="badge black white-text <?php echo $esconde; ?>" id="badge_<?php echo $area_id; ?>"> + <b><?php echo $cont; ?></b> Oc. </span>
                                                <?php echo $a->Descricao; ?>
                                            </h5>
                                        </div>
                                        <div class="collapsible-body area_<?php echo $area_id; ?>">
                                            <div class="col s12">
                                                <div class="col s12 m4 bordered no-margin no-padding">
                                                    <ul class="sortable l100 connectedSortable" data-areaStatus="1" id="executar_<?php echo $area_id; ?>">
                                                        <?php
                                                            foreach ($executar as $i => $e) {
                                                                ?>
                                                                <li id="ocorrenciaId_<?php echo $e['id']; ?>" data-ocorrenciaId="<?php echo $e['id']; ?>" onclick="ocorrencia_detalhe(<?php echo $e['id']; ?>)" style="background:<?php echo $e['cor']; ?>;" class="draggable ui-state-default"><?php echo $e['descricao']; ?></li>
                                                                <?php
                                                            }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <div class="col s12 m4 bordered no-margin no-padding">
                                                    <ul class="sortable l100 limite-1 connectedSortable" data-areaStatus="2" id="executando_<?php echo $area_id; ?>">
                                                        <?php
                                                            foreach ($executando as $i => $e) {
                                                                ?>
                                                                <li id="ocorrenciaId_<?php echo $e['id']; ?>" data-ocorrenciaId="<?php echo $e['id']; ?>" onclick="ocorrencia_detalhe(<?php echo $e['id']; ?>)" style="background:<?php echo $e['cor']; ?>;" class="draggable ui-state-default"><?php echo $e['descricao']; ?></li>
                                                                <?php
                                                            }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <div class="col s12 m4 bordered no-margin no-padding">
                                                    <ul class="sortable l100 connectedSortable" data-areaStatus="3" id="executado_<?php echo $area_id; ?>">
                                                        <?php
                                                            foreach ($executado as $i => $e) {
                                                                ?>
                                                                <li id="ocorrenciaId_<?php echo $e['id']; ?>" data-ocorrenciaId="<?php echo $e['id']; ?>" onclick="ocorrencia_detalhe(<?php echo $e['id']; ?>)" style="background:<?php echo $e['cor']; ?>;" class="draggable ui-state-default"><?php echo $e['descricao']; ?></li>
                                                                <?php
                                                            }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php
                                        $executando = array();
                                        $executado = array();
                                        $executar = array();
                                    }
                                ?>
                        </ul>
                    </div>
                        <input type="hidden" name="contador_tarefas" id="contador_tarefas" value="4">
                    </div>
                </div>
            </div>

            <div class="hide col s12 m5 container-pesquisa z-depth-3">
                <div class="acao-icone">
                    <i class="material-icons">close</i>
                </div>
                <nav class="col s12 black">
                <form>
                    <div class="input-field">
                        <input id="search" type="search" required>
                        <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                        <i class="material-icons">close</i>
                    </div>
                </form>
                </nav>
                <div class="col s12 container-pesquisa-resultado">

                </div>
            </div>

        <div id="modal_tarefa_detalhes" class="modal">
            <div class="modal-content" style="padding-bottom:0 ;">
                 <div class="row center">
                    <form id="fm_mtd" method="post">
                    <input type="hidden" name="mtd_ocorrencia_id" id="mtd_ocorrencia_id">
                    <h4 class="no-margin" style="margin:0;">Detalhes da Ocorrencia</h4>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="mtd_descricao" name="mtd_descricao" type="text">
                            <label for="descricao">Descricao</label>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="mtd_descricao_cliente" name="mtd_descricao_cliente" class="materialize-textarea"></textarea>
                                <label for="descricao_cliente">Descricao Cliente</label>
                            </div>
                        </div>
                        <div class="input-field col s12 m6">
                            <input id="mtd_tempo" name="mtd_tempo" type="text">
                            <label for="tempo">Tempo Previsto</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input id="mtd_vencimento" name="mtd_vencimento" type="date">
                            <label for="vencimento" class="active">Vencimento</label>
                        </div>
                    </div>
                    <div class="input-field col s12 m3">
                        <select class="browser-default" name="mtd_prioridade" id="mtd_prioridade">
                            <option value="2">Normal</option>
                            <option value="3">Alta</option>
                            <option value="1">Baixa</option>
                        </select>
                        <label for="prioridade">Prioridade</label>
                    </div>
                    <div class="input-field col s12 m5">
                        <select class="browser-default" name="mtd_area" id="mtd_area">
                            <?php
                                $areas = $wpdb->get_results("SELECT * FROM area");
                                foreach( $areas as $a ){
                            ?>
                                <option value="<?php echo $a->idArea; ?>"><?php echo $a->Descricao; ?> </option>
                            <?php
                                }
                            ?>
                        </select>
                        <label for="area">Projeto</label>
                    </div>
                    <div class="col s12 m4">
                        <a href="#!" class="btn black right"onclick="ocorrencia_update()">Salvar Alterações</a>
                    </div>
                </form>
                </div>
                <div class="row" id="container_img"></div>
            </div>
        </div>

        <?php
            get_footer();
        ?>

    </body>
</html>
