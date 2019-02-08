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
//no direct access
defined('_JEXEC') or die();
jimport('joomla.application.component.model');
jimport('joomla.user.helper');

class appthaeventzModelsearch extends JModel {
public function getEventSearch($searchvalue)
{
    $db = JFactory::getDBO();
    $query="SELECT  `event_name` 
            FROM  `#__em_events` 
            WHERE event_name LIKE '%".$searchvalue."%' AND  `active_status` =1
            AND  `published` =1";
    $db->setQuery($query);
    $search_result = $db->loadObjectList();
    return $search_result;
}

public function searchResult($serachVal)
{
    $db = JFactory::getDBO();
   $query="SELECT  `event_name` 
            FROM  `#__em_events` 
            WHERE (event_name LIKE '%".$serachVal."%' OR   description LIKE '%".$serachVal."%' ) AND  `active_status` =1
            AND  `published` =1";
    $db->setQuery($query);
    $search_result_event = $db->loadObjectList();
   
    $query="SELECT  `name`,description 
            FROM  `#__em_categories` 
            WHERE (name LIKE '%".$serachVal."%' OR description LIKE '%".$serachVal."%' )   AND  `active_status` =1
            AND  `published` =1";
    $db->setQuery($query);
    $search_result_categories = $db->loadObjectList();
   
    $query="SELECT  `location_name` 
            FROM  `#__em_locations` 
            WHERE location_name LIKE '%".$serachVal."%' ";
   $db->setQuery($query);
   $search_result_location = $db->loadObjectList();
   $search_result = array('location'=>$search_result_location,'categories'=>$search_result_categories,'event'=>$search_result_event);
    return $search_result;
    
}
    

}

?>