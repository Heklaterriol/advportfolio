<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/**
 * Class supporting a list of images.
 *
 * @package		Joomla.Administrator
 * @subpakage	Skyline.AdvPortfolio
 */
class AdvPortfolioModelImageHandler extends JModelList {

	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JControllerLegacy
	 */
	public function __construct($config = array()) {
		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null) {
		$app = JFactory::getApplication();

		// Load the filter state.
		$search		= $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		// folder
		$folder		= $this->getUserStateFromRequest($this->context . '.folder', 'folder');
		$filter		= new JFilterInput();
		$folder		= $filter->clean($folder, 'path');

		$this->setState('folder', $folder);

		// List state information.
		parent::populateState();
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and different modules
	 * that might need different sets of data or different ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 * @return	string		A store id.
	 */
	protected function getStoreId($id = '') {
		// compile the store id.
		$id	.= ':' . $this->getState('filter.search');
		$id	.= ':' . $this->getState('folder');

		return parent::getStoreId($id);
	}

	/**
	 * Get list of folders.
	 */
	public function getFolders() {
		$store	= $this->getStoreId();

		if (isset($this->cache[$store . '-folder'])) {
			return $this->cache[$store . '-folder'];
		}

		// initialize variables
		$basePath	= JPATH_ROOT . '/images/advportfolio/images/';
		$folder		= $this->getState('folder');

		if ($folder) {
			$basePath	= $basePath . '/' . $folder . '/';
		}

		$items		= JFolder::folders($basePath);
		$folders	= array();

		foreach ($items as $item) {
			$tmp		= new stdClass();
			$tmp->name	= $item;
			$tmp->path	= JPath::clean($basePath . $item);

			$folders[]	= $tmp;
		}

		$this->cache[$store . '-folder']	= $folders;

		return $this->cache[$store . '-folder'];
	}

	/**
	 * Method to get images.
	 */
	public function getItems() {
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (isset($this->cache[$store])) {
			return $this->cache[$store];
		}

		$list	= $this->_getList();
		$images	= array();

		$limit	= $this->getState('list.limit');
		if ($limit == 0) {
			$limit	= $this->getStart('list.total');
			$s		= 1;
		} else {
			$s		= $this->getState('list.start') + 1;
		}

		// get only a part of list images
		for ($i = ($s - 1), $n = $s + $limit, $total	= $this->getState('list.total'); $i < $n; $i++) {
			if ($i + 1 <= $total) {
				$list[$i]->size		= AdvPortfolioHelper::fileSize(filesize($list[$i]->path));

				$images[]			= $list[$i];
			}
		}

		$this->cache[$store]	= $images;

		return $this->cache[$store];
	}

	/**
	 * Method to get list of images
	 */
	protected function _getList() {
		static $list;

		// only process the list once per request
		if (is_array($list)) {
			return $list;
		}

		// get search from state
		$search		= $this->getState('filter.search');

		// Security
		if (!JFile::exists(JPATH_ROOT . '/images/advportfolio/images/.htaccess')) {
			$text	= 'Options -Indexes';
			JFile::write(JPATH_ROOT . '/images/advportfolio/images/.htaccess', $text);
		}

		// initialize variables
		$basePath	= realpath(JPATH_ROOT . '/images/advportfolio/images/');
		$folder		= $this->getState('folder');

		if ($folder) {
			$basePath	= $basePath . '/' . $folder . '/';
		}

		$images		= array();

		// get the list of files and folders from the given folder
		jimport('joomla.filesystem.folder');
		$files		= JFolder::files($basePath, '^(.)*((\.jpg)|(\.jpeg)|(\.gif)|(\.png)|(\.JPG)|(\.JPEG)|(\.GIF)|(\.PNG))$');

		if ($files !== false) {
			foreach ($files as $file) {
				if (is_file($basePath . '/' . $file) && substr($file, 0, 1) != '.') {
					if ($search == '' || stristr($file, $search)) {
						$tmp		= new JObject();
						$tmp->name	= $file;
						$tmp->path	= JPath::clean(($basePath . '/' . $file));

						$images[]	= $tmp;
					}
				}
			}
		}

		$list	= $images;
		$this->setState('list.total', count($images));

		return $images;
	}

	/**
	 * Method to get a pagination object for list images.
	 */
	public function getPagination() {
		// Get a storage key.
		$store = $this->getStoreId('getPagination');

		// Try to load the data from internal storage.
		if (isset($this->cache[$store])) {
			return $this->cache[$store];
		}

		// Create the pagination object.
		jimport('joomla.html.pagination');
		$page = new JPagination($this->getTotal(), $this->getStart(), $this->getState('list.limit'));

		// Add the object to the internal cache.
		$this->cache[$store] = $page;

		return $this->cache[$store];
	}

	/**
	 * Method to get total of images.
	 */
	public function getTotal() {
		$this->_getList();

		return $this->getState('list.total');
	}
}