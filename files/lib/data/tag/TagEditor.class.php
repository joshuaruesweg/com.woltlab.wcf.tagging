<?php
namespace wcf\data\tag;
use wcf\data\DatabaseObjectEditor;

/**
 * Provides functions to edit tags.
 *
 * @author	Marcel Werk
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
}
