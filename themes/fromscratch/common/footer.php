        </div><!-- end content -->

	<?php 

	if( strpos( current_url(), 'items'  ) !== false OR strpos( current_url(),'search'  ) !== false  ){
		echo "<style>#footer{position:relative;}</style>";
	}
	
	?>

        <div id="footer">

            <div id="footer-text">
				<?php if ( $footerText = get_theme_option('Footer Text') ): ?>
				<p><?php echo $footerText; ?></p>
				<?php endif; ?>
				<?php if ((get_theme_option('Display Footer Copyright') == 1) && $copyright = option('copyright')): ?>
					<p><?php echo $copyright; ?></p>
				<?php endif; ?>
				<div id="footer_text">El Colegio de M&eacute;xico A.C<br> Camino al Ajusco 20 Pedregal de Santa Teresa.<br> M&eacute;xico, D.F., C.P. 10740<br> Tel&eacute;fono: 01 55 54 49 30 00 ext.: 2121, 2122
				</div>
				<div id="footer_logo"><a href="http://biblioteca.colmex.mx">
				<?php if ( 
					strpos( current_url(), 'map' ) !== false 
					OR
					strpos( current_url(), 'show' ) !== false
					) {?>
					<img src="../../themes/fromscratch/footer_logo.png">
				<?php }elseif ( strpos( current_url(), 'items') !== false ){?>
					<img src="../themes/fromscratch/footer_logo.png">
				<?php }else{?>
					<img src="./themes/fromscratch/footer_logo.png">				
				<?php }?>
				</a>
				</div>
				<div id="footer_logo_instituto">
					<a href="http://www-01.sil.org/mexico/ilv/einfoilvmexico.htm">
					<?php if ( 
						strpos( current_url(), 'map' ) !== false 
						OR
						strpos( current_url(), 'show' ) !== false
					) {?>
						<img src="../../themes/fromscratch/footer_logo_instituto.png">
					<?php }elseif( strpos( current_url(),'items'  ) !== false  ){ ?>
						<img src="../themes/fromscratch/footer_logo_instituto.png">
					<?php }else{?>
						<img src="./themes/fromscratch/footer_logo_instituto.png">				
					<?php }?>
					</a>
				</div>
			</div>

		<?php fire_plugin_hook('public_footer', array('view' => $this)); ?>

        </div><!-- end footer -->
    </div><!-- end wrap -->
	<script type="text/javascript">
	jQuery(document).ready(function () {
		Seasons.showAdvancedForm();
		Seasons.mobileSelectNav();
	});
	</script>

</body>
</html>
