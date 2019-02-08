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

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * @package		Joomla.Site
 * @subpackage	photogallery
 */
class appthaeventzViewevents extends JView {

    public function display($tpl = null) {
        global $mainframe;
        $user=Jfactory::getUser();
        $model = $this->getModel();
        $events = $model->eventList();
        $this->assignRef('events', $events);
		      
      

   $ip=$_SERVER['REMOTE_ADDR'];

$checkIp = $model->checkIp($ip);
 $this->assignRef('checkIp', $checkIp);
 $displayRating = $model->displayRating();
 $this->assignRef('displayRating', $displayRating);
 $location = $model->getLocation();
 $this->assignRef('location', $location);
     
 $event_Settings = $model->getEventSettings();
 $this->assignRef('event_settings', $event_Settings);
 
 $event_images=$model->getEventImages();
 $this->assignRef('event_images', $event_images);
 
 $event_Details=$model->getEventDetails();
 $this->assignRef('event_details', $event_Details);
 
 $event_Date=$model->getEventDate();
 $this->assignRef('event_Date', $event_Date);
        parent::display($tpl);
        
    }

}
