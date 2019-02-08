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
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class AppthaeventzModeldashboard extends JModel {

//function to get total revenues
    public function totalrevenues() {
        $db = JFactory::getDBO();
        //get total payment amount from the order_list table
        $query = "SELECT sum(payment_amount) FROM #__em_orderlist WHERE payment_status = 'Pending' OR payment_status = '1'";
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

//function to get highratedevents
    function highratedevents() {
        $db = JFactory::getDBO();
        //select latest 5 highly rated events by calculating the sum of the review_rating given for a particular event.
        $query = "SELECT a.event_name FROM #__em_events AS a
                  INNER JOIN #__em_reviews AS b
                  ON a.id=b.event_id
                  GROUP BY a.event_name
                  ORDER BY sum(b.review_rating) DESC LIMIT 5";
        $db->setQuery($query);
        $result = $db->loadObjectList();

        return $result;
    }

//function to get lastpayments
    function lastpayments() {
        $db = JFactory::getDBO();
        //select last 5 payments method,status and amount.
        $query = "SELECT c.event_name,a.payment_method,a.payment_status,a.payment_amount FROM #__em_orderlist AS a
                  INNER JOIN #__em_subscriptions AS b
                  ON a.ticket_id=b.tickets_id
                  INNER JOIN #__em_events AS c
                  ON b.event_id=c.id
                  WHERE a.payment_status = 'Pending' OR a.payment_status = '1'
                  ORDER BY a.order_id DESC LIMIT 5";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

//function to get upcomingevents
    function upcomingevents() {
       $currentdate = date('Y-m-d');
       
        $db = JFactory::getDBO();
        //select events from events table if the event date is greater than current date.
        $query = "SELECT a.event_name,b.event_id,a.start_date,count(b.event_id) AS subscriber_count FROM #__em_events AS a
                  LEFT JOIN #__em_subscriptions AS b
                  ON a.id=b.event_id
                  WHERE date(a.start_date) >'$currentdate'
                  GROUP BY a.id LIMIT 5";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

//function to get newsubscribers
    function newsubscribers() {
        $db = JFactory::getDBO();
        //select latest 5 subscribers from subscription table.
        $query = "SELECT b.event_name,a.name,a.subscribed_on FROM #__em_subscriptions AS a
                  INNER JOIN #__em_events AS b
                  ON a.event_id=b.id
                  WHERE a.status = 2
                  ORDER BY a.id DESC LIMIT 5";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        
        return $result;
    }

//function to get currentevents
    function currentevents() {
        $currentdate = date('Y-m-d');
             
        $db = JFactory::getDBO();
         //select eventsdetails from events table if the event date is equal current date.
      
      $query = "SELECT a.event_name,b.event_id,a.start_date,count(b.event_id) AS subscriber_count FROM #__em_events AS a
                  LEFT JOIN #__em_subscriptions AS b
                  ON a.id=b.event_id
                  WHERE date(a.end_date ) >= '$currentdate' AND date(a.start_date ) <= '$currentdate'
                  GROUP BY a.id LIMIT 5";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

}