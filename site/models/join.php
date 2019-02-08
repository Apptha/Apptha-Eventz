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

require_once( JPATH_BASE . DS . 'components'. DS . 'com_appthaeventz'. DS . 'helpers' . DS . 'paypal.class.php' );
require_once(  JPATH_BASE . DS . 'components'. DS . 'com_appthaeventz'. DS . 'helpers' . DS . 'config.php' );

class appthaeventzModeljoin extends JModel {
    public function getEventTicket()
    {
        $db = JFactory::getDBO();
        $eventID = JRequest::getVar('event_id');

        $query="SELECT `id` ,`ticket_name`,`price` FROM  `#__em_tickets` WHERE `event_id` = '$eventID'";// '$eventID'";
        $db->setQuery($query);
        $ticket_list = $db->loadObjectList();
        return $ticket_list;
    }

    public function getPaymentMethods()
    {
        $db = JFactory::getDBO();
        $eventID = JRequest::getVar('event_id');

        $query="SELECT `id` ,`payment_method`,`currency_sign` FROM  `#__em_paymentmethod` WHERE status = 1";
        $db->setQuery($query);
        $payment_list = $db->loadObjectList();
        return $payment_list;
    }

    public function getTicketSeats($id)
    {
        $db = JFactory::getDBO();
        $eventID = JRequest::getVar('event_id');

        $query="SELECT `seats` FROM  `#__em_tickets` WHERE id = $id";
        $db->setQuery($query);
        $seats = $db->loadResult();
        return $seats;
    }

    public static function processPayment(){

        $db = JFactory::getDBO();

        $name = JRequest::getVar('cust_name');
        $email = JRequest::getVar('x_email');
        $no_tickets = JRequest::getVar('cust_ticketno');
        $ticket_id = JRequest::getVar('cust_ticket');


        //get the payment details
        $query="SELECT `id` ,`payment_method`,`currency`,`currency_sign` FROM  `#__em_paymentmethod` WHERE status = 1";
        $db->setQuery($query);
        $payment_list = $db->loadObjectList();

        //Check the Paypal mode
        $query = " SELECT `email`,`mode` from `#__em_paypal_settings` ";
        $db->setQuery($query);
        $settings = $db->loadObject();
        $mode = $settings->mode;

        
        $query = " SELECT `ticket_name`,`price`,`event_id` FROM `#__em_tickets` WHERE id = ".$ticket_id;
        $db->setQuery($query);
        $eventTitle = $db->loadObject();


        //Inserting the subscriber
        $query = " INSERT INTO `#__em_subscriptions` (name,email,no_tickets,tickets_id,event_id,payment_id) VALUES ('$name','$email','$no_tickets','$ticket_id','$eventTitle->event_id','2') ";
        $db->setQuery($query);
        $db->query();
        $insID = $db->insertid();


        $payAmount = $no_tickets*$eventTitle->price;
        $taskTitle = $eventTitle->ticket_name;
        $paypalEmail = $settings->email;

        $baseURL = JURI::base();
        $rootPath = JURI::root(true) . '/';


        $return=JURI::base()."index.php?option=com_appthaeventz&view=join&task=paypalCancel";
        //$returnNotify = JURI::base()."index.php?option=com_appthaeventz&task=paypalSuccess";   //&task_id=".$taskId
        $returnNotify = JURI::base()."components/com_appthaeventz/notify.php";
        $returnURL=JURI::base()."index.php?option=com_appthaeventz&view=join&task=paypalReturn";

        $paypal = new paypal_class_events;
        // initiate an instance of the class
        if ($mode == '1') {
            $paypal->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
            // testing paypal url
        } else {
            $paypal->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
            // paypal url
        }

        $paypal->add_field('currency_code', $payment_list[0]->currency);
        $paypal->add_field('amount', $payAmount);
        $paypal->add_field('business', $paypalEmail);
        $paypal->add_field('custom', $insID);
        $paypal->add_field('paymentaction', 'authorization');
        $paypal->add_field('return', $returnURL);
        $paypal->add_field('cancel_return', $return);
        $paypal->add_field('notify_url', $returnNotify);
        $paypal->add_field('item_name', $taskTitle);

        // submit the fields to paypal
        $paypal->submit_paypal_post($paypal->paypal_url);


    }

    public static function processWire(){
        $db = JFactory::getDBO();

        $name = JRequest::getVar('cust_name');
        $email = JRequest::getVar('x_email');
        $no_tickets = JRequest::getVar('cust_ticketno');
        $ticket_id = JRequest::getVar('cust_ticket');
        date_default_timezone_set('Asia/Calcutta');
        $trasdate = date('Y-m-d H:i:s');

        //get the payment details
        $query="SELECT `id` ,`payment_method`,`currency`,`currency_sign` FROM  `#__em_paymentmethod` WHERE status = 1";
        $db->setQuery($query);
        $payment_list = $db->loadObjectList();
        
        $query = " SELECT `ticket_name`,`price`,`event_id` FROM `#__em_tickets` WHERE id = ".$ticket_id;
        $db->setQuery($query);
        $eventTitle = $db->loadObject();


        //Inserting the subscriber
        $query = " INSERT INTO `#__em_subscriptions` (name,email,no_tickets,tickets_id,event_id,status,payment_id,subscribed_on) VALUES ('$name','$email','$no_tickets','$ticket_id','$eventTitle->event_id','1','1','$trasdate') ";
        $db->setQuery($query);
        $db->query();
        $insID = $db->insertid();

        $query = "SELECT * FROM `#__em_subscriptions` WHERE id = '$insID' ";
        $db->setQuery( $query );
        $objResult = $db->loadObject();

        //get Eventname
        $query = "SELECT event_name FROM #__em_events WHERE id = '$objResult->event_id' ";    
        $db->setQuery( $query );
        $eventName = $db->loadResult();

        //updating tickets count
        $query = " SELECT seats,booked_count,balance_count,event_id FROM `#__em_tickets` WHERE id = '$ticket_id' ";
        $db->setQuery($query);
        $seatCount = $db->loadObject();

        if($seatCount->booked_count == 0 && $seatCount->balance_count != 1 && $seatCount->balance_count != NULL){
            $query = " SELECT allow_overbooking,overbooking_amount FROM `#__em_control` WHERE event_id = '$seatCount->event_id' ";
            $db->setQuery($query);
            $ticketOverbooked = $db->loadObject();
            if($ticketOverbooked->allow_overbooking == 1){
                $count = $ticketOverbooked->overbooking_amount;
            }

            $query = " UPDATE `#__em_tickets` SET booked_count = '$count',balance_count = 1 WHERE id = '$ticket_id' ";
            $db->setQuery($query);
            $db->query();

            $query = " SELECT seats,booked_count,balance_count,event_id FROM `#__em_tickets` WHERE id = '$ticket_id' ";
            $db->setQuery($query);
            $seatCount = $db->loadObject();

        }


        if(!empty ($seatCount))
        {


            if( $seatCount->booked_count != 0 || $ticket->booked_count != NULL)
            {


                $bookedCount = $seatCount->booked_count - $no_tickets;
            }
            else{
                $bookedCount = $seatCount->seats - $no_tickets;
            }
        }

        if($bookedCount == 0 && $seatCount->balance_count != 1){
            $query = " UPDATE `#__em_tickets` SET balance_count = '2' WHERE id = '$ticket_id' ";
            $db->setQuery($query);
            $db->query();
        }
        $query = " UPDATE `#__em_tickets` SET booked_count = '$bookedCount' WHERE id = '$ticket_id' ";
        $db->setQuery($query);
        $db->query();
    

        //User Email
        $app= JFactory::getApplication();
        $templateparams	= $app->getTemplate(true)->params; // get the tempalte parameters
        $logo= $templateparams->get('logo');//get the logo
        $logo_image=JURI::base().'/'.htmlspecialchars($logo);
        

        $query = "SELECT ticket_name,price FROM #__em_tickets WHERE id = '$objResult->tickets_id' ";    //Get ticket name and price
        $db->setQuery( $query );
        $ticketUser = $db->loadObject();
        $query = "SELECT currency_sign FROM #__em_paymentmethod WHERE id = '$objResult->payment_id' ";  //Get currency
        $db->setQuery( $query );
        $currency = $db->loadResult();
        $ticketPrice = $objResult->no_tickets*$ticketUser->price;
        
        if($logo != '')
             $logoDonate =$logo_image;
         else
             $logoDonate=JURI::base().'images/joomla_green.gif';

        $query = "SELECT * FROM #__em_wiretransfer_settings WHERE 1 ";    //Get ticket name and price
        $db->setQuery( $query );
        $ownerBank = $db->loadObject();
         
         $mailer =JFactory::getMailer();
         $config = JFactory::getConfig();

         $message = <<<EOD
            <table cellpadding="0" cellspacing="0" border="0" style="margin: 0; border: 5px solid #ccc; padding: 10px; ">
            <tr>
                <td align="left" valign="top">
                    <span style="display: block; border-bottom: 1px solid #ccc; padding-bottom: 5px;"><img src="$logoDonate" border="0" alt="" title=""/></span>
                    <span style="margin: 0; padding: 15px 0 5px; font-family: arial; color: #000; font-weight: normal; font-size: 13px; display:  block;">Dear $name,</span>

                <h1 style="margin: 0; padding: 0 0 20px; font-family: arial; color: #000; font-weight: normal; font-size: 18px">Thank you for subscribing for the Event - $eventName.</h1>

                <h4 style="margin: 0; padding: 0 0 0px; font-family: arial; color: #000; font-weight: normal; font-size: 14px">Your Subscription details:</h4>
            <table cellpadding="0" cellspacing="0" border="" style="border: 1px solid #ccc; background: #EEEEEE; padding: 10px; margin: 10px 0;">

                <tr>
                    <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: normal; font-size: 12px">Event:</td>
                     <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: bold; font-size: 12px; padding-left: 10px;">$eventName</td>
                </tr>
                <tr>
                    <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: normal; font-size: 12px">Ticket Name:</td>
                     <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: bold; font-size: 12px; padding-left: 10px;">$ticketUser->ticket_name</td>
                </tr>
                <tr>
                    <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: normal; font-size: 12px">Amount:</td>
                     <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: bold; font-size: 12px; padding-left: 10px;">$ticketPrice $currency</td>
                </tr>
                
        </table>
        <span style="margin: 0; padding: 15px 0 5px; font-family: arial; color: #000; font-weight: normal; font-size: 13px; display:  block;">Kindly make a payment for your subscription to the following details.</span>
        <table cellpadding="0" cellspacing="0" border="" style="border: 1px solid #ccc; background: #EEEEEE; padding: 10px; margin: 10px 0;">

                <tr>
                    <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: normal; font-size: 12px" colspan="2">$ownerBank->details</td>
                     
                </tr>
                                
        </table>
        <span style="margin: 0; padding: 15px 0 5px; font-family: arial; color: #000; font-weight: normal; font-size: 13px; display:  block;">Please ignore the mail,if you have already made the payment.</span><br/>

        <span style="font-family: arial; color: #000; font-weight: normal; font-size: 12px;color: #000;">
        Regards,<br>
        Admin.
    </span>
            </td>
        </tr>
    </table>
EOD;
         
           if($contentList->fromemail != '' && $contentList->fromname != '' )
            $mailer->setSender(array($contentList->fromemail,$contentList->fromname));
           else{
            $sender = array(
            $config->getValue( 'config.mailfrom' ),
            $config->getValue( 'config.fromname' ) );
            $mailer->setSender($sender);
           }
          $sitename = $config->getValue( 'config.sitename' );
          $subject = "$sitename - $eventName Subscription status mail";
          $mailer->addRecipient($email);
          $mailer->setSubject($subject);
          $mailer->setBody($message);
          $mailer->isHTML();
          $send = $mailer->Send();
          if ( $send != true ) {
                echo 'Error sending email: ' . $send->message;
          }


          //Sending Subscription Notification mail
        $query = "SELECT notify_owner FROM #__em_control WHERE event_id = '$objResult->event_id' ";
        $db->setQuery( $query );
        $notifyOwner = $db->loadResult();
        if($notifyOwner == 1){
            //Get the Owner details
            $query = "SELECT contact_name,email FROM #__em_contacts WHERE event_id = '$objResult->event_id' ";
            $db->setQuery( $query );
            $ownerDetails = $db->loadObject();

            $query = "SELECT subscription FROM #__em_emailsettings ";
            $db->setQuery( $query );
            $contentSubscribe = $db->loadObject();
            $content = json_decode($contentSubscribe->subscription);

            $emailsubject = $content[0];
            $sub = str_replace('{EventName}',$eventName,$emailsubject);
            $subject = str_replace('{SubscriberName}',$objResult->name,$sub);
            //Content of the mail
            $emailContent = $content[1];
            $messag = str_replace('{OwnerName}',$ownerDetails->contact_name,$emailContent);
            $messag1 = str_replace('{EventName}',$eventName,$messag);
            $message2 = str_replace('{SubscribeDate}',$objResult->subscribed_on,$messag1);
            $message3 = str_replace('{SubscriberName}',$objResult->name,$message2);
            $message4 = str_replace('{SubscriberEmail}',$objResult->email,$message3);
                $paymethods = array('1'=>'Wire Transfer','2'=>'Paypal','3'=>'Authorized.net');
                $payname = $paymethods[$objResult->payment_id];
            $message5 = str_replace('{PaymentGateway}',$payname,$message4);
                $query = "SELECT ticket_name,price FROM #__em_tickets WHERE id = '$objResult->tickets_id' ";
                $db->setQuery( $query );
                $ticketSubscriber = $db->loadObject();
                $ticketInfo = $objResult->no_tickets.' x '.$ticketSubscriber->ticket_name;
            $message6 = str_replace('{TicketsInfo}',$ticketInfo,$message5);
                $query = "SELECT currency_sign FROM #__em_paymentmethod WHERE id = '$objResult->payment_id' ";
                $db->setQuery( $query );
                $currency = $db->loadResult();
                $ticketPrice = $objResult->no_tickets*$ticketSubscriber->price;
            $message = str_replace('{TicketsTotal}',$ticketPrice.' '.$currency,$message6);

            
            if($contentList->fromemail != '' && $contentList->fromname != '' )
                $mailer->setSender(array($contentList->fromemail,$contentList->fromname));
            else{
                $sender = array(
                $config->getValue( 'config.mailfrom' ),
                $config->getValue( 'config.fromname' ) );
                $mailer->setSender($sender);
            }
            $mailerNotify =JFactory::getMailer();
            $mailerNotify->addRecipient($ownerDetails->email);
            $mailerNotify->setSubject($subject);
            $mailerNotify->setBody($message);
            $mailerNotify->isHTML();

            $sendNotify = $mailerNotify->Send();
            if ( $sendNotify != true ) {
                echo 'Error sending email: ' . $sendNotify->message;
            }
         }

    }

    public static function authorizedProcess(){

        $app = JFactory::getApplication();
        $db = JFactory::getDBO();

        $METHOD_TO_USE='AIM';
        if ($METHOD_TO_USE == "AIM") {
                $transaction = new AuthorizeNetAIM;
                $transaction->setSandbox(AUTHORIZENET_SANDBOX);
                $amount=JRequest::getVar('x_amount');
                $transaction->setFields(
                    array(
                    'amount' => $amount,
                    'card_num' => JRequest::getVar('x_card_num'),
                    'exp_date' => JRequest::getVar('x_exp_date'),
                    'email' =>JRequest::getVar('x_email'),
                    'card_code' => JRequest::getVar('x_card_code')
                    )
                );
                $response = $transaction->authorizeAndCapture();

                if ($response->approved) {
                    $taskId=JRequest::getInt('authorized_task_id');
                    $totalAmount=JRequest::getVar('x_amount');
                    $transactionId=$response->transaction_id;
                    $first_name=$response->first_name;
                    $last_name=$response->last_name;
                    $payment_status=$response->approved;
                    $email = JRequest::getVar('x_email');
                    $eventType = JRequest::getVar('event');
                    $creditno = JRequest::getVar('x_card_num');
                    $expiry = JRequest::getVar('x_exp_date');
                    $cvvcode = JRequest::getVar('x_card_code');
                    date_default_timezone_set('Asia/Calcutta');
                    $trasdate = date('Y-m-d H:i:s');
                    // Transaction approved! Do your logic here.

                    $jname = JRequest::getVar('cust_name');
                    $jemail = JRequest::getVar('x_email');
                    $jno_tickets = JRequest::getVar('cust_ticketno');
                    $jticket_id = JRequest::getVar('cust_ticket');

                     $query = " SELECT `ticket_name`,`price`,`event_id` FROM `#__em_tickets` WHERE id = ".$jticket_id;
                     $db->setQuery($query);
                     $eventTitle = $db->loadObject();


                    //Inserting the subscriber
                    $query = " INSERT INTO `#__em_subscriptions` (name,email,no_tickets,tickets_id,event_id,payment_id,status,subscribed_on) VALUES ('$jname','$jemail','$jno_tickets','$jticket_id','$eventTitle->event_id','3','2','$trasdate') ";
                    $db->setQuery($query);
                    $db->query();
                    $insID = $db->insertid();

                    $query = " INSERT INTO `#__em_orderlist` (payment_amount,transaction_id,email_address,ticket_id,payment_method,payment_status,transaction_date)
                     VALUES ('$totalAmount','$transactionId','$email','$jticket_id','3','$payment_status','$trasdate') ";
                    $db->setQuery($query);
                    $db->query();

                    
                     //updating tickets count
                    $query = " SELECT seats,booked_count,balance_count,event_id FROM `#__em_tickets` WHERE id = '$jticket_id' ";
                    $db->setQuery($query);
                    $seatCount = $db->loadObject();

                    if($seatCount->booked_count == 0 && $seatCount->balance_count != 1 && $seatCount->balance_count != NULL){
                        $query = " SELECT allow_overbooking,overbooking_amount FROM `#__em_control` WHERE event_id = '$seatCount->event_id' ";
                        $db->setQuery($query);
                        $ticketOverbooked = $db->loadObject();
                        if($ticketOverbooked->allow_overbooking == 1){
                            $count = $ticketOverbooked->overbooking_amount;
                        }

                        $query = " UPDATE `#__em_tickets` SET booked_count = '$count',balance_count = 1 WHERE id = '$jticket_id' ";
                        $db->setQuery($query);
                        $db->query();

                        $query = " SELECT seats,booked_count,balance_count,event_id FROM `#__em_tickets` WHERE id = '$jticket_id' ";
                        $db->setQuery($query);
                        $seatCount = $db->loadObject();

                    }


                    if(!empty ($seatCount))
                    {


                        if( $seatCount->booked_count != 0 || $ticket->booked_count != NULL)
                        {


                            $bookedCount = $seatCount->booked_count - $jno_tickets;
                        }
                        else{
                            $bookedCount = $seatCount->seats - $jno_tickets;
                        }
                    }

                    if($bookedCount == 0 && $seatCount->balance_count != 1){
                        $query = " UPDATE `#__em_tickets` SET balance_count = '2' WHERE id = '$jticket_id' ";
                        $db->setQuery($query);
                        $db->query();
                    }
                    $query = " UPDATE `#__em_tickets` SET booked_count = '$bookedCount' WHERE id = '$jticket_id' ";
                    $db->setQuery($query);
                    $db->query();
                    $obj = new appthaeventzModeljoin();
                    
                    $obj->_updateProcessing($insID,$email,$first_name,$last_name);
                   
                    $msg = JText::_('Authorized.net Payment Successful.');
                    $app->redirect(JRoute::_('index.php?option=com_appthaeventz&view=eventlist', false), $msg);
                    
                } else {

                    $msg = JText::_('Please contact the Admin').'<br/>';
                    $msg .= JText::_('Response reason code') .": $response->response_reason_code <br/>";
                    $msg .= JText::_('Response code') .": $response->response_code <br/>";
                    $msg .= JText::_('Response reason text') .": $response->response_reason_text";
                    $app->redirect(JRoute::_('index.php?option=com_appthaeventz&view=eventlist', false), $msg);
                }
            }
    }

    public function _updateProcessing($subscription_id,$email,$firstname,$lastname){

        $db = JFactory::getDBO();
        date_default_timezone_set('Asia/Calcutta');
        $trasdate = date('Y-m-d H:i:s');

        $query = "SELECT a.name,a.email,a.event_id,a.subscribed_on,a.payment_id,a.tickets_id,a.no_tickets,b.event_name FROM #__em_subscriptions a join #__em_events b on a.event_id=b.id WHERE a.id = '$subscription_id'";
        $db->setQuery($query);
        $objResult = $db->loadObject();

        $eventName = $objResult->event_name;
        $name = $objResult->name;
        $query = "SELECT approved,fromemail,fromname,cc,bcc FROM #__em_emailsettings ";
        $db->setQuery( $query );
        $contentList = $db->loadObject();
        $content = json_decode($contentList->approved);
        //Subject of the mail
        $subject = $content[0];
        //Content of the mail
        $emailContent = $content[1];
        $messag = str_replace('{user}',$name,$emailContent);
        $message = str_replace('{EventName}',$eventName,$messag);

        //mail functionality
        $fromname = $firstname." ".$lastname;
        $toEmail = $objResult->email;
        //$toEmail = 'shinucherian@contus.in';
        //Get the attachment
        $query = "SELECT ticketlayout_id,attach_email,ticketlayout FROM #__em_ticketlayout WHERE event_id = '$objResult->event_id' ";
        $db->setQuery( $query );
        $attach = $db->loadObject();
        $attachment = '';
        if($attach->attach_email == 1)
            $attachment = $attach->ticketlayout;

        /*if($attachment != ''){
            $pdf=new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial','B',16);
            $pdf->Cell(100,10,$attachment);
            $dir = JURI::base().'components/com_appthaeventz/images/ticket_'.$attach->ticketlayout_id.'.pdf';
            $pdf->Output($dir,"F");
        }*/

        $mailer =JFactory::getMailer();
        $config = JFactory::getConfig();

        if($contentList->fromemail != '' && $contentList->fromname != '' )
            $mailer->setSender(array($contentList->fromemail,$contentList->fromname));
        else{
            $sender = array(
            $config->getValue( 'config.mailfrom' ),
            $config->getValue( 'config.fromname' ) );
            $mailer->setSender($sender);
        }
         //$mailer->addBCC($email);
        $mailer->addRecipient($toEmail);
        $mailer->setSubject($subject);
        $mailer->setBody($message);
        $mailer->isHTML();
        /*$filename = 'ticket_'.$attach->ticketlayout_id.'.pdf';
        if($attachment != ''){
            $mailer->addAttachment(JPATH_COMPONENT.DS.'images'.DS.$filename);
        }*/
        $send = $mailer->Send();
        if ( $send != true ) {
            echo 'Error sending email: ' . $send->message;
        }


    //Sending Subscription Notification mail
        $query = "SELECT notify_owner FROM #__em_control WHERE event_id = '$objResult->event_id' ";
        $db->setQuery( $query );
        $notifyOwner = $db->loadResult();
        if($notifyOwner == 1){
            //Get the Owner details
            $query = "SELECT contact_name,email FROM #__em_contacts WHERE event_id = '$objResult->event_id' ";
            $db->setQuery( $query );
            $ownerDetails = $db->loadObject();

            $query = "SELECT subscription FROM #__em_emailsettings ";
            $db->setQuery( $query );
            $contentSubscribe = $db->loadObject();
            $content = json_decode($contentSubscribe->subscription);

            $emailsubject = $content[0];
            $sub = str_replace('{EventName}',$eventName,$emailsubject);
            $subject = str_replace('{SubscriberName}',$objResult->name,$sub);
            //Content of the mail
            $emailContent = $content[1];
            $messag = str_replace('{OwnerName}',$ownerDetails->contact_name,$emailContent);
            $messag1 = str_replace('{EventName}',$eventName,$messag);
            $message2 = str_replace('{SubscribeDate}',$objResult->subscribed_on,$messag1);
            $message3 = str_replace('{SubscriberName}',$objResult->name,$message2);
            $message4 = str_replace('{SubscriberEmail}',$objResult->email,$message3);
                $paymethods = array('1'=>'Wire Transfer','2'=>'Paypal','3'=>'Authorized.net');
                $payname = $paymethods[$objResult->payment_id];
            $message5 = str_replace('{PaymentGateway}',$payname,$message4);
                $query = "SELECT ticket_name,price FROM #__em_tickets WHERE id = '$objResult->tickets_id' ";
                $db->setQuery( $query );
                $ticketSubscriber = $db->loadObject();
                $ticketInfo = $objResult->no_tickets.' x '.$ticketSubscriber->ticket_name;
            $message6 = str_replace('{TicketsInfo}',$ticketInfo,$message5);
                $query = "SELECT currency_sign FROM #__em_paymentmethod WHERE id = '$objResult->payment_id' ";
                $db->setQuery( $query );
                $currency = $db->loadResult();
                $ticketPrice = $objResult->no_tickets*$ticketSubscriber->price;
            $message = str_replace('{TicketsTotal}',$ticketPrice.' '.$currency,$message6);

            $mailerNotify =JFactory::getMailer();
            //$config = JFactory::getConfig();

            if($contentList->fromemail != '' && $contentList->fromname != '' )
                $mailerNotify->setSender(array($contentList->fromemail,$contentList->fromname));
            else{
                $sender = array(
                $config->getValue( 'config.mailfrom' ),
                $config->getValue( 'config.fromname' ) );
                $mailerNotify->setSender($sender);
            }
             //$mailer->addBCC($email);
            $mailerNotify->addRecipient($ownerDetails->email);
            $mailerNotify->setSubject($subject);
            $mailerNotify->setBody($message);
            $mailerNotify->isHTML();

            $sendNotify = $mailerNotify->Send();
            if ( $sendNotify != true ) {
                echo 'Error sending email: ' . $sendNotify->message;
            }
         }

   //Buyer Email
        $app= JFactory::getApplication();
        $templateparams	= $app->getTemplate(true)->params; // get the tempalte parameters
        $logo= $templateparams->get('logo');//get the logo
        $logo_image=JURI::base().'/'.htmlspecialchars($logo);
        $subject = 'Notification mail';

        $query = "SELECT ticket_name,price FROM #__em_tickets WHERE id = '$objResult->tickets_id' ";    //Get ticket name and price
        $db->setQuery( $query );
        $ticketUser = $db->loadObject();
        $query = "SELECT currency_sign FROM #__em_paymentmethod WHERE id = '$objResult->payment_id' ";  //Get currency
        $db->setQuery( $query );
        $currency = $db->loadResult();
        $ticketPrice = $objResult->no_tickets*$ticketUser->price;
        if($logo != '')
             $logoDonate =$logo_image;
         else
             $logoDonate=JURI::base().'images/joomla_green.gif';

         $message = <<<EOD
            <table cellpadding="0" cellspacing="0" border="0" style="margin: 0; border: 5px solid #ccc; padding: 10px; ">
            <tr>
                <td align="left" valign="top">
                    <span style="display: block; border-bottom: 1px solid #ccc; padding-bottom: 5px;"><img src="$logoDonate" border="0" alt="" title=""/></span>
                    <span style="margin: 0; padding: 15px 0 5px; font-family: arial; color: #000; font-weight: normal; font-size: 13px; display:  block;">Dear $name,</span>

                <h1 style="margin: 0; padding: 0 0 20px; font-family: arial; color: #000; font-weight: normal; font-size: 18px">Thank you for subscribing for the Event - $eventName.</h1>

                <h4 style="margin: 0; padding: 0 0 0px; font-family: arial; color: #000; font-weight: normal; font-size: 14px">Your Subscription details:</h4>
            <table cellpadding="0" cellspacing="0" border="" style="border: 1px solid #ccc; background: #EEEEEE; padding: 10px; margin: 10px 0;">

                <tr>
                    <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: normal; font-size: 12px">Event:</td>
                     <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: bold; font-size: 12px; padding-left: 10px;">$eventName</td>
                </tr>
                <tr>
                    <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: normal; font-size: 12px">Ticket Name:</td>
                     <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: bold; font-size: 12px; padding-left: 10px;">$ticketUser->ticket_name</td>
                </tr>
                <tr>
                    <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: normal; font-size: 12px">Amount:</td>
                     <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: bold; font-size: 12px; padding-left: 10px;">$ticketPrice &nbsp; $currency</td>
                </tr>
        </table>
        <span style="font-family: arial; color: #000; font-weight: normal; font-size: 12px;color: #000;">
        Regards,<br>
        Admin.
    </span>
            </td>
        </tr>
    </table>
EOD;
           $mailerBuy =JFactory::getMailer();

           if($contentList->fromemail != '' && $contentList->fromname != '' )
            $mailerBuy->setSender(array($contentList->fromemail,$contentList->fromname));
           else{
            $sender = array(
            $config->getValue( 'config.mailfrom' ),
            $config->getValue( 'config.fromname' ) );
            $mailerBuy->setSender($sender);
           }
          $mailerBuy->addRecipient($email);
          $mailerBuy->setSubject($subject);
          $mailerBuy->setBody($message);
          $mailerBuy->isHTML();
          $sendBuyer = $mailerBuy->Send();
          if ( $sendBuyer != true ) {
                echo 'Error sending email: ' . $sendBuyer->message;
          }

 //Merchant Email
          $merchantMailId = '';
          $query = "SELECT * FROM #__em_authorized_settings ";
          $db->setQuery($query);
          $objSettings = $db->loadObject();
          if(count($objSettings) > 0){
                $merchantMailId = $objSettings->email;
          }

          $merchantMessage = <<<EOD
            <table cellpadding="0" cellspacing="0" border="0" style="margin: 0; border: 5px solid #ccc; padding: 10px; ">
            <tr>
                <td align="left" valign="top">
                    <span style="display: block; border-bottom: 1px solid #ccc; padding-bottom: 5px;"><img src="$logoDonate" border="0" alt="" title=""/></span>
                    <span style="margin: 0; padding: 15px 0 5px; font-family: arial; color: #000; font-weight: normal; font-size: 13px; display:  block;">Dear Admin,</span>

                <h1 style="margin: 0; padding: 0 0 20px; font-family: arial; color: #000; font-weight: normal; font-size: 18px">$name has subscribed for the Event - $eventName.</h1>

                <h4 style="margin: 0; padding: 0 0 0px; font-family: arial; color: #000; font-weight: normal; font-size: 14px">Subscription details:</h4>
            <table cellpadding="0" cellspacing="0" border="" style="border: 1px solid #ccc; background: #EEEEEE; padding: 10px; margin: 10px 0;">

                <tr>
                    <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: normal; font-size: 12px">Event:</td>
                     <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: bold; font-size: 12px; padding-left: 10px;">$eventName</td>
                </tr>
                <tr>
                    <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: normal; font-size: 12px">Ticket Name:</td>
                     <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: bold; font-size: 12px; padding-left: 10px;">$ticketUser->ticket_name</td>
                </tr>
                <tr>
                    <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: normal; font-size: 12px">Amount:</td>
                     <td style="margin: 0; padding: 0 0 5px; font-family: arial; color: #000; border: 0; font-weight: bold; font-size: 12px; padding-left: 10px;">$ticketPrice &nbsp; $currency</td>
                </tr>
        </table>

            </td>
        </tr>
    </table>
EOD;
      $mailerMerchant =JFactory::getMailer();

      $merchantSubject = JText::_('Notification mail');
      if($contentList->fromemail != '' && $contentList->fromname != '' )
        $mailerMerchant->setSender(array($contentList->fromemail,$contentList->fromname));
       else{
        $sender = array(
        $config->getValue( 'config.mailfrom' ),
        $config->getValue( 'config.fromname' ) );
        $mailerMerchant->setSender($sender);
       }

      $mailerMerchant->addRecipient($merchantMailId);
      $mailerMerchant->setSubject($merchantSubject);
      $mailerMerchant->setBody($merchantMessage);
      $mailerMerchant->isHTML();
      $sendMerchant = $mailerMerchant->Send();
      if ( $sendMerchant != true ) {
            echo 'Error sending email: ' . $sendMerchant->message;
      }

    }

    public static function join(){
        $db = JFactory::getDBO();

        $name = JRequest::getVar('cust_name');
        $email = JRequest::getVar('x_email');
        $eventId = JRequest::getVar('event_id');
        date_default_timezone_set('Asia/Calcutta');
        $trasdate = date('Y-m-d H:i:s');

        //Inserting the subscriber
        $query = " INSERT INTO `#__em_subscriptions` (name,email,event_id,status,subscribed_on) VALUES ('$name','$email','$eventId','1','$trasdate') ";
        $db->setQuery($query);
        $db->query();

    }
    
}

?>