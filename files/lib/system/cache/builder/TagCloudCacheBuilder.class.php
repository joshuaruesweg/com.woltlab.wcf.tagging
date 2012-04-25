<?php
namespace wcf\system\cache\builder;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\tag\TagCloudTag;
use wcf\data\tag\Tag;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Caches the tag cloud.
 * 
 * @author 	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.tagging
 * @subpackage	system.cache.builder
 * @category 	Community Framework
 */
class TagCloudCacheBuilder implements ICacheBuilder {
	/**
	 * list of tags
	 * @var	array<wcf\data\tag\TagCloudTag>
	 */
	protected $tags = array();
	
	/**
	 * language ids
	 * @var integer
	 */
	protected $languageIDs = array();
	
	/**
	 * object type ids
	 * @var integer
	 */
	protected $objectTypeIDs = array();

	/**
	 * @see wcf\system\cache\builder\CacheBuilder::getData()
	 */
	public function getData(array $cacheResource) {
		list($cache, $languageIDs) = explode('-', $cacheResource['cache']);
		$this->languageIDs = explode(',', $languageIDs);
		
		// get all taggable types
		$objectTypes = ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.tagging.taggableObject');
		foreach ($objectTypes as $objectType) {
			$this->objectTypeIDs[] = $objectType->objectTypeID;
		}
		
		// get tags
		$this->getTags();
		
		return $this->tags;
	}

	protected function getTags() {
		if (count($this->objectTypeIDs) > 0) {
			// get tag ids
			$tagIDs = array();
			$conditionBuilder = new PreparedStatementConditionBuilder();
			$conditionBuilder->add('object.objectTypeID IN (?)', array($this->objectTypeIDs));
			$conditionBuilder->add('object.languageID IN (?)', array($this->languageIDs));
			$sql = "SELECT		COUNT(*) AS counter, object.tagID
				FROM 		wcf".WCF_N."_tag_to_object object
				".$conditionBuilder->__toString()."
				GROUP BY 	object.tagID
				ORDER BY 	counter DESC";
			$statement = WCF::getDB()->prepareStatement($sql, 500);
			$statement->execute($conditionBuilder->getParameters());
			while ($row = $statement->fetchArray()) {
				$tagIDs[$row['tagID']] = $row['counter'];
			}
			
			// get tags
			if (count($tagIDs)) {
				$sql = "SELECT	*
					FROM	wcf".WCF_N."_tag
					WHERE	tagID IN (?".(count($tagIDs) > 1 ? str_repeat(',?', count($tagIDs) - 1) : '').")";
				$statement = WCF::getDB()->prepareStatement($sql);
				$statement->execute(array_keys($tagIDs));
				while ($row = $statement->fetchArray()) {
					$row['counter'] = $tagIDs[$row['tagID']];
					$this->tags[StringUtil::toLowerCase($row['name'])] = new TagCloudTag(new Tag(null, $row));
				}

				// sort by counter
				uasort($this->tags, array('self', 'compareTags'));
			}
		}
	}
	
	protected static function compareTags($tagA, $tagB) {
		if ($tagA->counter > $tagB->counter) return -1;
		if ($tagA->counter < $tagB->counter) return 1;
		return 0;
	}
}
