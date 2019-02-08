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

class AppthaeventzModelpaymentintegration extends JModelList {

//function to get list of payment methods
    function paymentmethods() {
        $db = JFactory::getDBO();
        $query = "SELECT id,payment_method,status FROM #__em_paymentmethod";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

//function to publish or unpublish an item
    function changeStatus($detail) {

        $cid = $detail['cid'];

        $n = count($detail['cid']);
        $db = JFactory::getDBO();
        $task = JRequest::getCmd('task');
        if ($task == "unpublish") {
            $publish = 0;
        } else {
            $publish = 1;
        }
        for ($i = 0; $i < $n; $i++) {
            //update the status to the table.
            $query = "UPDATE #__em_paymentmethod set status=" . $publish . "
                      WHERE id = " . $cid[$i];
            $db->setQuery($query);
            $result = $db->loadResult();
        }
        return $result;
    }

//function to save details of wire transfer
    function savewiretransfer($detail) {
        
        $published = $detail['published']['0'];
        
        //get text editor content
        $content = $_REQUEST['content'];
        $content = addslashes($content);
      
        $db = JFactory::getDBO();
//       
        $query = " UPDATE `#__em_wiretransfer_settings` SET `details` = '$content'
                   WHERE `id` = 1";
        $db->setQuery($query);
        $db->query();
        $query = " UPDATE `#__em_paymentmethod` SET `status` = '$published'
                   WHERE `id` = 1";
        $db->setQuery($query);
        $db->query();
    }

//function to save paypal details
    function savepaypal($detail) {
        $published = $detail['published']['0'];
        $paypalmode = $detail['paypalmode']['0'];
        $email = trim($detail['email']);

        $db = JFactory::getDBO();
        $query = " UPDATE `#__em_paypal_settings` SET `email` = '$email' , `mode` = '$paypalmode'
                   WHERE `id` = 1";
        $db->setQuery($query);
        $db->query();
        $query = " UPDATE `#__em_paymentmethod` SET `status` = '$published'
                   WHERE `id` = 2";
        $db->setQuery($query);
        $db->query();
    }

//function to save authorized .net details
    function saveauthorized($detail) {
        $published = $detail['published']['0'];
        $authorizedmode = $detail['authorizedmode']['0'];
        $email = trim($detail['email']);
        $login_id = trim($detail['login_id']);
        $transaction_key = trim($detail['transaction_key']);

        $db = JFactory::getDBO();
        $query = " UPDATE `#__em_authorized_settings` SET `email` = '$email' ,`api` = '$login_id' ,`transaction_key` = '$transaction_key' , `mode` = '$authorizedmode'
                   WHERE `id` = 1";
        $db->setQuery($query);
        $db->query();
         $query = " UPDATE `#__em_paymentmethod` SET `status` = '$published'
                   WHERE `id` = 3";
        $db->setQuery($query);
        $db->query();
    }

//function to get wiretransfer details
    function getwiretransfer() {

        $db = JFactory::getDBO();
        $query = "SELECT a.id,a.details FROM #__em_wiretransfer_settings AS a
                  WHERE id = 1";
        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
    }

//function to get paypal details
    function getpaypal() {

        $db = JFactory::getDBO();
        $query = "SELECT id,email,mode FROM #__em_paypal_settings
                  WHERE id = 1";
        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
    }

//function to get authorized.net details
    function getauthorized() {

        $db = JFactory::getDBO();
        $query = "SELECT id,email,api,transaction_key,mode FROM #__em_authorized_settings
                  WHERE id = 1";
        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
    }
    function getstatus() {

        $db = JFactory::getDBO();
        $query = "SELECT status FROM #__em_paymentmethod";
        $db->setQuery($query);
        $result = $db->loadResultArray();

        return $result;
    }
   

}