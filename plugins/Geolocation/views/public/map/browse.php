<?php 
queue_css_file('geolocation-items-map');

$title = __('Navegar Items en el mapa') . ' ' . __('(%s totales)', $totalItems);
echo head(array('title' => $title, 'bodyclass' => 'map browse'));
?>

<!--<h1><?php echo $title; ?></h1>-->

<!--<nav class="items-nav navigation secondary-nav">
    <?php //echo public_nav_items(); ?>
</nav>-->

<?php
//echo item_search_filters();
//echo pagination_links();
?>

<div id="geolocation-browse">
    <?php echo $this->googleMap('map_browse', array('list' => 'map-links', 'params' => $params)); ?>
    <div id="map-links"><h2><?php echo __('Encuentra por ubicaci&oacute;n'); ?></h2></div>
</div>

<?php echo foot(); ?>
