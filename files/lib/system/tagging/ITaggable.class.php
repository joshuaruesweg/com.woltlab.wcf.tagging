<?php
namespace wcf\system\tagging;

/**
 * Any object type that is taggable, can implement this interface.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.tagging
 * @subpackage	system.tagging
 * @category	Community Framework
 */
interface ITaggable {
	/**
	 * Returns the type id of this taggable object.
	 * 
	 * @return integer
	 */
	public function getObjectTypeID();
}
