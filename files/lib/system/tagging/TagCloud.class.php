<?php
namespace wcf\system\tagging;
use wcf\system\cache\CacheHandler;
use wcf\util\StringUtil;

/**
 * This class holds a list of tags that can be used for creating a tag cloud.
 * 
 * @author 	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.tagging
 * @subpackage	system.tagging
 * @category 	Community Framework
 */
class TagCloud {
	/**
	 * max font size
	 * @var integer
	 */
	const MAX_FONT_SIZE = 170;
	
	/**
	 * min font size
	 * @var integer
	 */
	const MIN_FONT_SIZE = 85;
	
	/**
	 * list of tags
	 * @var array<wcf\data\tag\TagCloudTag>
	 */
	protected $tags = array();

	/**
	 * max value of tag counter
	 * @var integer
	 */
	protected $maxCounter = 0;

	/**
	 * min value of tag counter
	 * @var integer
	 */
	protected $minCounter = 4294967295;
	
	/**
	 * active language ids
	 * @var	array<integer>
	 */
	protected $languageIDs = array();
	
	/**
	 * Contructs a new TagCloud object.
	 *
	 * @param	array<integer>	$languageIDs
	 */
	public function __construct(array $languageIDs = array()) {
		$this->languageIDs = $languageIDs;
		if (!count($this->languageIDs)) $this->languageIDs = array(0);
		
		// init cache
		$this->loadCache();
	}

	/**
	 * Loads the tag cloud cache.
	 */
	protected function loadCache() {
		$cacheName = 'tagCloud-'.implode(',', $this->languageIDs);
		
		CacheHandler::getInstance()->addResource($cacheName, WCF_DIR.'cache/cache.tagCloud-'.PACKAGE_ID.'-'.StringUtil::getHash(implode(',', $this->languageIDs)).'.php', 'wcf\system\cache\builder\TagCloudCacheBuilder', 3600);
		$this->tags = CacheHandler::getInstance()->get($cacheName);
	}
	
	/**
	 * Gets a list of weighted tags.
	 *
	 * @param	integer				$slice
	 * @return	array<wcf\data\tag\TagCloudTag>	the tags to get
	 */
	public function getTags($slice = 50) {
		// slice list
		$tags = array_slice($this->tags, 0, min($slice, count($this->tags)));
		
		// get min / max counter
		foreach ($tags as $tag) {
			if ($tag->counter > $this->maxCounter) $this->maxCounter = $tag->counter;
			if ($tag->counter < $this->minCounter) $this->minCounter = $tag->counter;
		}
		
		// assign sizes
		foreach ($tags as $tag) {
			$tag->setSize($this->calculateSize($tag->counter));
		}
		
		// sort alphabetically
		ksort($tags);
		
		// return tags
		return $tags;
	}
	
	/**
	 * Calculate the size of the tag in a weighted list
	 *
	 * @param	integer 	$counter 	the number of times a tag has been used
	 * @return	double 				the size to calculate
	 */
	private function calculateSize($counter) {
		if ($this->maxCounter == $this->minCounter) {
			return 100;
		}
		else {
			return (self::MAX_FONT_SIZE - self::MIN_FONT_SIZE) / ($this->maxCounter - $this->minCounter) * $counter + self::MIN_FONT_SIZE - ((self::MAX_FONT_SIZE - self::MIN_FONT_SIZE) / ($this->maxCounter - $this->minCounter)) * $this->minCounter;
		}
	}
}
