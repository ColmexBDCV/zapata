<?php
if (!empty($formActionUri)) :
    $formAttributes['action'] = $formActionUri;
else :
    $formAttributes['action'] = url(array(
        'controller' => 'items',
        'action' => 'browse'
    ));
endif;
$formAttributes['method'] = 'GET';
?>
<script>
    function hideElements() {
        var hide_ids = new Array(
            "47", "51", "66", "67", "58", "63", "62", "71", "87", "88", 
            "96", "83", "21", "20", "19", "18", "17","16", "15", "13", 
            "12", "10", "7", "22","23", "24", "14", "36", "35", "34", 
            "33", "32", "30", "29", "28", "27", "26", "25","1");

        var selects = document.getElementsByClassName("find_selects");
        for (var element of selects) {
            options = element.getElementsByTagName("option");
            for (var option of options) {
                for (var text_id of hide_ids) {
                    if (text_id == option.value.trim()) {
                        console.log(option);
                        option.style.display = 'none';
                    }
                }
            }
        }
    }
</script>
<form <?php echo tag_attributes($formAttributes); ?> class="advanced-form">
    <div id="search-narrow-by-fields" class="field">
        <?php echo $this->formLabel('', __('Narrow by Specific Fields')); ?>
        <!--<div class="label"><?php //echo __('Narrow by Specific Fields'); 
                                ?></div>-->
        <div class="inputs">
            <?php
            // If the form has been submitted, retain the number of search
            // fields used and rebuild the form
            if (!empty($_GET['advanced'])) {
                $search = $_GET['advanced'];
            } else {
                $search = array(array('field' => '', 'type' => '', 'value' => ''));
            }

            //Here is where we actually build the search form
            foreach ($search as $i => $rows) : ?>
                <div class="search-entry">
                    <?php
                    //The POST looks like =>
                    // advanced[0] =>
                    //[field] = 'description'
                    //[type] = 'contains'
                    //[terms] = 'foobar'
                    //etc
                    echo $this->formSelect(
                        "advanced[$i][joiner]",
                        @$rows['joiner'],
                        array(
                            'title' => __("Search Joiner"),
                            'id' => null,
                            'class' => 'advanced-search-joiner'
                        ),
                        array(
                            'and' => __('Y'),
                            'or' => __('O'),
                        )
                    );
                    echo $this->formSelect(
                        "advanced[$i][element_id]",
                        @$rows['element_id'],
                        array(
                            'title' => __("Search Field"),
                            'id' => null,
                            'class' => 'advanced-search-element find_selects'
                        ),
                        get_table_options(
                            'Element',
                            null,
                            array(
                                'record_types' => array('Item', 'All'),
                                'sort' => 'orderBySet'
                            )
                        )
                    );
                    echo $this->formSelect(
                        "advanced[$i][type]",
                        @$rows['type'],
                        array(
                            'title' => __("Search Type"),
                            'id' => null,
                            'class' => 'advanced-search-type'
                        ),
                        label_table_options(
                            array(
                                'contains' => __('contains'),
                                'does not contain' => __('does not contain'),
                                'is exactly' => __('is exactly'),
                                'is empty' => __('is empty'),
                                'is not empty' => __('is not empty'),
                                'starts with' => __('starts with'),
                                'ends with' => __('ends with')
                            )
                        )
                    );
                    echo $this->formText(
                        "advanced[$i][terms]",
                        @$rows['terms'],
                        array(
                            'size' => '20',
                            'title' => __("Search Terms"),
                            'id' => null,
                            'class' => 'advanced-search-terms'
                        )
                    );
                    ?>
                    <button type="button" class="remove_search" disabled="disabled" style="display: none;"><?php echo __('Remove field'); ?></button>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="inputs">
            <button type="button" class="button-header add_search"><?php echo __('Add a Field'); ?></button>
        </div>
    </div>

    <!--<div id="search-by-range" class="field">
        <?php echo $this->formLabel('range', __('Search by a range of ID#s (example: 1-4, 156, 79)')); ?>
        <div class="inputs">
        <?php
        echo $this->formText(
            'range',
            @$_GET['range'],
            array('size' => '40')
        );
        ?>
        </div>
    </div>-->

    <div class="field">
        <?php echo $this->formLabel('collection-search', __('Search By Collection')); ?>
        <div class="inputs">
            <?php
            echo $this->formSelect(
                'collection',
                @$_REQUEST['collection'],
                array('id' => 'collection-search'),
                get_table_options('Collection', null, array('include_no_collection' => true))
            );
            ?>
        </div>
    </div>
    <!--
    <div class="field">
        <?php echo $this->formLabel('item-type-search', __('Search By Type')); ?>
        <div class="inputs">
        <?php
        echo $this->formSelect(
            'type',
            @$_REQUEST['type'],
            array('id' => 'item-type-search'),
            get_table_options('ItemType')
        );
        ?>
        </div>
    </div>

    <?php if (is_allowed('Users', 'browse')) : ?>
    <div class="field">
    <?php
        echo $this->formLabel('user-search', __('Search By User')); ?>
        <div class="inputs">
        <?php
        echo $this->formSelect(
            'user',
            @$_REQUEST['user'],
            array('id' => 'user-search'),
            get_table_options('User')
        );
        ?>
        </div>
    </div>-->
<?php endif; ?>


<?php if (is_allowed('Items', 'showNotPublic')) : ?>
    <!--<div class="field">
        <?php echo $this->formLabel('public', __('Public/Non-Public')); ?>
        <div class="inputs">
        <?php
        echo $this->formSelect(
            'public',
            @$_REQUEST['public'],
            array(),
            label_table_options(array(
                '1' => __('Only Public Items'),
                '0' => __('Only Non-Public Items')
            ))
        );
        ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="field">
        <?php echo $this->formLabel('featured', __('Featured/Non-Featured')); ?>
        <div class="inputs">
        <?php
        echo $this->formSelect(
            'featured',
            @$_REQUEST['featured'],
            array(),
            label_table_options(array(
                '1' => __('Only Featured Items'),
                '0' => __('Only Non-Featured Items')
            ))
        );
        ?>
        </div>
    </div>-->
    <div id="search-keywords" class="field">
        <?php echo $this->formLabel('keyword-search', __('Búsqueda por coincidencia')); ?>
        <div class="inputs">
            <?php
            echo $this->formText(
                'search',
                @$_REQUEST['search'],
                array('id' => 'keyword-search', 'size' => '40')
            );
            ?>
        </div>
    </div>

    <div id="search-exact" class="field">
        <?php echo $this->formLabel('keyword-search', __('Búsqueda de frase exacta')); ?>
        <div class="inputs">
            <?php
            echo $this->formText(
                'search-exact',
                @$_REQUEST['search-exact'],
                array('id' => 'search-exact', 'size' => '40')
            );
            ?>
        </div>
    </div>

    <div class="field">
        <?php echo $this->formLabel('tag-search', __('Search By Tags')); ?>
        <div class="inputs">
            <?php
            echo $this->formText(
                'tags',
                @$_REQUEST['tags'],
                array('size' => '40', 'id' => 'tag-search')
            );
            ?>
        </div>
    </div>
    <?php //fire_plugin_hook('public_items_search', array('view' => $this)); 
    ?>
    <div>
        <?php if (!isset($buttonText)) {
            $buttonText = __('Realizar Búsqueda');
        } ?>
        <input type="submit" class="submit" name="submit_search" id="submit_search_advanced" value="<?php echo $buttonText ?>">
    </div>
    <input type="hidden" value="Dublin Core,Title" name="sort_field" />
</form>

<?php echo js_tag('items-search'); ?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        Omeka.Search.activateSearchButtons();
    });
    //sleep(5000).then(() => {
    // Do something after the sleep!
    hideElements();
    // });
</script>