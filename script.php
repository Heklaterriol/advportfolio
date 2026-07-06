<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Table\Table;

/**
 * Advanced Portfolio Installer Script
 *
 * @package		Joomla.Install
 * @subpakage	Skyline.AdvPortfolio
 */
class InstallerScript implements InstallerScriptInterface
{
	/**
	 * Install.
	 *
	 * @param	InstallerAdapter	$parent
	 * @return	bool
	 */
	public function install(InstallerAdapter $parent): bool
	{
		/** @var \Joomla\CMS\Table\ContentType $table */
		$table	= Table::getInstance('contenttype');

		if ($table) {
			$table->load(array('type_alias' => 'com_advportfolio.project'));

			if (!$table->type_id) {
				$data	= array(
					'type_title'		=> 'Portfolio Project',
					'type_alias'		=> 'com_advportfolio.project',
					'table'				=> '{"special":{"dbtable":"#__advportfolio_projects","key":"id","type":"Project","prefix":"ProjectsTable","config":"array()"},"common":{"dbtable":"#__core_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}',
					'rules'				=> '',
					'field_mappings'	=> '{"common":[{"core_content_item_id":"id","core_title":"title","core_state":"state","core_alias":"alias","core_created_time":"created","core_modified_time":"modified","core_body":"description", "core_hits":"null","core_publish_up":"null","core_publish_down":"null","core_access":"access", "core_params":"attribs", "core_featured":"null", "core_metadata":"metadata", "core_language":"language", "core_images":"images", "core_urls":"link", "core_version":"null", "core_ordering":"ordering", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"catid", "core_xreference":"null", "asset_id":"null"}], "special": []}',
					'router'			=> '\\Joomla\\Component\\Advportfolio\\Site\\Service\\Router::getProjectRoute',
				);

				$table->bind($data);

				if ($table->check()) {
					$table->store();
				}
			}
		}

		return true;
	}

	/**
	 * Update.
	 *
	 * @param	InstallerAdapter	$parent
	 * @return	bool
	 */
	public function update(InstallerAdapter $parent): bool
	{
		return true;
	}

	/**
	 * Uninstall.
	 *
	 * @param	InstallerAdapter	$parent
	 * @return	bool
	 */
	public function uninstall(InstallerAdapter $parent): bool
	{
		return true;
	}

	/**
	 * Preflight.
	 *
	 * @param	string				$type
	 * @param	InstallerAdapter	$parent
	 * @return	bool
	 */
	public function preflight(string $type, InstallerAdapter $parent): bool
	{
		return true;
	}

	/**
	 * Postflight.
	 *
	 * @param	string				$type
	 * @param	InstallerAdapter	$parent
	 * @return	bool
	 */
	public function postflight(string $type, InstallerAdapter $parent): bool
	{
		return true;
	}
}