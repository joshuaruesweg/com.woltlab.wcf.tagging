<?php
namespace wcf\data\tag;
use wcf\data\DatabaseObjectEditor;
use wcf\system\WCF;

/**
 * Provides functions to edit tags.
 *
 * @author	Tim Düsterhus, Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.tagging
 * @subpackage	data.tag
 * @category 	Community Framework
 */
class TagEditor extends DatabaseObjectEditor {
	/**
	 * @see	wcf\data\DatabaseObjectEditor::$baseClass
	 */
	protected static $baseClass = 'wcf\data\tag\Tag';
	
	/**
	 * Adds the given tag, and all of it's synonyms as a synonym.
	 * 
	 * @param wcf\data\tag\Tag	$synonym
	 */
	public function addSynonym(Tag $synonym) {
		$sql = "UPDATE
				wcf".WCF_N."_tag_to_object
			SET
				tagID = ?
			WHERE
				tagID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array(
			$this->tagID,
			$synonym->tagID
		));
		
		$editor = new TagEditor($synonym);
		$editor->update(array(
			'synonymFor' => $this->tagID
		));
		
		$synonymList = new TagList();
		$synonymList->getConditionBuilder()->add('synonymFor = ?', array($synonym->tagID));
		$synonymList->readObjects();
		
		foreach ($synonymList as $synonym) {
			$this->addSynonym($synonym);
		}
	}
}
