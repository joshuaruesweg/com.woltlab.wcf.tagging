<?php
namespace wcf\system\cache\builder;

/**
 * Caches the typed tag cloud.
 * 
 * @author 	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.tagging
 * @subpackage	system.cache.builder
 * @category 	Community Framework
 */
class TypedTagCloudCacheBuilder extends TagCloudCacheBuilder {
	/**
	 * @see wcf\system\cache\builder\CacheBuilder::getData()
	 */
	public function getData(array $cacheResource) {
		list($cache, $objectTypeIDs, $languageIDs) = explode('-', $cacheResource['cache']);
		$this->objectTypeIDs = explode(',', $objectTypeIDs);
		$this->languageIDs = explode(',', $languageIDs);
		
		// get tags
		$this->getTags();

		return $this->tags;
	}
}
