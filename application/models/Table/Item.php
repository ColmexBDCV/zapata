<?php
/**
 * Omeka
 * 
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * @package Omeka\Db\Table
 */
class Table_Item extends Omeka_Db_Table
{
    /**
     * Run the search filter on the SELECT statement
     *
     * @param Zend_Db_Select
     * @param array
     */
    public function filterBySearch($select, $params)
    {   
        //print_r(@$params['search']);
        //print_r(@$params['advanced']);
        //Apply the simple or advanced search
        if (isset($params['search']) || isset($params['advanced']) || isset($params['search-exact'])) {
            if ($simpleTerms = @$params['search']) {
                $this->_simpleSearch($select, $simpleTerms);
            }
            if($simpleTerms = @$params['search-exact']){
                $this->_exactSearch($select, $simpleTerms);
            }
            if ($advancedTerms = @$params['advanced']) {
                $this->_advancedSearch($select, $advancedTerms);
            }
        }
    }

    /**
     * Build the simple search.
     * 
     * The search query consists of a derived table that is INNER JOINed to the 
     * main SQL query.  That derived table is a union of two SELECT queries. The 
     * first query searches the FULLTEXT index on the items_elements table, and 
     * the second query searches the tags table for every word in the search 
     * terms and assigns each found result a rank of '1'. That should make 
     * tagged items show up higher on the found results list for a given search.
     * 
     * @param Zend_Db_Select $select
     * @param string $simpleTerms
     */
    protected function _exactSearch($select, $terms)
    {
        $db = $this->getDb();

        // Build tags query.
        $tagList = preg_split('/\s+/', $terms);
        // Make sure the tag list contains the whole search string, just in case
        // that is found
        if (count($tagList) > 1) {
            $tagList[] = $terms;
        }

        $select->joinLeft(
            array('_simple_records_tags' => $db->RecordsTags),
            "_simple_records_tags.record_id = items.id AND _simple_records_tags.record_type = 'Item'",
            array()
        );
        $select->joinLeft(
            array('_simple_tags' => $db->Tag),
            '_simple_tags.id = _simple_records_tags.tag_id',
            array()
        );

        $subquery = new Omeka_Db_Select;
        $subquery->from(array('_simple_etx' => $db->ElementText), '_simple_etx.record_id')
            ->where("_simple_etx.record_type = 'Item'")
            ->where('_simple_etx.text LIKE ?', '%' . $terms . '%');

        $whereCondition = "items.id IN ($subquery) OR "
                        . $db->quoteInto('_simple_tags.name IN (?)', $tagList);
        $select->where($whereCondition);
        /*$db = $this->getDb();

        // Build tags query.
        $tagList = preg_split('/\s+/', $terms);
        $terms = str_replace(array(" "),array(" +"),$terms);
        $terms = "+".$terms;
        // Make sure the tag list contains the whole search string, just in case
        // that is found
        if (count($tagList) > 1) {
            $tagList[] = $terms;
        }

        $select->joinLeft(
            array('_simple_records_tags' => $db->RecordsTags),
            "_simple_records_tags.record_id = items.id AND _simple_records_tags.record_type = 'Item'",
            array()
        );
        $select->joinLeft(
            array('_simple_tags' => $db->Tag),
            '_simple_tags.id = _simple_records_tags.tag_id',
            array()
        );

        $subquery = new Omeka_Db_Select;
        $aliasSearch = 'search_texts';
        $subquery->from(array("{$aliasSearch}" => $db->SearchText), "{$aliasSearch}.record_id")
            ->where("{$aliasSearch}.record_type = 'Item'");

        $subquery->where('MATCH ('.$aliasSearch.'.title) AGAINST ("'.$terms.'" IN BOOLEAN MODE)');

        $subquery->order($aliasSearch.'.text');

        $whereCondition = "items.id IN ($subquery) OR "
                        . $db->quoteInto('_simple_tags.name IN (?)', $tagList);

        $select->where($whereCondition);*/

    }

    /**
     * Build the simple search.
     * 
     * The search query consists of a derived table that is INNER JOINed to the 
     * main SQL query.  That derived table is a union of two SELECT queries. The 
     * first query searches the FULLTEXT index on the items_elements table, and 
     * the second query searches the tags table for every word in the search 
     * terms and assigns each found result a rank of '1'. That should make 
     * tagged items show up higher on the found results list for a given search.
     * 
     * @param Zend_Db_Select $select
     * @param string $simpleTerms
     */
    protected function _simpleSearch($select, $terms)
    {
        $db = $this->getDb();

        // Build tags query.
        $tagList = preg_split('/\s+/', $terms);
        // Make sure the tag list contains the whole search string, just in case
        // that is found
        if (count($tagList) > 1) {
            $tagList[] = $terms;
        }
        $select->joinLeft(
            array('_search_texts' => $db->SearchText),
            "_search_texts.record_id = items.id AND _search_texts.record_type = 'Item'",
            array()
        );
        $select->joinLeft(
            array('_simple_records_tags' => $db->RecordsTags),
            "_simple_records_tags.record_id = items.id AND _simple_records_tags.record_type = 'Item'",
            array()
        );
        $select->joinLeft(
            array('_simple_tags' => $db->Tag),
            '_simple_tags.id = _simple_records_tags.tag_id',
            array()
        );

        $subquery = new Omeka_Db_Select;

        $aliasSearch = 'search_texts';

        $subquery->from(array("{$aliasSearch}" => $db->SearchText), "{$aliasSearch}.record_id")
            ->where("{$aliasSearch}.record_type = 'Item'");

        $subquery->where('MATCH (' . $aliasSearch . '.text) AGAINST ("' . $terms . '" IN BOOLEAN MODE)');

        $subquery->order($aliasSearch . '.text');

        $whereCondition = "items.id IN ($subquery) OR "
            . $db->quoteInto('_simple_tags.name IN (?)', $tagList);
        
        $predicate = 'MATCH (' . '_'.$aliasSearch . '.text) AGAINST ("' . $terms . '" IN BOOLEAN MODE)';
        $select->columns(array("result" => new Zend_Db_Expr($predicate)))
            ->where($whereCondition)
            ->reset('order')
            ->group('items.id')
            ->order(array("result DESC"));
    }

    /**
     * Build the advanced search.
     * 
     * @param Zend_Db_Select $select
     * @param array $simpleTerms
     */
    protected function _advancedSearch($select, $terms)
    {
        $db = $this->getDb();

        $where = '';
        $advancedIndex = 0;
        foreach ($terms as $v) {
            // Do not search on blank rows.
            if (!empty($v['element_id'])) {
                $elementId = (int) $v['element_id'];
            }else{
                $elementId = '';
            }

            if (!empty($v['type'])) {
                $type = $v['type'];
            }else{
                $type = 'contains';
            }

            $value = isset($v['terms']) ? $v['terms'] : null;

            if(empty($v['type']) && empty($v['element_id']) && $value == null){
                continue;
            }

            $value = isset($v['terms']) ? $v['terms'] : null;

            $alias = "_advanced_{$advancedIndex}";
            $aliasSearch = "search_texts_{$advancedIndex}";
            $result = "result_{$advancedIndex}";

            $joiner = isset($v['joiner']) && $advancedIndex > 0 ? $v['joiner'] : null;

            $negate = false;
            // Determine what the WHERE clause should look like.
            switch ($type) {
                case 'does not contain':
                    $negate = true;
                case 'contains':
                    $predicate = 'MATCH ('.$aliasSearch.'.'.($elementId == '50' ? "title" : "text").') AGAINST ("'.$value.'" IN BOOLEAN MODE)';
                    //$predicate = "LIKE " . $db->quote('%'.$value .'%');
                    break;

                case 'is not exactly':
                    $negate = true;
                case 'is exactly':
                    $predicate = ' = ' . $db->quote($value);
                    break;

                case 'is empty':
                    $negate = true;
                case 'is not empty':
                    $predicate = 'IS NOT NULL';
                    break;

                case 'starts with':
                    $predicate = "LIKE " . $db->quote($value.'%');
                    break;

                case 'ends with':
                    $predicate = "LIKE " . $db->quote('%'.$value);
                    break;

                case 'does not match':
                    $negate = true;
                case 'matches':
                    if (!strlen($value)) {
                        continue 2;
                    }
                    $predicate = 'REGEXP ' . $db->quote($value);
                    break;

                default:
                    throw new Omeka_Record_Exception(__('Invalid search type given!'));
            }

            if($type == 'contains'){
                $predicateClause = "{$predicate}";
                $predicateClause .= "AND {$alias}.element_id IS NOT NULL";
            }else{
                $predicateClause = "{$alias}.text {$predicate}";
            }

            // Note that $elementId was earlier forced to int, so manual quoting
            // is unnecessary here
            $joinCondition = "{$alias}.record_id = items.id AND {$alias}.record_type = 'Item' ". ($elementId != '' ? " AND {$alias}.element_id = $elementId": "");

            if($type == 'contains'){
                $joinSearch = "{$aliasSearch}.record_id = items.id AND {$aliasSearch}.record_type = 'Item'";
            }            

            if ($negate) {
                $joinCondition .= " AND {$predicateClause}";
                $whereClause = "{$alias}.text IS NULL";
            } else {
                $whereClause = $predicateClause;
            }

            $select->joinLeft(array($alias => $db->ElementText), $joinCondition, array());
            if($type == 'contains'){
                $select->joinLeft(array($aliasSearch => $db->SearchText), $joinSearch, array());
                $select->columns(array("{$result}" => new Zend_Db_Expr($predicate)))
                ->reset('order')
                ->group('items.id')
                ->order(array("{$result} DESC"));
            }

            if ($where == '') {
                $where = $whereClause;
            } elseif ($joiner == 'or') {
                $where .= " OR $whereClause";
            } else {
                $where .= " AND $whereClause";
            }
            $advancedIndex++;
        }

        if ($where) {
            $select->where($where);
        }
        //echo $select."<br><br>";
    }

    /**
     * Filter the SELECT statement based on an item's collection
     *
     * @param Zend_Db_Select $select
     * @param Collection|int|array $collections Either a Collection object,
     * or the collection id or an array of collection object or id.
     */
    public function filterByCollection($select, $collections)
    {
        if (!is_array($collections)) {
            $collections = array($collections);
        }

        $collectionIds = array_map(function ($collection) {
            if ($collection === 0 || $collection === '0') {
                return null;
            }
            if ($collection instanceof Collection) {
                return (int) $collection->id;
            }
            if (is_numeric($collection)) {
                return (int) $collection;
            }
            return;
        }, $collections);

        $hasEmpty = in_array(null, $collectionIds);
        $collectionIds = array_filter($collectionIds);
        if (!empty($collectionIds)) {
            $select->joinLeft(
                array('collections' => $this->getDb()->Collection),
                'items.collection_id = collections.id',
                array());
            $condition = 'collections.id IN (?)';
            if ($hasEmpty) {
                $condition .= ' OR items.collection_id IS NULL';
            }
            $select->where($condition, $collectionIds);
        }
        // Check no collection only.
        elseif ($hasEmpty) {
            $select->where('items.collection_id IS NULL');
        }
    }

    /**
     * Filter the SELECT statement based on the item Type
     *
     * @param Zend_Db_Select $select
     * @param Type|int|string|array $types One or multiple Item Type object,
     * Item Type ID or Item Type name.
     */
    public function filterByItemType($select, $types)
    {
        if (!is_array($types)) {
            $types = array($types);
        }

        $typeIdsOrNames = array_map(function ($type) {
            if ($type === 0 || $type === '0') {
                return null;
            }
            if ($type instanceof ItemType) {
                return (int) $type->id;
            }
            if (is_numeric($type)) {
                return (int) $type;
            }
            if (is_string($type)) {
                return $type;
            }
            return;
        }, $types);

        $hasEmpty = in_array(null, $typeIdsOrNames);
        $typeIdsOrNames = array_filter($typeIdsOrNames);
        if ($typeIdsOrNames) {
            $select->joinLeft(array(
                'item_types' => $this->getDb()->ItemType),
                'items.item_type_id = item_types.id',
                array());
            $typeIds = array_filter($typeIdsOrNames, 'is_integer');
            $typeNames = array_diff($typeIdsOrNames, $typeIds);
            if (!empty($typeIds)) {
                if (!empty($typeNames)) {
                    $conditions = 'item_types.id IN (' . implode(',', $typeIds) . ') OR item_types.name IN (?)';
                    $bind = array($typeNames);
                } else {
                    $conditions = 'item_types.id IN (?)';
                    $bind = array($typeIds);
                }
            } else {
                $conditions = 'item_types.name IN (?)';
                $bind = array($typeNames);
            }
            if ($hasEmpty) {
                $conditions .= ' OR items.item_type_id IS NULL';
            }
            $select->where($conditions, $bind);
        }
        // Check no collection only.
        elseif ($hasEmpty) {
            $select->where('items.item_type_id IS NULL');
        }
    }

    /**
     * Query must look like the following in order to correctly retrieve items
     * that have all the tags provided (in this example, all items that are
     * tagged both 'foo' and 'bar'):
     *
     *    SELECT i.id
     *    FROM omeka_items i
     *    WHERE
     *    (
     *    i.id IN
     *        (SELECT tg.record_id as id
     *        FROM omeka_records_tags tg
     *        INNER JOIN omeka_tags t ON t.id = tg.tag_id
     *        WHERE t.name = 'foo' AND tg.record_type = 'Item')
     *    AND i.id IN
     *       (SELECT tg.record_id as id
     *       FROM omeka_records_tags tg
     *       INNER JOIN omeka_tags t ON t.id = tg.tag_id
     *       WHERE t.name = 'bar' AND tg.record_type = 'Item')
     *    )
     *      ...
     *
     *
     * @param Omeka_Db_Select
     * @param string|array A comma-delimited string or an array of tag names.
     */
    public function filterByTags($select, $tags)
    {
        // Split the tags into an array if they aren't already
        if (!is_array($tags)) {
            $tags = explode(get_option('tag_delimiter'), $tags);
        }

        $db = $this->getDb();

        // For each of the tags, create a SELECT subquery using Omeka_Db_Select.
        // This subquery should only return item IDs, so that the subquery can be
        // appended to the main query by WHERE i.id IN (SUBQUERY).
        foreach ($tags as $tagName) {
            $subSelect = new Omeka_Db_Select;
            $subSelect->from(array('records_tags' => $db->RecordsTags), array('items.id' => 'records_tags.record_id'))
                ->joinInner(array('tags' => $db->Tag), 'tags.id = records_tags.tag_id', array())
                ->where('tags.name = ? AND records_tags.`record_type` = "Item"', trim($tagName));

            $select->where('items.id IN (' . (string) $subSelect . ')');
        }
    }

    /**
     * Filter SELECT statement based on items that are not tagged with a specific
     * set of tags
     *
     * @param Zend_Db_Select
     * @param array|string Set of tag names (either array or comma-delimited string)
     */
    public function filterByExcludedTags($select, $tags)
    {
        $db = $this->getDb();

        if (!is_array($tags)) {
            $tags = explode(get_option('tag_delimiter'), $tags);
        }
        $subSelect = new Omeka_Db_Select;
        $subSelect->from(array('items' => $db->Item), 'items.id')
                         ->joinInner(array('records_tags' => $db->RecordsTags),
                                     'records_tags.record_id = items.id AND records_tags.record_type = "Item"',
                                     array())
                         ->joinInner(array('tags' => $db->Tag),
                                     'records_tags.tag_id = tags.id',
                                     array());

        foreach ($tags as $key => $tag) {
            $subSelect->where('tags.name LIKE ?', $tag);
        }

        $select->where('items.id NOT IN ('.$subSelect->__toString().')');
    }

    /**
     * Filter SELECT statement based on whether items have a derivative image
     * file.
     *
     * @param Zend_Db_Select
     * @param bool $hasDerivativeImage Whether items should have a derivative
     * image file.
     */
    public function filterByHasDerivativeImage($select, $hasDerivativeImage = true)
    {
        $hasDerivativeImage = $hasDerivativeImage ? '1' : '0';

        $db = $this->getDb();

        $select->joinLeft(array('files' => "$db->File"), 'files.item_id = items.id', array());
        $select->where('files.has_derivative_image = ?', $hasDerivativeImage);
    }

    /**
     * @param Omeka_Db_Select
     * @param array
     */
    public function applySearchFilters($select, $params)
    {
        $boolean = new Omeka_Filter_Boolean;
        if(is_array($params) || is_object($params)){
            foreach ($params as $key => $value) {
                if ($value === null || (is_string($value) && trim($value) == '')) {
                    continue;
                }
                switch ($key) {
                    case 'user':
                    case 'owner':
                    case 'user_id':
                    case 'owner_id':
                        $this->filterByUser($select, $value, 'owner_id');
                        break;
                    case 'public':
                        $this->filterByPublic($select, $boolean->filter($value));
                        break;
                    case 'featured':
                        $this->filterByFeatured($select, $boolean->filter($value));
                        break;
                    case 'collection':
                    case 'collection_id':
                        $this->filterByCollection($select, $value);
                        break;
                    case 'type':
                    case 'item_type':
                    case 'item_type_id':
                        $this->filterByItemType($select, $value);
                        break;
                    case 'tag':
                    case 'tags':
                        $this->filterByTags($select, $value);
                        break;
                    case 'excludeTags':
                        $this->filterByExcludedTags($select, $value);
                        break;
                    case 'hasImage':
                        $this->filterByHasDerivativeImage($select, $boolean->filter($value));
                        break;
                    case 'range':
                        $this->filterByRange($select, $value);
                        break;
                    case 'added_since':
                        $this->filterBySince($select, $value, 'added');
                        break;
                    case 'modified_since':
                        $this->filterBySince($select, $value, 'modified');
                        break;
                }
            }
        }
        $this->filterBySearch($select, $params);

        // If we returning the data itself, we need to group by the item ID
        $select->group('items.id');
        //echo $select;
    }

    /**
     * Enables sorting based on ElementSet,Element field strings.
     *
     * @param Omeka_Db_Select $select
     * @param string $sortField Field to sort on
     * @param string $sortDir Sorting direction (ASC or DESC)
     */
    public function applySorting($select, $sortField, $sortDir)
    {
        parent::applySorting($select, $sortField, $sortDir);

        $db = $this->getDb();
        $fieldData = explode(',', $sortField);
        if (count($fieldData) == 2) {
            $element = $db->getTable('Element')->findByElementSetNameAndElementName($fieldData[0], $fieldData[1]);
            if ($element) {
                $select->joinLeft(array('et_sort' => $db->ElementText),
                                  "et_sort.record_id = items.id AND et_sort.record_type = 'Item' AND et_sort.element_id = {$element->id}",
                                  array())
                       ->group('items.id')
                       ->order(array("IF(ISNULL(et_sort.text), 1, 0) $sortDir",
                                     "et_sort.text $sortDir"));
            }
        }
    }

    /**
     * This is a kind of simple factory that spits out proper beginnings
     * of SQL statements when retrieving items
     *
     * @return Omeka_Db_Select
     */
    public function getSelect()
    {
        $select = parent::getSelect();
        $permissions = new Omeka_Db_Select_PublicPermissions('Items');
        $permissions->apply($select, 'items');

        return $select;
    }

    /**
     * Return the first item accessible to the current user.
     *
     * @return Item|null
     */
    public function findFirst()
    {
        $select = $this->getSelect();
        $select->order('items.id ASC');
        $select->limit(1);
        return $this->fetchObject($select);
    }

    /**
     * Return the last item accessible to the current user.
     *
     * @return Item|null
     */
    public function findLast()
    {
        $select = $this->getSelect();
        $select->order('items.id DESC');
        $select->limit(1);
        return $this->fetchObject($select);
    }

    public function findPrevious($item)
    {
        return $this->findNearby($item, 'previous');
    }

    public function findNext($item)
    {
        return $this->findNearby($item, 'next');
    }

    protected function findNearby($item, $position = 'next')
    {
        //This will only pull the title and id for the item
        $select = $this->getSelect();

        $select->limit(1);

        switch ($position) {
            case 'next':
                $select->where('items.id > ?', (int) $item->id);
                $select->order('items.id ASC');
                break;

            case 'previous':
                $select->where('items.id < ?', (int) $item->id);
                $select->order('items.id DESC');
                break;

            default:
                throw new Omeka_Record_Exception('Invalid position provided to ItemTable::findNearby()!');
                break;
        }

        return $this->fetchObject($select);
    }
}
