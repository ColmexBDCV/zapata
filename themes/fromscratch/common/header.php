<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>
	<meta name="robots" content="nofollow" />
    <meta charset="utf-8">
    <?php if ($description = option('description')): ?>
    <meta name="description" content="<?php echo $description; ?>">
    <meta property="og:image" content="http://sandbox.colmex.mx/~asmartinez/guerrilla/fb4.png"/>
    <?php endif; ?>

    <?php if (isset($title)) {  $titleParts[] = strip_formatting($title); } $titleParts[] = option('site_title'); ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>
	
    <?php echo auto_discovery_link_tags(); ?>

    <!-- Plugin Stuff -->
    <?php fire_plugin_hook('public_head', array('view'=>$this)); ?>

    <!-- Stylesheets -->
    <?php
    queue_css_file('style', 'screen');
    echo head_css();
    ?>

    <!-- JavaScripts -->
    <?php queue_js_file('vendor/modernizr'); ?>
    <?php queue_js_file('globals'); ?>
    <?php echo head_js(); ?>
    <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
    
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-57216370-1', 'auto');
  ga('send', 'pageview');

</script>

<?php if ( strpos( current_url(), 'items/browse' ) !== false ) {?>
	<style>
		@media all and (max-width:900px){
			body{background:none!important;}
			.hentry h2, .hentry .item-meta, .hentry h2 a {background:none!important;color:black!important;}
		}
	
	</style>
<?php } ?>	
 
</head>

<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
    <?php fire_plugin_hook('public_body', array('view'=>$this)); ?>
    <div id="wrap">

        <div id="header">
            <?php fire_plugin_hook('public_header', array('view'=>$this)); ?>
            <div id="site-title">
                <?php echo link_to_home_page(theme_logo()); ?>
			</div>

			<nav class="top">
				<?php echo public_nav_main(); ?>
				<div id="search-container">
                <?php if (get_theme_option('use_advanced_search') === null || get_theme_option('use_advanced_search')): ?>
                <?php echo search_form(array('show_advanced' => true)); ?>
                <?php else: ?>
                <?Php echo search_form(); ?>
                <?php endif; ?>
            </div>
			</nav><!-- end primary-nav -->

        </div><!-- end header -->

		<?php
		$absolute = substr(absolute_url(), 0, -1);
		$home = link_to_home_page();
		$home = new SimpleXMLElement($home); //echo $home['href'];
		$home = $home['href'];
		if( strcmp($home,$absolute) === 0 ){
			echo '
				<style>
					#primary{float: left;width: 100%;padding: 0;}
				</style>';
		}
		?>
		
        <div id="content">
            <!--<div id="search-container">
                <?php //echo search_form(array('show_advanced' => true)); ?>
            </div>--><!-- end search -->
            <?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>
