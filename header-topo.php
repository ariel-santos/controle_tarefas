<div class="row black">
    <div class="col s12 m4">
        <h4 class="blue-text darken-4"> <?php echo get_bloginfo(); ?></h4>
    </div>
    <div class="col s12 m4" id="container-usuario">
        <?php
            if( $_COOKIE["login"] != ""){
                $usuario = $wpdb->get_row("SELECT * FROM usuario WHERE post_id = " . $_COOKIE['id'] );
                $empresa_id = $usuario->empresa_id;
            ?>
                <div class="col s12 white-text">
                    Usuario: <b> <?php echo $usuario->Login; ?> </b> <br>
                </div>
                <div class="col s12 m6">
                    <a href="#modal-usuario-dados"> Editar dados </a>
                </div>
                <div class="col s12 m6">
                    <a href="#!" onclick="sair();"> Sair </a>
                </div>
            <?php
            }
        ?>

    </div>
    <div class="col s12 m4">
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

                function modal_usuario_atualizar(){
                    dados = jQuery("#modal-usuario-dados form").serialize();
                    url = "<?php echo get_template_directory_uri(); ?>/usuario/atualiza_dados.php";
                    jQuery.post(url, dados, function(data){
                        console.log(data);
                        jQuery("#modal-usuario-dados p.msg").html(data.msg);
                        if( 0 == data.cod ){
                            setTimeout(function(){
                                location.reload();
                            }, 1500);
                        }
                    }, "json");
                }
            </script>

            <p class="col s12 m6"><a class="btn blue darken-4 right hide"  href="#modal_area"> +Projeto </a></p>
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

            <p class="col s12 m6"> <a class="btn blue darken-4 right"  href="#modal_tarefa"> +Tarefa </a> </p>
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
                                                $areas = $wpdb->get_results("SELECT * FROM area WHERE empresa_id = $empresa_id" );
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
                                                $users = $wpdb->get_results("SELECT * FROM usuario WHERE empresa_id = $empresa_id");
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

            <!-- Modal para editar dados do usuario -->
            <div id="modal-usuario-dados" class="modal">
                <div class="row modal-content">
                    <form class="col s12 m8 offset-m2">
                        <input type="hidden" name="id" id="id" value="<?php echo $usuario->post_id; ?>">
                        <h4 class="center"><?php echo get_bloginfo(); ?></h4>
                        <h5 class="center">Dados do Usuario </h5>
                        <p class="msg center"></p>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">account_circle</i>
                            <input id="login" type="text" name="login" value="<?php echo $usuario->Login; ?>">
                            <label for="login">Login </label>
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">account_circle</i>
                            <input id="email" type="text" name="email" value="<?php echo $usuario->email; ?>">
                            <label for="email">Email </label>
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">lock</i>
                            <input id="senha" type="password" name="senha" placeholder="Sua senha sera criptografada ">
                            <label for="senha">Senha </label>
                        </div>
                        <div class="row center">
                            <div class="input-field col s12 center">
                                <a href="#!" onclick="modal_usuario_atualizar()" class="btn black center"> Atualizar </a>
                            </div>
                        </div>
                        <div class="input-field col s12 center">
                            <p></p>
                        </div>
                    </form>
                </div>
            </div>

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
