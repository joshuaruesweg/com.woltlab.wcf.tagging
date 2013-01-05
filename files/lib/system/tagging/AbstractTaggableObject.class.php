<?php
namespace wcf\system\tagging;

/**
 * Convenient abstract class that already implements certain functions of ITaggable.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.tagging
 * @subpackage	system.tagging
 * @category	Community Framework
 */
abstract class AbstractTaggableObject implements ITaggable {
	/**
	 * @see	wcf\syste\tagging\ITaggable::getObjectTypeID()
	 */
	public function getObjectTypeID() {
		return $this->objectTypeID;
	}
}
