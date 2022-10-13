<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')), 'bodyclass' => 'items show')); ?>
<div id="primary">
    <div id="izquierda">
        <div id="item-images">
        <?php $show_files = files_for_item(array('imageSize' => 'fullsize')); 
        if($show_files):
            /*if (restrict_item() != true && metadata('item', 'Collection Name') == 'Biblioteca Garay') : ?>
                <?php echo 'PARA ACCEDER A LOS DOCUMENTOS, PONGASE EN CONTACTO CON UN BIBLIOTECARIO DE LA BDCV'; ?>
                <?php echo '<br><a href="https://biblioteca.colmex.mx/index.php/referencia-virtual" target="_blank">https://biblioteca.colmex.mx/index.php/referencia-virtual</a>'; ?>
            <?php else: */?>
                <?php echo $show_files;?>  
            <?php /*endif;*/ ?>
        <?php endif;?>
        <?php //echo files_for_item(array('imageSize' => 'fullsize')); ?>
        </div>
    </div> <!-- izquierda  -->

    <div id="derecha">

        <!--<h1><?php //echo metadata('item', array('Dublin Core','Title'));
                ?></h1>-->
        <?php //if (restrict_item() && metadata('item', 'Collection Name') == 'Corridos y música popular sobre la revolución mexicana') : ?>
            <div id="item-metadata">
                <?php echo all_element_texts('item'); ?>
            </div>

            <?php if (metadata('item', 'Collection Name')) : ?>
                <div id="collection" class="element">
                    <h3><?php echo __('Collection'); ?></h3>
                    <div class="element-text"><?php echo link_to_collection_for_item(); ?></div>
                </div>
            <?php endif; ?>

            <!-- The following prints a list of all tags associated with the item -->
            <?php if (metadata('item', 'has tags')) : ?>
                <div id="item-tags" class="element">
                    <h3><?php echo __('Tags'); ?></h3>
                    <div class="element-text"><?php echo tag_string('item'); ?></div>
                </div>
            <?php endif; ?>

            <!-- The following prints a citation for this item. -->
            <div id="item-citation" class="element">
                <h3><?php echo __('Citation'); ?></h3>
                <div class="element-text"><?php echo metadata('item', 'citation', array('no_escape' => true)); ?></div>
            </div>
            <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>


            <ul class="item-pagination navigation">
                <li id="previous-item" class="previous"><?php echo link_to_previous_item_show(); ?></li>
                <li id="next-item" class="next"><?php echo link_to_next_item_show(); ?></li>
            </ul>

        <?php /*else :
            echo 'PONGASE EN CONTACTO CON SU BIBLIOTECARIO';

        endif;*/ ?>

    </div> <!-- end derecha  -->

</div> <!-- End of Primary. -->

<?php echo foot(); ?>
