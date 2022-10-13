<?php
$pageTitle = __('Browse Items');
echo head(array('title'=>$pageTitle,'bodyclass' => 'items browse'));

	$params = null;
        if ($params === null) {
            $request = Zend_Controller_Front::getInstance()->getRequest();
            $requestArray = $request->getParams();
        } else {
            $requestArray = $params;
        }

        $db = get_db();
        $displayArray = array();
        foreach ($requestArray as $key => $value) {
            if($value != null) {
                $filter = ucfirst($key);
                $displayValue = null;
                switch ($key) {
                    case 'type':
                        $filter = 'Item Type';
                        $itemType = $db->getTable('ItemType')->find($value);
                        if ($itemType) {
                            $displayValue = $itemType->name;
                        }
                        break;

                    case 'collection':
                        $collection = $db->getTable('Collection')->find($value);
                        if ($collection) {
                            $displayValue = strip_formatting(
                                metadata(
                                    $collection,
                                    array('Dublin Core', 'Title'),
                                    array('no_escape' => true)
                                )
                            );
                        }
                        break;

                    case 'user':
                        $user = $db->getTable('User')->find($value);
                        if ($user) {
                            $displayValue = $user->name;
                        }
                        break;

                    case 'public':
                    case 'featured':
                        $displayValue = ($value == 1 ? __('Yes') : $displayValue = __('No'));
                        break;

                    case 'search':
                    case 'tags':
                    case 'range':
                        $displayValue = $value;
                        break;
                }
                if ($displayValue) {
                    $displayArray[$filter] = $displayValue;
                }
		//var_dump($displayValue[0]);
		//echo $displayValue;
	    }
	}


?>

<!--<h1><?php echo $pageTitle;?> <?php echo __('(%s total)', $total_results); ?></h1>-->
<h1><?php echo 'Resultados de su b&uacute;squeda';?> <?php echo __('(%s total)', $total_results); ?></h1>


<nav class="items-nav navigation secondary-nav">
    <?php echo public_nav_items(); ?>
</nav>

<?php 
	echo item_search_filters(); 

	//if($displayValue !== 'Veteranos'){
		echo "<style>
		html body .item .item-img,
		#contenedor .item-meta, 
		#contenedor .item-description
		{
			height:0px!important;
		}
		#contenedor{height:auto;}
		.item-img,.item-description{display:none!important;}
		</style>";
	//}


	if($displayValue === 'Hechos de armas'){echo "<style>html body #outputs{margin:50px 0;}</style>";}
?>

<div id ="pagination_sort">

<?php echo pagination_links(); ?>
<?php //echo pagination_links( $options = array('per_page' => 5)  ); ?>

<?php if ($total_results > 0): ?>

<?php
$sortLinks[__('Title')] = 'Dublin Core,Title';
$sortLinks[__('Creator')] = 'Dublin Core,Creator';
$sortLinks[__('Date Added')] = 'added';
?>
<div id="sort-links">
    <span class="sort-label"><?php echo __('Organizar por: '); ?></span><?php echo browse_sort_links($sortLinks); ?>
</div>

</div>

<?php endif; ?>

<?php 
	$limit = 2;
	$records = get_records( 'item', $params, $limit );	  
	//var_dump($records);
	set_loop_records('items',$items);
	//var_dump(loop('items'));
	//foreach(loop('items') as $item){echo link_to_browse_items();}
?>

<?php foreach (loop('items') as $item): ?>
<div class="item hentry">
  <div class="item-img">
    <?php if (metadata('item', 'has thumbnail')): ?>
        <?php echo link_to_item(item_image('square_thumbnail')); ?>
    <?php endif; ?>
    </div>

    <div id="contenedor">

	<h2><?php echo link_to_item(metadata('item', array('Dublin Core', 'Title')), array('class'=>'permalink')); ?></h2>
    	<div class="item-meta">

    		<?php if ($description = metadata('item', array('Dublin Core', 'Description'), array('snippet'=>250))): ?>
    			<div class="item-description">
        			<?php echo $description; ?>
    			</div>
    		<?php endif; ?>

    		<?php if (metadata('item', 'has tags')): ?>
    			<div class="tags"><p><strong><?php echo __('Tags'); ?>:</strong>
        			<?php echo tag_string('items'); ?></p>
    			</div>
    		<?php endif; ?>

    		<?php fire_plugin_hook('public_items_browse_each', array('view' => $this, 'item' =>$item)); ?>

    	</div><!-- end class="item-meta" -->
    
     </div><!--contenedor-->

</div><!-- end class="item hentry" -->

<?php endforeach; ?>

<?php //echo pagination_links(); ?>

<div id="outputs">
    <span class="outputs-label"><?php echo __('Output Formats'); ?></span>
    <?php echo output_format_list(false); ?>
</div>

<?php fire_plugin_hook('public_items_browse', array('items'=>$items, 'view' => $this)); ?>

<?php echo foot(); ?>
