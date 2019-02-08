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

jimport('joomla.application.component.model');

class AppthaeventzModelsettings extends JModel {

   //function to save emaildetail fields
     function emailfields() {

                $task = JRequest::getvar('task');

                if (($task == 'apply')||($task == 'save')) {

                $tab = JRequest::getvar('tab', null, 'POST');

                if ($tab == 'general') {

                $post = JRequest::getvar('POST');

                $date = JRequest::getvar('global_dates', null, 'POST');
                $time = JRequest::getvar('global_times', null, 'POST');
                //store as json data.
                $jsonevents = array($date, $time);
                $jsondata = json_encode($jsonevents);

                $db = JFactory::getDBO();
                //save generalsettings
                $query = " UPDATE `#__em_eventsettings` SET `json_data_general` = ' $jsondata'
                           WHERE `id` = 1";
                $db->setQuery($query);
                $db->query();
            } else if ($tab == 'events') {                //save eventsettings

                $multipletickets = JRequest::getvar('multipletickets', null, 'POST');
                $commentingsystem = JRequest::getvar('commentingsystem', null, 'POST');
                $smallwidth = round(JRequest::getvar('smallwidth', null, 'POST'));
                $smallheight = round(JRequest::getvar('smallheight', null, 'POST'));
                $largewidth = round(JRequest::getvar('largewidth', null, 'POST'));
                $largeheight = round(JRequest::getvar('largeheight', null, 'POST'));
                $fbappid = JRequest::getvar('fbappid', null, 'POST');
                $fbsecretkey = JRequest::getvar('fbsecretkey', null, 'POST');
                $googleapi = JRequest::getvar('googleapi', null, 'POST');
                $googleclient = JRequest::getvar('googleclient', null, 'POST');
                $googleclient = trim($googleclient);
                $googlesecret = JRequest::getvar('googlesecret', null, 'POST');
                $googlesecret = trim($googlesecret);
                $jsonevents = array($multipletickets,$commentingsystem, $smallwidth, $smallheight, $largewidth, $largeheight);

                $jsondata = json_encode($jsonevents);

                $db = JFactory::getDBO();
                $query = " UPDATE `#__em_eventsettings` SET `json_data_events` = ' $jsondata',`fb_appid` = '$fbappid',`fb_secret` = '$fbsecretkey',`google_api` = '$googleapi',`googleclient` = '$googleclient',`googlesecret` = '$googlesecret'
                           WHERE `id` = 1";
                $db->setQuery($query);
                $db->query();
            } else if ($tab == 'emails') {
                //save email settings
                $fromemail = JRequest::getvar('fromemail', null, 'POST');
                $fromname = JRequest::getvar('fromname', null, 'POST');
                $cc = JRequest::getvar('cc', null, 'POST');
                $bcc = JRequest::getvar('bcc', null, 'POST');

                $db = JFactory::getDBO();

                $query = " UPDATE `#__em_emailsettings` SET `fromemail` = '$fromemail' , `fromname` = '$fromname',`cc` = '$cc',`bcc` = '$bcc'
                           WHERE `id` = 1";
                $db->setQuery($query);
                $db->query();
                $result = $db->loadResult();
            } else if ($tab == 'payments') {
                //save payments settings.
                $paymentmethod = JRequest::getvar('paymentmethod', null, 'POST');
//                if ($paymentmethod){
//	         foreach ($paymentmethod as $t){echo 'You selected ',$t,'<br />';}
//                }die;
                $currency = JRequest::getvar('currency', null, 'POST');
                $currencysign = JRequest::getvar('currencysign', null, 'POST');
                if ($paymentmethod ) {
                    //save if the payment method is wire transfer
                    $db = JFactory::getDBO();
                    //enable the current payment method by setting the payment flag 1 and set all other payment method as 0
                    foreach ($paymentmethod as $t){
                   $query = " UPDATE `#__em_paymentmethod` SET `status` = '0'
                               WHERE `id` <> $t ";
                    $db->setQuery($query);
                    $db->query();}
                    foreach ($paymentmethod as $t){
                    $query = " UPDATE `#__em_paymentmethod` SET `status` = '1'
                               WHERE `id` = $t ";
                    $db->setQuery($query);
                    $db->query();}
                    $query = " UPDATE `#__em_paymentmethod` SET `currency` = '$currency' , `currency_sign` = '$currencysign' ";
                    $db->setQuery($query);
                    $db->query();
                }

            }
        }
    }
    //function to save details inside email popup fields
    function email() {

        $task = JRequest::getvar('task');
        $type = JRequest::getvar('type');



        if ($task == 'saveit') {

            $subject = JRequest::getvar('subject', null, 'POST');
            //get text editor content
            $content = $_REQUEST['content'];
            $content = addslashes($content);
            $content = str_replace('"','doublequotes',$content);
            $content = str_replace(array("\n", "\r"),'',$content);

            //format the data as json data.
            $jsonemail = array($subject, $content);
            $jsondata = json_encode($jsonemail);

            $db = JFactory::getDBO();

           $query = " UPDATE `#__em_emailsettings` SET `$type` = ' $jsondata'
                       WHERE `id` = 1";
            $db->setQuery($query);
            $db->query();
            echo '<script type="text/javascript">window.parent.SqueezeBox.close();</script>';
        }
    }
     //function to get general settings
    function getgeneralsettings() {

        $db = JFactory::getDBO();
        $query = "SELECT json_data_general FROM #__em_eventsettings";
        $db->setQuery($query);
        $result = $db->loadResult();
        //decode the returned json data.
        $json_result = json_decode($result);

        return $json_result;
    }
    //function to get event settings
    function geteventsettings() {

        $db = JFactory::getDBO();
        $query = "SELECT json_data_events FROM #__em_eventsettings";
        $db->setQuery($query);
        $result = $db->loadResult();
        //decode the returned json data.
        $json_result = json_decode($result);

        return $json_result;
    }
 //function to get payment settings.
    function getpaymentsettings() {

        $db = JFactory::getDBO();
        $query = "SELECT id FROM #__em_paymentmethod
                  WHERE status = 1";
        $db->setQuery($query);
        $result = $db->loadResultArray();

        return $result;
    }
    function getcurrencydetails() {

        $db = JFactory::getDBO();
        $query = "SELECT currency,currency_sign FROM #__em_paymentmethod";
        $db->setQuery($query);
        $result = $db->loadObject();
        return $result;

    }
   //function to get email field settings.
    function getemailsettings() {

        $db = JFactory::getDBO();
        $query = "SELECT fromemail,fromname,cc,bcc FROM #__em_emailsettings";
        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
    }
//function to get email popup settings.
    function getemailpopupsettings() {

        $db = JFactory::getDBO();
        $query = "SELECT registration,activation,denied,approved,invite,remainder,moderation,subscription FROM #__em_emailsettings ";
        $db->setQuery($query);
        $result = $db->loadAssocList();
        if($result){
        $result = $result['0'];
        return $result;
        }

    }
 function fbdetails() {

        $db = JFactory::getDBO();
        $query = "SELECT fb_appid,fb_secret FROM #__em_eventsettings";
        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
    }
    function googledetails() {

        $db = JFactory::getDBO();
        $query = "SELECT google_api,googleclient,googlesecret FROM #__em_eventsettings";
        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
    }
}