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
 

// import Joomla modelitem library
jimport('joomla.application.component.model');
jimport('joomla.user.helper');
 

class AppthaeventzModelEvents extends JModel
{
      public function eventList()
{
	
        $db = JFactory::getDBO();
	 $eventsId = JRequest::getVar('events_id','0');
        
	$image_list =  array();
        if($eventsId!=0)
	{
	 $query = "SELECT a.id, a.event_name, a.start_date, a.end_date, b.location_id, a.description, a.category_id,  a.image_name, a.parent_id, b.location_name, c.name,d.contact_name, d.email,e.*
                  FROM #__em_events AS a
                  LEFT JOIN #__em_locations AS b ON b.location_id = a.id
                  LEFT JOIN #__em_categories AS c ON c.id = a.category_id
                  LEFT JOIN #__em_contacts AS d ON a.id = d.event_id
                  LEFT JOIN #__em_control AS e ON a.id = e.event_id
                  WHERE  a.published =1
                  AND c.published =1  AND  a.id = '$eventsId'";
        }
                $db->setQuery($query);
        $result = $db->loadObject();

    
    return $result;
}
public function getRateMe($event_details)
{
    $db = JFactory::getDBO();
    $event_id=$event_details['event_id'];
    $star_user_click=$event_details['star_user_click'];
  
        $ip=$_SERVER['REMOTE_ADDR'];
      
   
if(!empty($event_details))	
{
   $query = "INSERT INTO  #__em_reviews (`event_id`,`review_rating`,`ip_address`)
              VALUES ($event_id, $star_user_click, '".$ip."')";
     $db->setQuery($query);
     $db->query();
     
}
  $query="SELECT avg(`review_rating`) FROM `#__em_reviews` WHERE `event_id`='$event_id'";
     $db->setQuery($query);
     $ave_rating=$db->loadResult();
     return $ave_rating;
}
public function  checkIp($ip_address)
{
    $eventsId = JRequest::getVar('events_id','0');
     $db = JFactory::getDBO();
     $query="SELECT `review_id`, `event_id`, `review_rating` FROM `#__em_reviews` WHERE `ip_address`='$ip_address' AND `event_id`=$eventsId";
     $db->setQuery($query);
     $rating_avail=$db->loadObject();
   return $rating_avail;
}
public function  displayRating()
{
     $eventsId = JRequest::getVar('events_id','0');
     $db = JFactory::getDBO();
     $query="SELECT avg(`review_rating`) FROM `#__em_reviews` WHERE `event_id`='$eventsId'";
     $db->setQuery($query);
     $ave_rating=$db->loadResult();
     return $ave_rating;
}
public function  getLocation()
{
     $eventsId = JRequest::getVar('events_id','0');
     $db = JFactory::getDBO();
     $query="SELECT  `location_name` ,  `latitude` ,  `longitude` 
             FROM  `#__em_locations` 
             WHERE  `event_id` ='$eventsId'";
     $db->setQuery($query);
     $event_location=$db->loadObject();
     return $event_location;
}
function getEventSettings()
{
     $db = JFactory::getDBO();
     $eventsId = JRequest::getVar('events_id','0');
     $query="SELECT `option_id`, `share`, `showing` 
             FROM `#__em_events_options` 
             WHERE `event_id`='$eventsId'";
     $db->setQuery($query);
     $event_settings=$db->loadObject();
     return $event_settings;
}
function getEventImages()
{
     $db = JFactory::getDBO();
     $eventsId = JRequest::getVar('events_id','0');    
    $query="SELECT  `image_id` ,  `event_id` ,  `image_name` 
            FROM  `#__em_events_images` 
            WHERE  `event_id`='$eventsId'";
     $db->setQuery($query);
     $event_images = $db->loadObjectList();
    
     return $event_images;
}
function getEventDetails()
{
     $db = JFactory::getDBO();
     $eventsId = JRequest::getVar('events_id','0');
     $query="SELECT `review_id`,`ip_address` , `review_rating`,`event_id` 
            FROM `#__em_reviews`
            WHERE  `event_id`='$eventsId'";
     
     $db->setQuery($query);
     $event_details=$db->loadObject();
     return $event_details;
}

function getEventDate(){
         $db = JFactory::getDBO();
    
         $query =  "SELECT `json_data_general` FROM `#__em_eventsettings` WHERE 1 ";
         $db->setQuery($query);
         $dateSet = $db->loadObject();
         
         return $dateSet;
}
}