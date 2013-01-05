<?php
namespace wcf\system\cache\builder;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\tag\Tag;
use wcf\data\tag\TagCloudTag;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\WCF;

/**
 * Caches the tag cloud.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.tagging
 * @subpackage	system.cache.builder
 * @category	Community Framework
 */
class TagCloudCacheBuilder implements ICacheBuilder {
	/**
	 * list of tags
	 * @var	array<wcf\data\tag\TagCloudTag>
	 */
	protected $tags = array();
	
	/**
	 * language ids
	 * @var	integer
	 */
	protected $languageIDs = array();
	
	/**
	 * object type ids
	 * @var	integer
	 */
	protected $objectTypeIDs = array();
	
	/**
	 * @see	wcf\system\cache\builder\CacheBuilder::getData()
	 */
	public function getData(array $cacheResource) {
		list(, $languageIDsStr) = explode('-', $cacheResource['cache']);
		$this->languageIDs = $this->parseLanguageIDs($languageIDsStr);
		
		// get all taggable types
		$objectTypes = ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.tagging.taggableObject');
		foreach ($objectTypes as $objectType) {
			$this->objectTypeIDs[] = $objectType->objectTypeID;
		}
		
		// get tags
		$this->getTags();
		
		return $this->tags;
	}
	
	/**
	 * Parses a comma-seperated list of language ids. If one given language
	 * ids evaluates to '0' all ids will be discarded.
	 * 
	 * @param	string		$languageIDsStr
	 * @return	array<integer>
	 */
	protected function parseLanguageIDs($languageIDsStr) {
		$languageIDs = explode(',', $languageIDsStr);
		
		// handle special '0' value
		if (in_array(0, $languageIDs)) {
			// discard all language ids
			$languageIDs = array();
		}
		
		return $languageIDs;
	}
	
	protected function getTags() {
		if (!empty($this->objectTypeIDs)) {
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
			if (!empty($tagIDs)) {
				$sql = "SELECT	*
					FROM	wcf".WCF_N."_tag
					WHERE	tagID IN (?".(count($tagIDs) > 1 ? str_repeat(',?', count($tagIDs) - 1) : '').")";
				$statement = WCF::getDB()->prepareStatement($sql);
				$statement->execute(array_keys($tagIDs));
				while ($row = $statement->fetchArray()) {
					$row['counter'] = $tagIDs[$row['tagID']];
					$this->tags[$row['name']] = new TagCloudTag(new Tag(null, $row));
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
