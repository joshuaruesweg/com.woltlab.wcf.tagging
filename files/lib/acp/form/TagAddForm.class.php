<?php
namespace wcf\acp\form;
use wcf\data\language\Language;
use wcf\data\language\LanguageList;
use wcf\data\tag\Tag;
use wcf\data\tag\TagAction;
use wcf\data\tag\TagEditor;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;
use wcf\util\ArrayUtil;
use wcf\util\StringUtil;

/**
 * Shows the tag add form.
 *
 * @author	Tim Düsterhus
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.tagging
 * @subpackage	acp.form
 * @category 	Community Framework
 */
class TagAddForm extends ACPForm {
	/**
	 * @see wcf\acp\form\ACPForm::$activeMenuItem
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.tag.add';
	
	/**
	 * @see wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('admin.content.tag.canAddTag');
	
	/**
	 * name value
	 * @var	string
	 */
	public $name = '';
	
	/**
	 * language value
	 * @var	string
	 */
	public $languageID = 0;
	
	/**
	 * synonyms
	 * @var	array<string>
	 */
	public $synonyms = array();
	
	/**
	 * @see wcf\page\IPage::readData()
	 */
	public function readData() {
		$this->languages = new LanguageList();
		$this->languages->readObjects();
		
		parent::readData();
	}
	
	/**
	 * @see wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['name'])) $this->name = StringUtil::trim($_POST['name']);
		if (isset($_POST['language'])) $this->languageID = intval($_POST['language']);
		// actually these are synonyms
		if (isset($_POST['tags']) && is_array($_POST['tags'])) $this->synonyms = ArrayUtil::trim($_POST['tags']);
	}
	
	/**
	 * @see wcf\form\IForm::validate()
	 */
	public function validate() {
		parent::validate();
		
		// validate fields
		// name must not be empty
		if (empty($this->name)) {
			throw new UserInputException('name');
		}
		
		// check duplicate
		$tag = Tag::getTag($this->name, $this->languageID);
		if ($tag !== null) {
			throw new UserInputException('name', 'duplicate');
		}
		
		// validate language
		if ($this->languageID !== 0) {
			$language = new Language($this->languageID);
			if (!$language->languageID) {
				throw new UserInputException('language', 'notFound');
			}
		}
		
		// validate synonyms
		foreach ($this->synonyms as $synonym) {
			if (StringUtil::toLowerCase($synonym) == StringUtil::toLowerCase($this->name)) throw new UserInputException('synonyms', 'duplicate');
		}
	}
	
	/**
	 * @see wcf\form\IForm::save()
	 */
	public function save() {
		parent::save();
		
		// save tag
		$this->objectAction = new TagAction(array(), 'create', array('data' => array(
			'name' => $this->name,
			'languageID' => $this->languageID
		)));
		$this->objectAction->executeAction();
		$returnValues = $this->objectAction->getReturnValues();
		$editor = new TagEditor($returnValues['returnValues']);
		
		foreach ($this->synonyms as $synonym) {
			if (empty($synonym)) continue;
			
			// find existing tag
			$synonymObj = Tag::getTag($synonym, $this->languageID);
			if ($synonymObj === null) {
				$synonymAction = new TagAction(array(), 'create', array('data' => array(
					'name' => $synonym,
					'languageID' => $this->languageID,
					'synonymFor' => $editor->tagID
				)));
				$synonymAction->executeAction();
			}
			else {
				$editor->addSynonym($synonymObj);
			}
		}
		
		$this->saved();
		
		// reset values
		$this->languageID = 0;
		$this->name = '';
		$this->synonyms = array();
		
		// show success
		WCF::getTPL()->assign(array(
			'success' => true
		));
	}
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'action' => 'add',
			'name' => $this->name,
			'languageID' => $this->languageID,
			'languages' => $this->languages,
			'synonyms' => $this->synonyms
		));
	}
}
