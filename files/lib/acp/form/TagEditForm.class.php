<?php
namespace wcf\acp\form;
use wcf\data\tag\Tag;
use wcf\data\tag\TagAction;
use wcf\data\tag\TagEditor;
use wcf\data\tag\TagList;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;

/**
 * Shows the tag edit form.
 * 
 * @author	Tim Düsterhus
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.tagging
 * @subpackage	acp.form
 * @category 	Community Framework
 */
class TagEditForm extends TagAddForm {
	/**
	 * @see wcf\acp\form\ACPForm::$activeMenuItem
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.tag.list';
	
	/**
	 * @see wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('admin.content.tag.canEditTag');
	
	/**
	 * tag id
	 * @var	integer
	 */
	public $tagID = 0;
	
	/**
	 * tag object
	 * @var	wcf\data\tag\Tag
	 */
	public $tagObj = null;
	
	/**
	 * @see wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['id'])) $this->tagID = intval($_REQUEST['id']);
		$this->tagObj = new Tag($this->tagID);
		if (!$this->tagObj->tagID) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see wcf\form\IForm::save()
	 */
	public function save() {
		ACPForm::save();
		
		// update tag
		$this->objectAction = new TagAction(array($this->tagID), 'update', array('data' => array(
			'name' => $this->name
		)));
		$this->objectAction->executeAction();
		
		// remove synonyms first
		$sql = "UPDATE
				wcf".WCF_N."_tag
			SET
				synonymFor = ?
			WHERE
				synonymFor = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array(
			null,
			$this->tagID
		));
		
		$editor = new TagEditor($this->tagObj);
		foreach ($this->synonyms as $synonym) {
			if (empty($synonym)) continue;
			
			// find existing tag
			$synonymObj = Tag::getTag($synonym, $this->languageID);
			if ($synonymObj === null) {
				$synonymAction = new TagAction(array(), 'create', array('data' => array(
					'name' => $synonym,
					'languageID' => $this->languageID,
					'synonymFor' => $this->tagID
				)));
				$synonymAction->executeAction();
			}
			else {
				$editor->addSynonym($synonymObj);
			}
		}
		
		$this->saved();
		
		// show success
		WCF::getTPL()->assign(array(
			'success' => true
		));
	}
	
	/**
	 * @see wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			$this->name = $this->tagObj->name;
			
			$synonymList = new TagList();
			$synonymList->getConditionBuilder()->add('synonymFor = ?', array($this->tagObj->tagID));
			$synonymList->readObjects();
			foreach ($synonymList as $synonym) {
				$this->synonyms[] = $synonym->name;
			}
		}
		
		$this->languageID = $this->tagObj->languageID;
	}
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'tagID' => $this->tagID,
			'action' => 'edit'
		));
	}
}
