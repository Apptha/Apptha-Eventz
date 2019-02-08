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
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * SocialPinboard component helper.
 */
abstract class appthaeventzHelper {

    /**
     * Build an SQL query to load the user data.
     *
     * @return	JDatabaseQuery
     * @since	1.5&1.6&1.7
     */
    public static function getKey(){
       global $option, $mainframe;
        $db =  Jfactory::getDBO(); //to enable db connection
        $query = "SELECT json_data_general FROM #__em_eventsettings";
        $db->setQuery($query);
        $result = $db->loadResult();
        //decode the returned json data.
         $json_result = json_decode($result);
         return $json_result[0];
  
    }

    public static function getEventCount($id)
    {
        $db = JFactory::getDBO();
        $query="SELECT  count(id)
                FROM  `#__em_events`
                WHERE FIND_IN_SET($id, category_id) AND `active_status` =1
                AND  `published` =1";
        $db->setQuery($query);
        $event_count = $db->loadResult();
        return $event_count;
    }

    public static function tagsList($id){
    $db = JFactory::getDBO();

     $query =  "SELECT  `tag_id`,`tag_name` ".
               " FROM `#__em_tags` WHERE event_id = '$id'";
     $db->setQuery($query);
     $tag_list = $db->loadObjectList();
     return $tag_list;
    }
    
    public static function ticketPayment($id,$subscribeID){
     $db = JFactory::getDBO();

     $query =  "SELECT  a.`id`,b.name,b.email  FROM `#__em_tickets` a INNER JOIN `#__em_subscriptions` b ON a.event_id = b.event_id WHERE a.id = '$id' and b.id = '$subscribeID'";
     $db->setQuery($query);
     $ticket_list = $db->loadObject();
     return $ticket_list;
    }
}