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
ini_set('display_errors',1); 
error_reporting(E_ALL);

$my_path = dirname(__FILE__);

if (file_exists($my_path . "/../../../configuration.php")) {
   $absolute_path = dirname($my_path . "/../../../configuration.php");
   require_once($my_path . "/../../../configuration.php");
} else if (file_exists($my_path . "/../../../configuration.php")) {
   $absolute_path = dirname($my_path . "/../../../configuration.php");
   require_once($my_path . "/../../../configuration.php");
} elseif (file_exists($my_path . "/../../configuration.php")) {
   $absolute_path = dirname($my_path . "/../../configuration.php");
   require_once($my_path . "/../../configuration.php");
} elseif (file_exists($my_path . "/configuration.php")) {
   $absolute_path = dirname($my_path . "/configuration.php");
   require_once( $my_path . "/configuration.php" );
} else {
   die("Joomla Configuration File not found!");
}


$absolute_path = realpath($absolute_path);
// Set up the appropriate CMS framework
define('_JEXEC', 1);
define('JPATH_BASE', $absolute_path);
define('DS', DIRECTORY_SEPARATOR);

// Load the framework
require_once ( JPATH_BASE . DS . 'includes' . DS . 'defines.php' );
require_once ( JPATH_BASE . DS . 'includes' . DS . 'framework.php' );
require_once(JPATH_BASE . DS . 'libraries' .DS . 'joomla' .DS . 'database'.DS .'database.php');
require_once (JPATH_BASE . DS . 'components' .DS . 'com_appthaeventz' .DS . 'helpers'.DS .'paypal.class.php' );
require_once( JPATH_BASE . DS . 'components'. DS . 'com_appthaeventz'. DS . 'helpers' . DS . 'fpdf.php' );
// create the mainframe object
$mainframe = & JFactory::getApplication('site');

// Initialize the framework
$mainframe->initialise();

$app = JFactory::getApplication();
$paypal = new paypal_class_events;

$db = JFactory::getDBO();

$query = " INSERT INTO `#__em_locations` (location_id,event_id,location_name,address,latitude,longitude) VALUES ('8','2','news','','','') ";
$db->setQuery($query);
$db->query();
//Check the Paypal mode
$query = " SELECT `email`,`mode` from `#__em_paypal_settings` ";
$db->setQuery($query);
$settings = $db->loadObject();
$mode = $settings->mode;

if($mode =='1'){
    $paymentStatusCheck = "pending";                
}else{
    $paymentStatusCheck = "pending";
}

if ($paypal->validate_ipn()) {
 $transactionId=$paypal->ipn_data['txn_id'];
 $totalAmount=$paypal->ipn_data['mc_gross'];
 $subscription_id=$paypal->ipn_data['custom'];
 $payment_status=$paypal->ipn_data['payment_status'];
 $email = $paypal->ipn_data['payer_email'];
 $firstname = $paypal->ipn_data['first_name'];
 $lastname = $paypal->ipn_data['last_name'];
 date_default_timezone_set('Asia/Calcutta');
 $trasdate = date('Y-m-d H:i:s');
 $custEmail = JRequest::getVar('x_email');

 //Check the Paypal mode
$query = " SELECT `email`,`mode` from `#__em_paypal_settings` ";
$db->setQuery($query);
$settings = $db->loadObject();
$mode = $settings->mode;


if($mode =='1'){
    $paymentStatusCheck = "pending";                
}else{
    $paymentStatusCheck = "pending";
}


if(strtolower($payment_status) == $paymentStatusCheck){
    $query = "select transaction_id from #__em_orderlist where transaction_id='$transactionId'";
    $db->setQuery($query);
    $transIdYes = $db->loadResult();
    if($transIdYes == ''){
        $query = " SELECT tickets_id,no_tickets FROM `#__em_subscriptions` WHERE id = '$subscription_id' ";
        $db->setQuery($query);
        $ticket = $db->loadObject();

        $query = " INSERT INTO `#__em_orderlist` (payment_amount,transaction_id,email_address,ticket_id,payment_method,payment_status,transaction_date)
         VALUES ('$totalAmount','$transactionId','$email','$ticket->tickets_id','2','$payment_status','$trasdate') ";
        $db->setQuery($query);
        $db->query();

        $query = " UPDATE `#__em_subscriptions` SET status = '2',subscribed_on = '$trasdate' WHERE id = '$subscription_id' ";
        $db->setQuery($query);
        $db->query();



        //updating tickets count
        $query = " SELECT seats,booked_count,balance_count,event_id FROM `#__em_tickets` WHERE id = '$ticket->tickets_id' ";
        $db->setQuery($query);
        $seatCount = $db->loadObject();

        if($seatCount->booked_count == 0 && $seatCount->balance_count != 1 && $seatCount->balance_count != NULL){
            $query = " SELECT allow_overbooking,overbooking_amount FROM `#__em_control` WHERE event_id = '$seatCount->event_id' ";
            $db->setQuery($query);
            $ticketOverbooked = $db->loadObject();
            if($ticketOverbooked->allow_overbooking == 1){
                $count = $ticketOverbooked->overbooking_amount;
            }

            $query = " UPDATE `#__em_tickets` SET booked_count = '$count',balance_count = 1 WHERE id = '$ticket->tickets_id' ";
            $db->setQuery($query);
            $db->query();

            $query = " SELECT seats,booked_count,balance_count,event_id FROM `#__em_tickets` WHERE id = '$ticket->tickets_id' ";
            $db->setQuery($query);
            $seatCount = $db->loadObject();

        }


        if(!empty ($seatCount))
        {


            if( $seatCount->booked_count != 0 || $ticket->booked_count != NULL)
            {


                $bookedCount = $seatCount->booked_count - $ticket->no_tickets;
            }
            else{
                $bookedCount = $seatCount->seats - $ticket->no_tickets;
            }
        }

        if($bookedCount == 0 && $seatCount->balance_count != 1){
            $query = " UPDATE `#__em_tickets` SET balance_count = '2' WHERE id = '$ticket->tickets_id' ";
            $db->setQuery($query);
            $db->query();
        }
        $query = " UPDATE `#__em_tickets` SET booked_count = '$bookedCount' WHERE id = '$ticket->tickets_id' ";
        $db->setQuery($query);
        $db->query();

        updatePaypal($subscription_id,$email,$firstname,$lastname);  //helper function to save the transactions
        $msg = JText::_('Transaction saved');
        $app->redirect(JRoute::_('index.php?option=com_appthaeventz&view=eventlist', false), $msg);
    }

 }else{
    $msg = JText::_('IPN Validation failure');
    //$app->redirect(JRoute::_('index.php?option=com_appthaeventz&view=eventlist', false), $msg);
 }

}else{
    $msg = JText::_('IPN Validation failure');
    //$app->redirect(JRoute::_('index.php?option=com_appthaeventz&view=eventlist', false), $msg);
}
        
        
        
function updatePaypal($subscription_id,$email,$firstname,$lastname){

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

    if($contentList->fromemail != '' && $contentList->fromname != '' )
        $mailerNotify->setSender(array($contentList->fromemail,$contentList->fromname));
    else{
        $sender = array(
        $config->getValue( 'config.mailfrom' ),
        $config->getValue( 'config.fromname' ) );
        $mailerNotify->setSender($sender);
    }
    

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
$subjectbuy = 'Notification mail';

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

 $messagebuy = <<<EOD
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
  $mailerBuy->setSubject($subjectbuy);
  $mailerBuy->setBody($messagebuy);
  $mailerBuy->isHTML();
  $sendBuyer = $mailerBuy->Send();
  if ( $sendBuyer != true ) {
        echo 'Error sending email: ' . $sendBuyer->message;
  }

//Merchant Email
  $merchantMailId = '';
  $query = "SELECT * FROM #__em_paypal_settings ";
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
    

?>