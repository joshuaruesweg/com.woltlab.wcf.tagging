<?php
namespace wcf\system\tagging;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\tag\TagEditor;
use wcf\data\tag\Tag;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * Manages the tagging of objects.
 * 
 * @author 	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.tagging
 * @subpackage	system.tagging
 * @category 	Community Framework
 */
class TagEngine extends SingletonFactory {
	/**
	 * Adds tags to a tagged object.
	 * 
	 * @param	string		$objectType
	 * @param	integer		$objectID
	 * @param 	array 		$tags
	 * @param	integer		$languageID
	 */
	public function addObjectTags($objectType, $objectID, array $tags, $languageID = 0) {
		// get object type
		$objectTypeObj = ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.tagging.taggableObject', $objectType);
		
		// get tag ids
		$tagIDs = array();
		foreach ($tags as $tag) {
			if (empty($tag)) continue;
			
			// find existing tag
			$tagObj = Tag::getTag($tag, $languageID);
			if ($tagObj === null) {
				// create new tag
				$tagObj = TagEditor::create(array(
					'languageID' => $languageID,
					'name' => $tag
				));
			}
			
			$tagIDs[] = $tagObj->tagID;
		}
		$tagIDs = array_unique($tagIDs);
		if (!count($tagIDs)) return;
		
		// save tags
		$sql = "INSERT INTO	wcf".WCF_N."_tag_to_object
					(objectID, tagID, objectTypeID, languageID)
			VALUES 		(?, ?, ?, ?)";
		WCF::getDB()->beginTransaction();
		$statement = WCF::getDB()->prepareStatement($sql);
		foreach ($tagIDs as $tagID) {
			$statement->execute(array($objectID, $tagID, $objectTypeObj->objectTypeID, $languageID));
		}
		WCF::getDB()->commitTransaction();
	}
	
	/**
	 * Deletes all tags assigned to given tagged object.
	 *
	 * @param 	wcf\system\tagging\ITagged	$object 	object whose assigned to tags should be deleted
	 * @param	array<integer>			$languageIDs
	 */
	public function deleteObjectTags(ITagged $object, array $languageIDs = array()) {
		if (!count($languageIDs)) $languageIDs = array(0);
		
		$sql = "DELETE FROM 	wcf".WCF_N."_tag_to_object
			WHERE 		objectTypeID = ?
					AND languageID IN (?".(count($languageIDs) > 1 ? str_repeat(',?', count($languageIDs) - 1) : '').")
					AND objectID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array($object->getTaggable()->getObjectTypeID(), $languageIDs, $object->getObjectID()));
	}
	
	/**
	 * Returns all tags set for given object.
	 * 
	 * @param	string		$objectType
	 * @param	integer		$objectID
	 * @param	integer		$languageID
	 * @return	array<string>
	 */
	public function getObjectTags($objectType, $objectID, $languageID = 0) {
		if ($languageID === null) $languageID = 0;
		
		// get object type
		$objectTypeObj = ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.tagging.taggableObject', $objectType);
		
		// get tags
		$sql = "SELECT		tag.name
			FROM		wcf".WCF_N."_tag_to_object tag_to_object
			LEFT JOIN	wcf".WCF_N."_tag tag
			ON		(tag.tagID = tag_to_object.tagID)
			WHERE		tag_to_object.objectTypeID = ?
					AND tag_to_object.objectID = ?
					AND tag_to_object.languageID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array(
			$objectTypeObj->objectTypeID,
			$objectID,
			$languageID
		));
		
		$tags = array();
		
		while ($row = $statement->fetchArray()) {
			$tags[] = $row['name'];
		}
		
		return $tags;
	}
}
