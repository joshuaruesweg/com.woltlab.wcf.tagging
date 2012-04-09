<?php
namespace wcf\system\tagging;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\cache\CacheHandler;
use wcf\util\StringUtil;

/**
 * This class provides the function to filter the tag cloud by object types.
 * 
 * @author 	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.tagging
 * @subpackage	system.tagging
 * @category 	Community Framework
 */
class TypedTagCloud extends TagCloud {
	/**
	 * object type ids
	 * @var	array<integer>
	 */
	protected $objectTypeIDs = array();
	
	/**
	 * Contructs a new TypedTagCloud object.
	 *
	 * @param	string		$objectType
	 * @param	array<integer>	$languageIDs
	 */
	public function __construct($objectType, array $languageIDs = array()) {
		$objectTypeObj = ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.tagging.taggableObject', $objectType);
		$this->objectTypeIDs[] = $objectTypeObj->objectTypeID;
		
		parent::__construct($languageIDs);
	}
	
	/**
	 * Loads the tag cloud cache.
	 */
	protected function loadCache() {
		$cacheName = 'typedTagCloud-'.implode(',', $this->objectTypeIDs).'-'.implode(',', $this->languageIDs);
		
		CacheHandler::getInstance()->addResource($cacheName, WCF_DIR.'cache/cache.typedTagCloud-'.StringUtil::getHash(implode(',', $this->objectTypeIDs)).'-'.StringUtil::getHash(implode(',', $this->languageIDs)).'.php', 'wcf\system\cache\builder\TypedTagCloudCacheBuilder', 3600);
		$this->tags = CacheHandler::getInstance()->get($cacheName);
	}
}