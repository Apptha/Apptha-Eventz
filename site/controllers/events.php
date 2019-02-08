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

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

class appthaeventzControllerevents extends JController {

    
    function display($cachable = false, $urlparams = false) {

         
                           
        parent::display();
    }
function exportIcalfmt() {
        $db = JFactory::getDBO();
        $eventsId = JRequest::getVar('events_id','0');
        
            
            $query = "SELECT a.id,a.event_name,a.start_date,a.end_date,a.description,b.location_name,b.address FROM #__em_events a ".
                     " INNER JOIN #__em_locations b on b.event_id = a.id ".
                     " WHERE a.id = '$eventsId' ";
            $db->setQuery($query);
            $objIcal = $db->loadObject();
            //Export as .ics
            $site = JURI::root();
$importCal = 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//'.$site.'//NONSGML v1.0//EN
METHOD:PUBLISH
BEGIN:VEVENT
UID:"'.md5(uniqid(mt_rand(), true)).'@'.$site.'"
SUMMARY:'. $objIcal->event_name .'
DESCRIPTION:'. addslashes(strip_tags($objIcal->description)) .'
DTSTAMP:'. date('Ymd\THis\Z') .'
DTSTART:'. date('Ymd\THis\Z',strtotime($objIcal->start_date) ) .'
DTEND:'. date('Ymd\THis\Z',strtotime($objIcal->end_date) ) .'
LOCATION:'. $objIcal->location_name.'('.$objIcal->address.')'.'
END:VEVENT
END:VCALENDAR';
            header('Content-type: text/calendar');
            header('Content-Disposition: attachment; filename="'.$objIcal->event_name.'.ics"');
            header('Pragma: no-cache');
            header('Expires: 0');
            echo $importCal;
            exit;
    }
  
}