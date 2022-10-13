</div><!-- end content -->

<footer>

<?php
	$url_zap = '';
	//echo current_url();
    if( 
		strpos( current_url(), 'items'  ) !== false OR 
		strpos( current_url(),'search'  ) !== false OR 
		strpos(current_url(),'neatline') !== false
	){
		if(
			strpos(current_url(),'neatline-time') !== false
		){
			$url_zap = '';
		}elseif( 
			strpos(current_url(),'items/show/')!==false OR 
			strpos(current_url(),'neatline') !==false 
		){
			$url_zap = '../../';
		}else{
			$url_zap = '../';
		}
    }elseif(
		strpos( current_url(), 'collections/'  ) !== false 			
	){
		if( strpos(current_url(), 'collections/show') !== false ){
			$url_zap = '../../';
		}else{
			$url_zap = '../';
		}
	}
	//echo '--'.$url_zap.'--';
?>
    <div id="footer-content" class="center-div">
        <?php if($footerText = get_theme_option('Footer Text')): ?>
        <div id="custom-footer-text">
            <p><?php echo get_theme_option('Footer Text'); ?></p>
			<p class="credits">
				<a href="http://biblioteca.colmex.mx">
					<img src="<?php echo $url_zap;?>themes/avantgarde/images/logo_biblioteca.png" id="logo_biblioteca" target="_blank"/></a>
				<a href="http://www.fundacionzapata.org.mx/">
					<img src="<?php echo $url_zap;?>themes/avantgarde/images/logo_zapata.png" id="logo_zapata" target="_blank"/></a>
				<a href="http://morelos.gob.mx/">
					<img src="<?php echo $url_zap;?>themes/avantgarde/images/logo_morelos.png" id="logo_morelos" target="_blank"/></a>
				<a href="http://ceh.colmex.mx/">
					<img src="<?php echo $url_zap;?>themes/avantgarde/images/logo-CEH.png" id="logo_ceh" target="_blank"/></a>
				<a href="http://www.inah.gob.mx/es/">
					<img src="<?php echo $url_zap;?>themes/avantgarde/images/inah.png" id="logo_inah" target="_blank"/></a>
				<a href="http://www.fundacionzapata.org.mx/">
					<img src="<?php echo $url_zap;?>themes/avantgarde/images/fundacionzap.png" id="logo_fundacion" target="_blank"/></a>
				<a href="http://www.filmoteca.unam.mx/">
					<img src="<?php echo $url_zap;?>themes/avantgarde/images/FILMOTECA_UNAM.png" id="logo_filmoteca" target="_blank"/></a>
				<a>
					<img src="<?php echo $url_zap;?>themes/avantgarde/images/logo_EJERCITO-LIBERTADOR.png" id="logo_ejercito" target="_blank"/></a>
			</p>
        </div>
        <?php endif; ?>
        <?php if ((get_theme_option('Display Footer Copyright') == 1) && $copyright = option('copyright')): ?>
        <p><?php echo $copyright; ?>
		</p>
        <?php endif; ?>
            <p class="sociales">Sigue a la BDCV:</p>
			<?php //echo __('<a class="designed" href="http://emedara.com">Designed by Emedara Studio</a> <a class="powered" href="http://omeka.org">Proudly powered by Omeka</a>'); ?>
			<p class="sociales-iconos">
			<a href="https://es-la.facebook.com/Biblioteca-Daniel-Cos%C3%ADo-Villegas-199117690545/">&nbsp;&nbsp;&nbsp;
				<img src="<?php echo $url_zap;?>themes/avantgarde/images/facebook-icon.png" id="facebook" target="_blank"/>
			</a>
			<a href="https://twitter.com/bibliocolmex?lang=es">&nbsp;&nbsp;&nbsp;
				<img src="<?php echo $url_zap;?>themes/avantgarde/images/twitter-icon.png" id="twitter" target="_blank"/>
			</a>
			<!--<a title="asmartinez@colmex.mx">
				<img src="<?php //echo $url_zap;?>themes/avantgarde/images/correo.png" id="correo" /></a>-->
			</p>
			<span id="copyright">
				<a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/" id="copyright_license">
					<img alt="Licencia de Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by-nc/4.0/88x31.png" />
				<br />Este obra está bajo una licencia de Creative Commons Reconocimiento-NoComercial 4.0 Internacional.
				</a>
				<a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/" target="_blank">
				<img src="<?php echo $url_zap;?>themes/avantgarde/images/copyright.png" id="copyright_img" target="_blank"/></a>
			</span>
    </div><!-- end footer-content -->

     <?php fire_plugin_hook('public_footer', array('view'=>$this)); ?>

</footer>

<script type="text/javascript">
    jQuery(document).ready(function(){
        Omeka.showAdvancedForm();
               Omeka.dropDown();
	/* Funcion para reemplazar el termino dublin core relacion con "Combatiente"  */
	function combatientes(){
		jQuery('#dublin-core-relation h3').replaceWith('<h3>Combatientes</h3>');
		/* reemplazar la url de la relacion por la foto del veterano  */
		/*Poner nombre del veterano*/	
		jQuery('#dublin-core-relation .element-text').each(function(){
			/* reemplazar la url de la relacion por la foto del veterano  */
			var combatiente = jQuery(this).find('a');
			var url = combatiente.attr('href').replace('admin/','');
			combatiente.attr('class','veterano');
			jQuery(this).find('.veterano').load(url+' #dublin-core-title .element-text').html();
			jQuery(this).find('a').attr('href',url);
		});
	}
	
	/* ejecutar cambios si se dio click  */
	jQuery('#neatline').click(function(){
			setTimeout(function(){
				combatientes();
				console.log('timeout 200 combatientes click');
		
				/* Cambios a comentar ----*/
				//combatientes();
				//jQuery('#dublin-core-relation .element-text').append('<img id="imagen_icono" src="../../themes/avantgarde/images/picture_icon_thumb.png">');
				//jQuery('#dublin-core-relation .element-text').append('<div id="imagen_veterano"></div>');
				/*Quitar duplicados cuando se da click*/
				/*jQuery('#imagen_icono').each(function(){
					jQuery('[id="'+ this.id  +'"]').slice(2).remove();
				});
				jQuery('#imagen_veterano').each(function(){
					jQuery('[id="'+ this.id  +'"]').slice(2).remove();
				});*/
				/* ------- */
				
			},200);
	});
	
	/* Cambios-----*/
	//jQuery('#OpenLayers_Map_4_OpenLayers_Container').click(function(){combatientes();});

	/*Quitar duplicados al dar click*/
	/*Query('#imagen_icono').click(function(){
		jQuery('#imagen_icono').remove();
		console.log('borrando icono');
	});*/
	

	/* Si se hace hover el icono de imagen muestra la imagen del veterano  */
	/*setInterval(function(){
		var isHovered = !!jQuery('#imagen_icono').
                    filter(function() { return jQuery(this).is(":hover"); }).length;
		if(isHovered == true){
			var url_dos = jQuery('#dublin-core-relation .element-text a').attr('href');
			jQuery('#imagen_veterano').load(url_dos+'#item-images a img.full');
		}else{
			jQuery('#imagen_veterano').html('');
		}
	},500);*/
	/*-------*/

	/* si se carga la pagina directamente hacia un elemento*/
	if( window.location.href.indexOf("neatline") === -1 ){setTimeout(function(){
		combatientes();
		console.log('timeout 500 combatientes');
		
		/*Cambios ----*/
		//jQuery('#dublin-core-relation .element-text').append('<img id="imagen_icono" src="../../themes/avantgarde/images/picture_icon_thumb.png">');
		//jQuery('#dublin-core-relation .element-text').append('<div id="imagen_veterano"></div>');
		/*-------*/
		
	},500);}


	/* Cambiar el color del tooltip (bubble) de Neatline segun el color del punto  */
	//console.log(Neatline.g);
    });
</script>


<?php
	if( strpos(current_url(),'show/') !== false ){
		echo "
			<script>
				jQuery('#dublin-core-title h3').html('Nombre');
				jQuery('#dublin-core-description h3').css('display','none');
				var dc_date = jQuery('#dublin-core-date').html();
				jQuery('#dublin-core-date').remove();
				jQuery('#dublin-core-description').after('<div id=\'dublin-core-date\' class=\'element\'>'+dc_date+'</div>');
				jQuery('#dublin-core-date').after('<hr>');
				jQuery('h2:contains(Collection Tree)').html('');
			</script>
		";
	}	
	if( 
		strpos(current_url(),'collections/browse') !== false OR 
		strpos(current_url(),'items/show/') !== false 
	){
		echo "
			<script>
				jQuery('#content .collection h2 a').each(function(){
					var vinculo = jQuery(this).attr('href');
					var id = /[^/]*$/.exec(vinculo)[0];
					jQuery(this).attr('href','/items/browse?collection='+id);
				});
				jQuery('#derecha #collection a').each(function(){
					var vinculo = jQuery(this).attr('href');
					console.log(vinculo);
					var id = /[^/]*$/.exec(vinculo)[0];
					jQuery(this).attr('href','/items/browse?collection='+id);
				});
			</script>
		";
	}
	if( 
		current_url() == '/' 
	){
		echo "
			<script>
				if( jQuery(window).height() < 850  ){
					console.log('hey... fuck you');
					jQuery('html body footer').css('cssText','position:relative!important;');
				}
				if( jQuery(window).width() < 1000  ){
					jQuery('html body footer').css('cssText','position:relative!important;');
				}
			</script>
		";
	} 
?>


</body>

</html>
