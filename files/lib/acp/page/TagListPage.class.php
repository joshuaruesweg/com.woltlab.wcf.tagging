<?php
namespace wcf\acp\page;
use wcf\page\SortablePage;
use wcf\system\menu\acp\ACPMenu;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Shows a list of tags.
 * 
 * @author	Tim Düsterhus
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.tagging
 * @subpackage	acp.page
 * @category 	Community Framework
 */
class TagListPage extends SortablePage {
	/**
	 * @see wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('admin.system.canViewLog');
	
	/**
	 * @see wcf\page\SortablePage::$defaultSortField
	 */
	public $defaultSortField = 'name';
	
	/**
	 * @see	wcf\page\SortablePage::$validSortFields
	 */
	public $validSortFields = array('tagID', 'languageID', 'name', 'usageCount');
	
	/**
	 * @see	wcf\page\MultipleLinkPage::$objectListClassName
	 */
	public $objectListClassName = 'wcf\data\tag\TagList';
	
	/**
	 * search-query
	 * @var string
	 */
	public $search = '';
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'search' => $this->search
		));
	}
	
	/**
	 * @see wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['search'])) $this->search = StringUtil::trim($_REQUEST['search']);
	}
	
	/**
	 * @see	wcf\page\MultipleLinkPage::initObjectList()
	 */
	protected function initObjectList() {
		parent::initObjectList();
		
		$this->objectList->sqlSelects = "(SELECT COUNT(*) FROM wcf".WCF_N."_tag_to_object t2o WHERE t2o.tagID = tag.tagID) AS usageCount";
		$this->objectList->sqlSelects .= ", language.languageName, language.languageCode";
		$this->objectList->sqlSelects .= ", synonym.name AS synonymName";
		
		$this->objectList->sqlJoins = "LEFT JOIN wcf".WCF_N."_language language ON tag.languageID = language.languageID";
		$this->objectList->sqlJoins .= " LEFT JOIN wcf".WCF_N."_tag synonym ON tag.synonymFor = synonym.tagID";
		
		if ($this->search !== '') {
			$this->objectList->getConditionBuilder()->add('tag.name LIKE ?', array($this->search.'%'));
		}
	}
	
	/**
	 * @see wcf\page\IPage::show()
	 */
	public function show() {
		// enable menu item
		ACPMenu::getInstance()->setActiveMenuItem('wcf.acp.menu.link.tag.list');
		
		parent::show();
	}
}
