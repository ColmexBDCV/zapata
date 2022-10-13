<?php echo head(array('bodyid'=>'home', 'bodyclass' =>'two-col')); ?>

<?php 
	$URL = $_SERVER['REQUEST_URI'];
	//echo $URL;
	if(!$URL == '/zapata/'){
		echo '<style>#imagen{display:none;}</style>';
	}elseif( $URL == '/zapata/' OR $URL == '/zapata/introduccion/' ){
		echo '<style>footer{position:fixed;}</style>';
	}?>

<div id="imagen"><img src="./themes/avantgarde/images/inicio1.png"/></div>

<div id="primary">
<?php if ($homepageText = get_theme_option('Homepage Text')): ?>
    <div id="homepage-text"><p><?php echo $homepageText; ?></p></div>
    <?php endif; ?>
    <?php
    $recentItems = get_theme_option('Homepage Recent Items');
    if ($recentItems === null || $recentItems === ''):
        $recentItems = 3;
    else:
        $recentItems = (int) $recentItems;
    endif;
    if ($recentItems):
    ?>
    <!--<div id="recent-items">
        <h2><?php /*echo __('Recently Added Items'); ?></h2>
        <?php echo recent_items($recentItems); ?>
        <p class="view-items-link"><a href="<?php echo html_escape(url('items')); ?>"><?php echo __('View All Items'); */?></a></p>
    </div>--><!--end recent-items -->
    <?php endif; ?>
    
    <?php fire_plugin_hook('public_home', array('view' => $this)); ?>

</div><!-- end primary -->

<div id="secondary">
	<div id="search-container">
		<h2>Search</h2>
		<?php if (get_theme_option('use_advanced_search') === null || get_theme_option('use_advanced_search')): ?>
		<?php echo search_form(array('show_advanced' => true)); ?>
		<?php else: ?>
        <?php //echo search_form(); ?>

		<script>var fondo = document.getElementById('content');fondo.style.background = "rgba(0,0,0,0)";</script>
		<style>
			@media all and (max-height:850px){#content{top:15%;}}
			@media all and (max-height:750px){#content{top:15%;}}
			#content{top:15%;padding:0!important;}
		</style>
		
		<form id="advanced-search-form" name="search-form" action=<?php echo $this->baseUrl()."/items/browse";?> method="get">    
			<input type="text" name="search" id="query" value="" title="Search">        
			<button name="submit_search" id="submit_search" type="submit" value="Search">Buscar</button>

			<div class="inputs advanced">
                <div class="search-entry">
                    <input id="todo" onclick="change_name('query', 'all')" class="advanced-search-element" title="Search Field" type="radio" name="advanced[0][element_id]" value="" checked="checked">
                    <label id="todo_label" for="todo">Todo</label> 
                    <input id="titulo" onclick="change_name('query', 'title')" class="advanced-search-element" title="Search Field" type="radio" name="advanced[0][element_id]" value="50">
                    <label id="titulo_label" for="titulo">T&iacute;tulo</label> 
                    <input id="autor" onclick="change_name('query', 'autor')" class="advanced-search-element" title="Search Field" type="radio" name="advanced[0][element_id]" value="39">
                    <label id="tema_label" for="autor">Autor</label> 
                    <input id="descripcion" class="advanced-search-element" onclick="change_name('query', 'desc')" title="Search Field" type="radio" name="advanced[0][element_id]" value="41">
                    <label id="descripcion_label" for="descripcion">Descripci&oacute;n</label>
                    <input type="hidden" name="advanced[0][type]" value="" id="advanced_input">
					<input type="hidden" value="Dublin Core,Title" name="sort_field"/>
                </div>
			</div>

		</form>

		<?php endif; ?>

	</div>
    
    <?php if (get_theme_option('Display Featured Item') == 1): ?>
    <!-- Featured Item -->
    <div id="featured-item">
        <h2><?php echo __('Featured Item'); ?></h2>
        <?php echo random_featured_items(1); ?>
    </div><!--end featured-item-->
    <?php endif; ?>
    <?php if (get_theme_option('Display Featured Collection')): ?>
    <!-- Featured Collection -->
    <div id="featured-collection">
        <h2><?php echo __('Featured Collection'); ?></h2>
        <?php echo random_featured_collection(); ?>
    </div><!-- end featured collection -->
    <?php endif; ?>
    <?php if ((get_theme_option('Display Featured Exhibit')) && function_exists('exhibit_builder_display_random_featured_exhibit')): ?>
    <!-- Featured Exhibit -->
    <?php echo exhibit_builder_display_random_featured_exhibit(); ?>
    <?php endif; ?>

</div><!-- end secondary -->
<?php echo foot(); ?>
