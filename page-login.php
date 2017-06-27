<?php
    /*
        Template name: Login
    */
?>
<html>
    <head>
        <?php get_header('includes'); ?>
        <script>
            function verificar_acesso(){
                dados = jQuery('form#fm_acesso').serialize();
            
                jQuery.post("<?php echo get_template_directory_uri(); ?>/bd/acesso.php", dados, function(data){
                    jQuery("span#resposta").html(data.msg);
                    if(data.cod == 0){
                        setTimeout(function(){
                            window.location.href = "/dashboard/";    
                        }, 500);
                    }
                }, "json");
            }
            
            function mostra_fm(mostra, esconde){
                jQuery(mostra).removeClass("hide");
                jQuery(esconde).addClass("hide");
            }
            
            function cadastrar_usuario(){
                dados = jQuery("#fm_cadastro").serialize();
                jQuery.post("<?php echo get_template_directory_uri(); ?>/bd/cadastra_usuario.php", dados, function(data){
                    console.log(data);
                    if( data.cod == 0 ){
                        location.href = '/dashboard';
                    }
                    jQuery("span#resposta").html(data.msg);
                }, "json");
            }
        </script>
    </head>
    <body>
        <?php get_header('topo'); 
//        print_r($_COOKIE);
?>
        <div class="row center container">
            <div class="col s12" style="padding:20px;">
                <a href="#!" class="btn black" onclick="mostra_fm('#container-acesso', '#container-cadastro')"> Acessar </a>
                <a href="#!" class="btn black" onclick="mostra_fm('#container-cadastro', '#container-acesso')"> Cadastrar </a>
            </div>
            <div class="col s12 center">
                <span id="resposta"></span>
            </div>
            <div class="col s12 m6 offset-m3" id="container-acesso">
                <div class="row">
                    <div class="col s12">
                        <h4>Acesso</h4>
                    </div>
                </div>   
                
                <form method="post" id="fm_acesso" name="fm_acesso" action="<?php echo get_site_url(); ?>/dashboard/">
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="user" name="user" type="text">
                            <label for="user"> Usuario </label>
                        </div>
                        <div class="input-field col s12">
                            <input id="senha" name="senha" type="password" >
                            <label for="senha">Senha</label>
                        </div>
                    </div>
                </form>
                 <div class="row center">
                    <div class="col s12">
                        <button class="btn black" onclick="verificar_acesso()">ACESSAR</button>
                    </div>
                </div>

                    <?php
                        echo $_COOKIE["login"];
                        echo $_COOKIE["id"];
                    ?>
                </div>
                
                <div class="col s12 m6 offset-m3 hide" id="container-cadastro">
                    <div class="row">
                        <div class="col s12">
                            <h4>Cadastro</h4>
                        </div>
                    </div>
                    <form method="post" id="fm_cadastro" name="fm_cadastro" action="<?php echo get_site_url(); ?>/dashboard/">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="c_nome" name="c_nome" type="text">
                                <label for="c_nome"> Nome </label>
                            </div>
                            <div class="input-field col s12">
                                <input id="c_login" name="c_login" type="text">
                                <label for="c_login"> Login </label>
                            </div>
                            <div class="input-field col s12">
                                <input id="c_senha" name="c_senha" type="password" >
                                <label for="c_senha">Senha</label>
                            </div>
                        </div>
                    </form>    
                    <div class="row center">
                        <div class="col s12">
                            <a class="btn black" onclick="cadastrar_usuario()">CADASTRAR</a>
                        </div>
                    </div>
                </div>
            </div>
        
        <?php get_footer();?>
    </body>
</html>

