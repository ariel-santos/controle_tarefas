<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<title>Falarme</title>
<?php wp_head(); ?>
<script src="<?php echo get_template_directory_uri();?>/js/materialize.js"></script>
<script>
	jQuery(document).ready(function(){
		jQuery('.carousel').carousel();
		jQuery('.carousel.carousel-slider').carousel({fullWidth: true});
        jQuery('.modal').modal();
        jQuery('select').material_select();
        
        jQuery("form#fm_add_tarefa").on('submit', function(e){
            e.preventDefault();
            
            var dados = new FormData(this);
            dados.append( "file[]", jQuery('input#input_wp_media')[0].files[0]);
            dados.append( "file[]", jQuery('input#input_wp_media')[0].files[1]);
            dados.append( "file[]", jQuery('input#input_wp_media')[0].files[2]);
            
//            console.log(jQuery('input#input_wp_media')[0].files);
//            console.log(jQuery('input#input_wp_media')[0].files[0]);
            
            jQuery.ajax({
                url: "<?php echo get_template_directory_uri();?>/bd/cadastra_tarefa.php",
                type: 'POST',
                data: dados,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                headers: { 'Content-Disposition': 'attachment; filename=test.jpg' },
            })
            .done(function(data){
                console.log(data);
                if(data.cod == 0 ){
                    setTimeout(function(){
                        jQuery('#modal_tarefa').modal('close');  
                        location.reload();
                    },2000);
                }
            });
        });
        
        jQuery('.collapsible').collapsible({
            onOpen: function(el){ jQuery(el).css("display", "grid"); },
            onClose: function(el){ jQuery(el).css("display", "block"); }
        });
	});
</script>
