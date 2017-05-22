<div class="row grey">
    <div class="col s12 m4">
        <h4> Controle de tarefas </h4>
    </div>
    <div class="col s12 m4" id="container-usuario">
        <?php 
            if( $_COOKIE["login"] != ""){
            ?>
                <p> 
                    User: <b> <?php echo $_COOKIE["login"]; ?> </b> <br>
                    <a href="#!" onclick="sair();"> Sair</a>
                </p>
            <?php
            }
        ?>
        
    </div>
    <div class="col s12 m4">
        <?php 
            $permissao = $wpdb->get_var("SELECT Administrador FROM usuario WHERE idUsuario = " . $_COOKIE["id"]);
            if($permissao == 's'){
        ?>
            <script>
                function add_projeto(){
                    descricao = jQuery("input#descricao").val();
                    jQuery.post("<?php echo get_template_directory_uri();?>/bd/cadastra_projeto.php", {descricao: descricao}, function(data){
                        jQuery("span#resposta").html(data.msg); 
                        if(data.cod == 0 ){
                            setTimeout(function(){
                                jQuery('#modal_area').modal('close');
                                location.reload();
                            },2000);
                        }
                    }, "json");
                }
                
                
                function abrir_media(){
                    var custom_uploader;
                    
                    if (custom_uploader) {
                        custom_uploader.open();
                        return;
                    }

                    custom_uploader = wp.media.frames.file_frame = wp.media({
                        title: 'Choose Image',
                        button: {
                            text: 'Choose Image'
                        },
                        multiple: true
                    });

                    custom_uploader.on('select', function() {
                        console.log(custom_uploader.state().get('selection').toJSON());
                        attachment = custom_uploader.state().get('selection').first().toJSON();
                        $('#upload_image').val(attachment.url);
                    });
                    custom_uploader.open();
                }
            </script>       
            <a class="btn black right"  href="#modal_area"> +Projeto </a>
                
            <div id="modal_area" class="modal">
                <div class="modal-content">
                     <div class="row center">
                        <h4>Cadastro de Pojeto</h4>   
                        <span id="resposta"></span>
                        <form class="col s12">
                            <div class="row center">
                                <div class="input-field col s12">
                                  <input id="descricao" name="descricao" type="text" class="validate">
                                  <label for="descricao">Descricao</label>
                                </div>
                            </div>
                        </form>
                        <div class="row center">
                            <button class="black btn" onclick="add_projeto()">Cadastrar</button>
                        </div>    
                    </div>
                </div>
            </div>
            
            <a class="btn black right"  href="#modal_tarefa"> +Tarefa </a>
                
            <div id="modal_tarefa" class="modal">
                <div class="modal-content" style="padding-bottom:0 ;">
                     <div class="row center">
                        <h4 class="no-margin" style="margin:0;">Cadastro de Ocorrencia</h4>   
                        <span id="resposta"></span>
                        <form name="fm_add_tarefa" id="fm_add_tarefa" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="descricao" name="descricao" type="text">
                                    <label for="descricao">Descricao</label>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <textarea id="descricao_cliente" name="descricao_cliente" class="materialize-textarea"></textarea>
                                        <label for="descricao_cliente">Descricao Cliente</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12 m3">
                                        <select name="prioridade" id="prioridade">
                                            <option value="2">Normal</option>
                                            <option value="3">Alta</option>
                                            <option value="1">Baixa</option>
                                        </select>   
                                        <label for="prioridade">Prioridade</label>
                                    </div>
                                    <div class="input-field col s12 m5">
                                        <select name="area" id="area">
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
                                    <div class="input-field col s12 m4">
                                        <select name="id_user" id="id_user">
                                            <?php 
                                                $users = $wpdb->get_results("SELECT * FROM usuario");
                                                foreach( $users as $u ){
                                            ?>
                                                <option value="<?php echo $u->idUsuario; ?>"><?php echo $u->Nome; ?> </option>
                                            <?php
                                                }
                                            ?>
                                        </select>   
                                        <label for="id_user">Usuario</label>
                                    </div>
                                </div>
                                <div class="row">
                                    
                                
                                    <div class="input-field col s12 m3">
                                        <input id="tempo" name="tempo" type="text">
                                        <label for="tempo">Tempo Previsto(hrs)</label>
                                    </div>
                                    <div class="input-field col s12 m4">
                                        <input id="vencimento" name="vencimento" type="date" value="<?php echo date("Y-m-d"); ?>">
                                        <label for="vencimento" class="active">Vencimento</label>
                                    </div>
                                    <div class="file-field input-field col s12 m5">
                                        <div class="btn blue darken-2">
                                            <i class="material-icons">system_update_alt</i>
                                            <input type="file" name="input_wp_media" id="input_wp_media" size="50" multiple> 
                                        </div>
                                        <div class="file-path-wrapper">
                                            <input class="file-path validate" type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="row center no-margin" style="margin:0px;">
                                <button class="black btn">Cadastrar</button>
                            </div>  
                        </form>
                          
                    </div>
                </div>
            </div>
            
            
        <?php } ?>
    </div>
</div> 
<!--
<div class="row red">
   <div class="col s12">
    <p class="white-text">    
        Manutencao do cadastro de tarefas, temporariamente nao estamos salvando tarefas
    </p>
    </div>
</div>-->
