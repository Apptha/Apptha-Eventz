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

class appthaeventzModelfacebookinvite extends JModel {
    
 
public function geteventdetails(){
   $event_id = JRequest::getVar('events_id');
   $db = JFactory::getDBO();
   $query= "SELECT  `event_name`,`start_date` FROM  `#__em_events` WHERE id=$event_id";
   $db->setQuery($query);
   $result = $db->loadObject();   
   return $result;

  }
public function facebookinvite()
{
   $db = JFactory::getDBO();
   $query="SELECT  `fb_appid` FROM  `#__em_eventsettings`";
   $db->setQuery($query);
   $result = $db->loadResult();

   return $result;
}

}

?>