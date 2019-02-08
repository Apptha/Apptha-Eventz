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

defined( '_JEXEC' ) or die( 'Restricted access' );

class ticketsHelper {

    public static function getTickets($eventID) {
                $db = JFactory::getDBO();

                $query = " SELECT ticket_name,seats FROM `#__em_tickets` WHERE event_id = '$eventID' ";
                $db->setQuery($query);
                $result=$db->loadObjectList();
                
                return $result;
   }

   public static function getTicketDetails($ticketId) {
                $db = JFactory::getDBO();
               
                $query = " SELECT ticket_name,price FROM `#__em_tickets` WHERE id = '$ticketId' ";
                $db->setQuery($query);
                $ticketDetails=$db->loadObject();
               
                return $ticketDetails;
   }
}