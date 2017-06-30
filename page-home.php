<?php
    /*
        Template name: Pagina Home
    */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
    <head>
        <?php get_header('includes'); ?>
        <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" ></script>
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

            /* OneSignal app para notificacao  */
            // Documentacao https://documentation.onesignal.com/docs/web-push-sdk
            var OneSignal = OneSignal || [];
            /* Ariel */
            // OneSignal.push(["init", {
            //     appId: "9e54dba2-abf1-4559-bee6-071306e44073",
            //     subdomainName: "https://talktome.os.tc",
            //     autoRegister: true,
            //     httpPermissionRequest: {
            //         enable: true
            //     },
            //     notifyButton: {
            //         enable: true
            //     }
            // }]);

            /* Acao sistemas  */
            OneSignal.push(["init", {
                appId: "b4448194-7efe-46e8-b86a-618483f04f28",
                subdomainName: "https://teamtasks.os.tc",
                autoRegister: true,
                httpPermissionRequest: {
                    enable: true
                },
                notifyButton: {
                    enable: true
                }
           }]);

        //    OneSignal.push(function() {
        //      OneSignal.registerForPushNotifications();
        //    });


        //    OneSignal.sendSelfNotification(
        //      /* Title (defaults if unset) */
        //      "Talk to Me",
        //      /* Message (defaults if unset) */
        //      "Seja bem vindo ao site, bla bla bla...",
        //       /* URL (defaults if unset) */
        //      'https://example.com/?_osp=do_not_open',
        //      /* Icon */
        //      'https://onesignal.com/images/notification_logo.png',
        //      {
        //        /* Additional data hash */
        //        notificationType: 'boas-vindas'
        //      },
        //      [{ /* Buttons */
        //        /* Choose any unique identifier for your button. The ID of the clicked button is passed to you so you can identify which button is clicked */
        //        id: 'btn-concordo',
        //        text: 'Concordo',
        //        icon: 'http://i.imgur.com/N8SN8ZS.png',
        //        url: 'http://ct.acaosistemas.com.br/'
        //     }]
        //    );
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
