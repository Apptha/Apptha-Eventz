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

class AppthaeventzControllerajaxfind extends JController
{
    /**
     * Method to display the view
     */
    function display($cachable = false, $urlparams = false)
    {
           //get user id for logincheck
        parent::display();
    }

    function getSeats()      //to get the count of the category which already exists
    {

        $mainframe = JFactory::getApplication();
        $flag = false; 
        $cat_name = JRequest::getVar('name');

        $model = $this->getModel('join');
        $results = $model->getTicketSeats($cat_name);

        $db = JFactory::getDBO();
        $query = " SELECT booked_count,event_id,balance_count FROM `#__em_tickets` WHERE id = '$cat_name' ";
        $db->setQuery($query);
        $ticket = $db->loadObject();

        if($ticket->booked_count != 0 || $ticket->booked_count != NULL)
            $count = $ticket->booked_count;
        else
            $count = $results;

        if($ticket->booked_count == 0 && ($ticket->balance_count != 1 && $ticket->balance_count != NULL) ){
            $query = " SELECT allow_overbooking,overbooking_amount FROM `#__em_control` WHERE event_id = '$ticket->event_id' ";
            $db->setQuery($query);
            $ticket = $db->loadObject();
            if($ticket->allow_overbooking == 1){
                $count = $ticket->overbooking_amount;
            }
        }else if($ticket->booked_count == 0 && $ticket->balance_count == 1){
              $flag = true;
        }

        $select = "<select name='cust_ticketno' id='cust_ticketno' onchange='getAmount();'>";
        if(!$flag){
            for($i=1;$i<=$count;$i++){
                $select .= "<option value='".$i."'>".$i."</option>";
            }
        }
        else{
            $select .= "<option value='0'>No seats available</option>";
        }
        $select .= "</select>";
        echo $select;
        die();
    }

}
?>