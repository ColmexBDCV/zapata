<!DOCTYPE html>
<html class="<?php echo get_theme_option('Style Sheet'); ?>" lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes" />
    <?php if ($description = option('description')): ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php endif; ?>

    <?php
	$URL = $_SERVER['REQUEST_URI'];
	//echo $URL;
	if(
		$URL == '/introduccion' 
		OR 
		strpos($URL,'collection=3') !== false 
		OR
		$URL == '/contacto' 
		OR
		$URL == '/' 
		OR
		$URL == '/collections/browse' 
	){
		echo '<style>footer{position:fixed!important;}#content{margin-bottom:450px!important;}</style>';
	}
	
	if($URL == '/'){
		echo "<style>body{background:url(/themes/avantgarde/images/fondo_home_background.png)!important;background-size:cover!important;}</style>";
	}
	if( strpos( $URL, 'collections/browse') !== false ){
		echo '<style>
			html body .collection-description, html body .collection .image, html body .element-set{display:none;}
			html body .collection h2,html body .exhibit h2{width:60%!important;}
			html body .collection-meta{width:30%!important;float: right;text-align: right;}
			html body #content .view-items-link a{border:none;color:#732242;text-transform:none;}
			html body #content .view-items-link{margin:0;}
		</style>';
	}elseif( strpos( $URL, 'collections/show') !== false ){
		echo '<style>
			html body .element-set, html body #collection-tree{display:none;}
		</style>';

	}
	/*Cargar simile jss en lugar de la API remota*/
	if( strpos( $URL, 'neatline/') !== false ){
		/*echo '<script type="text/javascript">//<!--
				SimileAjax.History.enabled = false; window.jQuery = SimileAjax.jQuery    //--></script>';**/
		/*echo '<script type="text/javascript">//<!--
				window.jQuery = window.jQuery;    //--></script>';*/
		/*queue_js_file('simile/labellers');
		queue_js_file('simile/simile-ajax-api');
		queue_js_file('simile/simile-ajax-bundle');
		queue_js_file('simile/timeline-api');
		queue_js_file('simile/timeline-bundle');
		queue_js_file('simile/timeline');
		//queue_css_file('simile/graphics');
		//queue_css_file('simile/timeline-bundle');*/
		
	}
	
    if (isset($title)) {
        $titleParts[] = strip_formatting($title);
    }
    $titleParts[] = option('site_title');
    ?>

    <title><?php echo implode(' &middot; ', $titleParts); ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <?php fire_plugin_hook('public_head',array('view'=>$this)); ?>
    <!-- Stylesheets -->
    <?php
    queue_css_file(array('iconfonts', 'style'));

    echo head_css();
    ?>
    <!-- JavaScripts -->
    <?php queue_js_file('vendor/modernizr'); ?>
    <?php queue_js_file('vendor/selectivizr', 'javascripts', array('conditional' => '(gte IE 6)&(lte IE 8)')); ?>
    <?php queue_js_file('vendor/respond'); ?>
    <?php queue_js_file('globals'); ?>
    <?php echo head_js(); ?>
</head>
 <?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
    <?php fire_plugin_hook('public_body', array('view'=>$this)); ?>
        <header>
            <?php fire_plugin_hook('public_header', array('view'=>$this)); ?>
            <div id="site-title"><?php echo link_to_home_page(theme_logo()); ?><div id="descripcion-sitio">Base de datos del Instituto Pro Veteranos de la Revoluci&oacute;n del Sur</div></div>
			<div id="primary-nav">
             <?php
                  echo public_nav_main();
             ?>
			</div>
        </header>         
  
         <div id="mobile-nav">
             <?php
                  echo public_nav_main();
             ?>
         </div>
        
		<?php if ((get_theme_option('Header Image') !== null)): ?>
		<div id="header-image-holder">
		<div class="held">
		<?php if ((get_theme_option('header_image_heading') !== '')): ?>
		<h2><?php echo get_theme_option('header_image_heading'); ?></h2>
		<?php endif; ?>
		<?php if ((get_theme_option('header_image_text') !== '')): ?>
		<p><?php echo get_theme_option('header_image_text'); ?></p>
		<?php endif; ?>
		</div>
        <?php echo theme_header_image(); ?>
		</div>
		<?php endif; ?>
		
		
                       
    <div id="content">

<?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>
