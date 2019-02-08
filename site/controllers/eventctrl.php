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
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class appthaeventzControllereventctrl extends JController
{
    /**
     * Method to display the view
     */
    function display($cachable = false, $urlparams = false)
    {
           //get user id for logincheck
        parent::display();
    }

      
    function getEventSearch()
    {
        $mainframe = JFactory::getApplication();
        $searchvalue=JRequest::getVar('searchvalue');
        $model =  $this->getModel('search');
        $results = $model->getEventSearch($searchvalue);
        $inc=0;
        $username= '';
        foreach ($results as $result) {

            if ((count($results) - 1) == $inc) {
                $username['event_name'][]= $result->event_name;
            } else {
                
                  $username['event_name'][]= $result->event_name;
            }
            $inc++;
        }
        echo json_encode($username);
        
        die;
        
    }function myStarRate()
    {
        $mainframe = JFactory::getApplication();
        $event_details['event_id']=JRequest::getVar('events_id');
        $event_details['star_user_click']=JRequest::getVar('star_user_click');
        
        
        $model =  $this->getModel('events');
        $result = $model->getRateMe($event_details);
echo ceil($result);
        die;
        
    }
    
}
?>