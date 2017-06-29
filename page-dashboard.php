<?php
    /*
        Template name: Dashboard
    */
    if( $_COOKIE['id'] == "" ){
        $url = get_site_url() . "/" ;
        wp_redirect($url);
        exit;
    }
    $empresa_id = $wpdb->get_var("SELECT empresa_id FROM usuario WHERE post_id = ". $_COOKIE['id']);
    $projetos_cores = $wpdb->get_results("SELECT idArea, cor FROM area WHERE empresa_id = $empresa_id ");
    $pessoas_cores = $wpdb->get_results("SELECT post_id, cor FROM usuario WHERE empresa_id = $empresa_id ");
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

            function projeto_visualizacao(tipo){
                if(tipo == 'pessoa'){
                    jQuery(".btn-pessoa").addClass("btn-ativo").removeClass("btn-inativo");
                    jQuery(".btn-projeto").removeClass("btn-ativo").addClass("btn-inativo");
                    console.log(<?php echo json_encode($pessoas_cores); ?>);
                    jQuery.each( <?php echo json_encode($pessoas_cores); ?>, function(index, value){
                        console.log(value.post_id + "-" + value.cor);
                        jQuery("li.usuario-"+value.post_id).css("background", value.cor );
                    });
                }else{
                    jQuery(".btn-projeto").addClass("btn-ativo").removeClass("btn-inativo");
                    jQuery(".btn-pessoa").removeClass("btn-ativo").addClass("btn-inativo");
                    console.log(<?php echo json_encode($projetos_cores); ?>);
                    jQuery.each( <?php echo json_encode($projetos_cores); ?>, function(index, value){
                        console.log(value.idArea + "-" + value.cor);
                        jQuery("li.projeto-"+value.idArea).css("background", value.cor );
                    });
                }
            }

        </script>
    </head>
    <body onload="projeto_visualizacao('projeto');">
        <?php get_header('topo'); ?>
        <div class="row">
            <div class="col s12">
                <div class="row" id="page-dashboard">
                    <div class="col s12">
                        <div class="col s6 m3">
                            <h4 class="right">Visualizar por:</h4>
                        </div>
                        <div class="col s6">
                            <p>
                            <a href="#!" class="btn btn-ativo btn-projeto" onclick="projeto_visualizacao('projeto')">Projetos</a>
                            <a href="#!" class="btn btn-inativo btn-pessoa" onclick="projeto_visualizacao('pessoa')">Pessoas</a>
                            </p>
                        </div>
                    </div>
                    <div class="col s12 m10 offset-m1 black center white-text">
                        <div class="col s12 m3"> <p><b> Executar </b></p> </div>
                        <div class="col s12 m3"> <p><b> Executando </b></p> </div>
                        <div class="col s12 m3"> <p><b> Em Avaliação </b></p> </div>
                        <div class="col s12 m3"> <p><b> Executado </b></p> </div>
                    </div>

                    <div class="col s12 m10 offset-m1">
                        <div class="col s12 m3 bordered no-margin no-padding container-sortable">
                            <ul class="sortable l100 connectedSortable" data-areaStatus="1" id="executar_<?php echo $area_id; ?>">
                            <?php
                                //Definindo cores para prioridade
                                $prioridadeCor = array('#000','#01579b','#1b5e20','#d32f2f');
                                // Busca de todas as tarefas com status 1
                                $executar = $wpdb->get_results("
                                    SELECT o.idOcorrencia, o.Descricao as Titulo, o.Vencimento, o.OcorrenciaPrioridade_idOcorrenciaPrioridade, a.*, u.idUsuario, u.Login, u.post_id as usuario_post_id, u.cor as usuario_cor
                                    FROM ocorrencia o, area a, usuario u
                                    WHERE o.Area_idArea = a.idArea
                                    AND a.empresa_id = $empresa_id
                                    AND o.OcorrenciaStatus_idOcorrenciaStatus = 1
                                    AND o.Usuario_idUsuario = u.idUsuario
                                    AND o.Vencimento >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                                    ORDER BY o.OcorrenciaPrioridade_idOcorrenciaPrioridade DESC
                                ");

                                foreach ($executar as $ex ) {
                                    ?>
                                        <li id="ocorrenciaId_<?php echo $ex->idOcorrencia; ?>" data-ocorrenciaId="<?php echo $ex->idOcorrencia; ?>" onclick="ocorrencia_detalhe(<?php echo $ex->idOcorrencia; ?>)" class="draggable ui-state-default projeto-<?php echo $ex->idArea; ?> usuario-<?php echo $ex->usuario_post_id; ?>">
                                            <div class="col s9">
                                            <b><?php echo $ex->Titulo; ?></b>
                                            </div>
                                            <div class="col s3">
                                                <i class="material-icons right" style="color:<?php echo $prioridadeCor[$ex->OcorrenciaPrioridade_idOcorrenciaPrioridade]; ?>;">label</i>
                                            </div>
                                            <div class="col s12"><p></p></div>
                                            <div class="col s9">
                                                <?php echo $ex->Descricao; ?><br>
                                                <time > <?php echo date("d/m/Y", strtotime($ex->Vencimento)); ?> </time>
                                            </div>
                                            <div class="col s3">
                                                <?php
                                                    $url = get_the_post_thumbnail_url($ex->usuario_post_id, 'thumbnail');
                                                    if( $url != ''){
                                                        echo '<img src="'.$url.'" class="responsive-img circle">';
                                                    }else{ echo $ex->Login; }
                                                ?>

                                            </div>
                                        </li>
                                    <?php
                                }
                            ?>
                            </ul>
                        </div>
                        <div class="col s12 m3 bordered no-margin no-padding container-sortable">
                            <ul class="sortable l100 limite-1 connectedSortable" data-areaStatus="2" id="executando_<?php echo $area_id; ?>">
                                <?php
                                    $executando = $wpdb->get_results("
                                        SELECT o.idOcorrencia, o.Descricao as Titulo, o.Vencimento, o.OcorrenciaPrioridade_idOcorrenciaPrioridade, a.*, u.idUsuario, u.Login, u.post_id as usuario_post_id, u.cor as usuario_cor
                                        FROM ocorrencia o, area a, usuario u
                                        WHERE o.Area_idArea = a.idArea
                                        AND a.empresa_id = $empresa_id
                                        AND o.OcorrenciaStatus_idOcorrenciaStatus = 2
                                        AND o.Usuario_idUsuario = u.idUsuario
                                        AND o.Vencimento >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                                        ORDER BY o.OcorrenciaPrioridade_idOcorrenciaPrioridade DESC
                                    ");

                                    foreach ($executando as $exx ) {
                                        ?>
                                            <li id="ocorrenciaId_<?php echo $exx->idOcorrencia; ?>" data-ocorrenciaId="<?php echo $exx->idOcorrencia; ?>" onclick="ocorrencia_detalhe(<?php echo $exx->idOcorrencia; ?>)" class="draggable ui-state-default projeto-<?php echo $exx->idArea; ?> usuario-<?php echo $exx->usuario_post_id; ?>">
                                                <div class="col s9">
                                                    <b><?php echo $exx->Titulo; ?></b>
                                                </div>
                                                <div class="col s3">
                                                    <i class="material-icons right" style="color:<?php echo $prioridadeCor[$exx->OcorrenciaPrioridade_idOcorrenciaPrioridade]; ?>;">label</i>
                                                </div>
                                                <div class="col s12"><p></p></div>
                                                <div class="col s9">
                                                    <?php echo $exx->Descricao; ?><br>
                                                    <time > <?php echo date("d/m/Y", strtotime($exx->Vencimento));  ?> </time>
                                                </div>
                                                <div class="col s3">
                                                    <?php
                                                        $url = get_the_post_thumbnail_url($exx->usuario_post_id, 'thumbnail');
                                                        if( $url != ''){
                                                            echo '<img src="'.$url.'" class="responsive-img circle">';
                                                        }else{ echo $exx->Login; }
                                                    ?>

                                                </div>
                                            </li>
                                        <?php
                                    }
                                ?>
                            </ul>
                        </div>
                        <div class="col s12 m3 bordered no-margin no-padding container-sortable">
                            <ul class="sortable l100 connectedSortable" data-areaStatus="3" id="executado_<?php echo $area_id; ?>">
                                <?php
                                    $avaliacao = $wpdb->get_results("
                                        SELECT o.idOcorrencia, o.Descricao as Titulo, o.Vencimento, o.OcorrenciaPrioridade_idOcorrenciaPrioridade, a.*, u.idUsuario, u.Login, u.post_id as usuario_post_id, u.cor as usuario_cor
                                        FROM ocorrencia o, area a, usuario u
                                        WHERE o.Area_idArea = a.idArea
                                        AND a.empresa_id = $empresa_id
                                        AND o.OcorrenciaStatus_idOcorrenciaStatus = 3
                                        AND o.Usuario_idUsuario = u.idUsuario
                                        AND o.Vencimento >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                                        ORDER BY o.OcorrenciaPrioridade_idOcorrenciaPrioridade DESC
                                    ");

                                    foreach ($avaliacao as $a ) {
                                        ?>
                                            <li id="ocorrenciaId_<?php echo $a->idOcorrencia; ?>" data-ocorrenciaId="<?php echo $a->idOcorrencia; ?>" onclick="ocorrencia_detalhe(<?php echo $a->idOcorrencia; ?>)" class="draggable ui-state-default projeto-<?php echo $a->idArea; ?> usuario-<?php echo $a->usuario_post_id; ?>">
                                                <div class="col s9">
                                                    <b><?php echo $a->Titulo; ?></b>
                                                </div>
                                                <div class="col s3">
                                                    <i class="material-icons right" style="color:<?php echo $prioridadeCor[$a->OcorrenciaPrioridade_idOcorrenciaPrioridade]; ?>;">label</i>
                                                </div>
                                                <div class="col s12"><p></p></div>
                                                <div class="col s9">
                                                    <?php echo $a->Descricao; ?><br>
                                                    <time > <?php echo date("d/m/Y", strtotime($a->Vencimento));  ?> </time>
                                                </div>
                                                <div class="col s3">
                                                    <?php
                                                        $url = get_the_post_thumbnail_url($a->usuario_post_id, 'thumbnail');
                                                        if( $url != ''){
                                                            echo '<img src="'.$url.'" class="responsive-img circle">';
                                                        }else{ echo $a->Login; }
                                                    ?>

                                                </div>
                                            </li>
                                        <?php
                                    }
                                ?>
                            </ul>
                        </div>
                        <div class="col s12 m3 bordered no-margin no-padding container-sortable">
                            <ul class="sortable l100 connectedSortable" data-areaStatus="4" id="executado_<?php echo $area_id; ?>">
                                <?php
                                    $executado = $wpdb->get_results("
                                        SELECT o.idOcorrencia, o.Descricao as Titulo, o.Vencimento, o.OcorrenciaPrioridade_idOcorrenciaPrioridade, a.*, u.idUsuario, u.Login, u.post_id as usuario_post_id, u.cor as usuario_cor
                                        FROM ocorrencia o, area a, usuario u
                                        WHERE o.Area_idArea = a.idArea
                                        AND a.empresa_id = $empresa_id
                                        AND o.OcorrenciaStatus_idOcorrenciaStatus = 4
                                        AND o.Usuario_idUsuario = u.idUsuario
                                        AND o.Vencimento >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                                        ORDER BY o.OcorrenciaPrioridade_idOcorrenciaPrioridade DESC
                                        LIMIT 30
                                    ");

                                    foreach ($executado as $exxx ) {
                                        ?>
                                            <li id="ocorrenciaId_<?php echo $exxx->idOcorrencia; ?>" data-ocorrenciaId="<?php echo $exxx->idOcorrencia; ?>" onclick="ocorrencia_detalhe(<?php echo $exxx->idOcorrencia; ?>)" class="draggable ui-state-default projeto-<?php echo $a->idArea; ?> usuario-<?php echo $a->usuario_post_id; ?>">
                                                <div class="col s9">
                                                    <b><?php echo $exxx->Titulo; ?></b>
                                                </div>
                                                <div class="col s3">
                                                    <i class="material-icons right" style="color:<?php echo $prioridadeCor[$exxx->OcorrenciaPrioridade_idOcorrenciaPrioridade]; ?>;">label</i>
                                                </div>
                                                <div class="col s12"><p></p></div>
                                                <div class="col s9">
                                                    <?php echo $exxx->Descricao; ?><br>
                                                    <time > <?php echo date("d/m/Y", strtotime($exxx->Vencimento));  ?> </time>
                                                </div>
                                                <div class="col s3">
                                                    <?php
                                                        $url = get_the_post_thumbnail_url($exxx->usuario_post_id, 'thumbnail');
                                                        if( $url != ''){
                                                            echo '<img src="'.$url.'" class="responsive-img circle">';
                                                        }else{ echo $exxx->Login; }
                                                    ?>

                                                </div>
                                            </li>
                                        <?php
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
        </div>

        <!-- MODAL ESTA FORA DO page-dashboard -->
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
                                $areas = $wpdb->get_results("SELECT * FROM area WHERE empresa_id = $empresa_id ");
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
