<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use JoomlaCMSTableTable;
use JoomlaCMSFactory;

/**
 * Advanced Portfolio Installer Script
 *
 * @package		Joomla.Install
 * @subpakage	AdvPortfolio
 */
class Com_AdvPortfolioInstallerScript
{

	/**
	 * Install.
	 *
	 * @param	$parent
	 */
	public function install($parent) {
		$table = Table::getInstance('contenttype');

		if ($table) {
			$table->load(['type_alias' => 'com_advportfolio.project']);

			if (!$table->type_id) {
				$data = [
					'type_title' => 'Portfolio Project',
					'type_alias' => 'com_advportfolio.project',
					'table' => '{"special":{"dbtable":"#__advportfolio_projects","key":"id","type":"Project","prefix":"ProjectsTable","config":"array()"},"common":{"dbtable":"#__core_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}',
					'rules' => '',
					'field_mappings' => '{"common":[{"core_content_item_id":"id","core_title":"title","core_state":"state","core_alias":"alias","core_created_time":"created","core_modified_time":"modified","core_body":"description", "core_hits":"null","core_publish_up":"null","core_publish_down":"null","core_access":"access", "core_params":"attribs", "core_featured":"null", "core_metadata":"metadata", "core_language":"language", "core_images":"images", "core_urls":"link", "core_version":"null", "core_ordering":"ordering", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"catid", "core_xreference":"null", "asset_id":"null"}], "special": []}',
					'router' => 'AdvPortfolioHelperRoute::getProjectRoute',
				];

				$table->bind($data);

				if ($table->check()) {
					$table->store();
				}
			}
		}
	}
}