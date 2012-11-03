<?php
namespace wcf\data\tag;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\ISearchAction;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\exception\ValidateActionException;
use wcf\system\WCF;

/**
 * Executes tagging-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.tagging
 * @subpackage	data.tag
 * @category	Community Framework
 */
class TagAction extends AbstractDatabaseObjectAction implements ISearchAction {
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction
	 */
	protected $allowGuestAccess = array('getSearchResultList');
	
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::$className
	 */
	protected $className = 'wcf\data\tag\TagEditor';
	
	/**
	 * @see	\wcf\data\AbstractDatabaseObjectAction::$permissionsDelete
	 */
	protected $permissionsDelete = array('admin.content.tag.canDeleteTag');
	
	/**
	 * @see	\wcf\data\AbstractDatabaseObjectAction::$permissionsUpdate
	 */
	protected $permissionsUpdate = array('admin.content.tag.canEditTag');
	
	/**
	 * @see	wcf\data\IPositionAction::validateGetSearchResultList()
	 */
	public function validateGetSearchResultList() {
		if (!isset($this->parameters['data']['searchString'])) {
			throw new ValidateActionException("Missing parameter 'searchString'");
		}
		
		if (isset($this->parameters['data']['excludedSearchValues']) && !is_array($this->parameters['data']['excludedSearchValues'])) {
			throw new ValidateActionException("Invalid parameter 'excludedSearchValues' given");
		}
	}
	
	/**
	 * @see	wcf\data\IPositionAction::getSearchResultList()
	 */
	public function getSearchResultList() {
		$searchString = $this->parameters['data']['searchString'];
		$excludedSearchValues = array();
		if (isset($this->parameters['data']['excludedSearchValues'])) {
			$excludedSearchValues = $this->parameters['data']['excludedSearchValues'];
		}
		$list = array();
		
		$conditionBuilder = new PreparedStatementConditionBuilder();
		$conditionBuilder->add("name LIKE ?", array($searchString.'%'));
		if (!empty($excludedSearchValues)) {
			$conditionBuilder->add("name NOT IN (?)", array($excludedSearchValues));
		}
		
		// find tags
		$sql = "SELECT	tagID, name
			FROM	wcf".WCF_N."_tag
			".$conditionBuilder;
		$statement = WCF::getDB()->prepareStatement($sql, 5);
		$statement->execute($conditionBuilder->getParameters());
		while ($row = $statement->fetchArray()) {
			$list[] = array(
				'label' => $row['name'],
				'objectID' => $row['tagID']
			);
		}
		
		return $list;
	}
}
