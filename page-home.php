<?php
    /*
        Template name: Pagina Home
    */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
    <head>
        <?php get_header('includes'); ?>
        <script>
            jQuery(document).ready(function(){
                jQuery('.modal').modal();
            });

            function modal_acesso(){
                dados = jQuery("#modal-acesso form").serialize();
                url = "<?php echo get_template_directory_uri(); ?>/usuario/acesso.php";
                console.log(dados);
                console.log(url);
                jQuery.post(url, dados, function(data){
                    console.log(data);
                    if(data.cod == 0){
                        jQuery("#modal-acesso form p.msg").html(data.msg);
                        setTimeout(function(){
                            window.location.href = "/dashboard";
                        }, 1000)
                    }else{
                        jQuery("#modal-acesso form input").val("");
                        jQuery("#modal-acesso form p.msg").val(data.msg);
                    }
                }, "json");
            }
        </script>
    </head>
        <?php get_header('topo-site'); ?>
        <div class="row center">
            <div class="col s12">
                <h2> Página Home </h2>
            </div>
        </div>
        <?php get_footer(); ?>
    </body>
</html>
