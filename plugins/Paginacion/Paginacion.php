<?php
//class Paginacion extends :php:class:`Omeka_Plugin_AbstractPlugin`
class Paginacion extends Omeka_Plugin_AbstractPlugin
{
	protected $_hooks = array('install', 'uninstall');

	//protected $_options = array('paginacion_option'=>'option_value');

	public function install() {
		$this->_installOptions();
	}

	public function uninstall() {
		$this->_uninstallOptions();
	}
	/*protected $_filters = array(
    		'items_browse_per_page',
	);

	public function filterItemsBrowsePerPage($perPage, $args)
	{
    		return 5;
	}*/
}
?>
