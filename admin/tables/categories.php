<?php
/**
 * @name          : Apptha Eventz
 * @version       : 1.0
 * @package       : apptha
 * @since         : Joomla 1.6
 * @subpackage    : Apptha Eventz.
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2012 Powered by Apptha
 * @license       : GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @abstract      : Apptha Eventz.
 * @Creation Date : November 3 2012
 **/
defined('_JEXEC') or die('Restricted Access');
class Tablecategories extends JTable {
    var $id = null;
    var $name = null;
    var $color = null;
    var $parent_id = null;
    var $description = null;
    var $ordering = null;
    var $published = null;
    var $created_date = null;
    var $updated_date = null;
    
    function Tablecategories(&$db) {
		parent::__construct('#__em_categories', 'id', $db);
	}
}
?>
