<?php
namespace wcf\data\tag;
use wcf\data\AbstractDatabaseObjectAction;

/**
 * Executes tagging-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.tagging
 * @subpackage	data.tag
 * @category 	Community Framework
 */
class TagAction extends AbstractDatabaseObjectAction {
	/**
	 * @see wcf\data\AbstractDatabaseObjectAction::$className
	 */
	protected $className = 'wcf\data\tag\TagEditor';
}
