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
//No direct access
defined('_JEXEC') or die('Restricted access');
error_reporting(0);
// Imports
jimport('joomla.installer.installer');
$installer = new JInstaller();
$upgra = '';

function AddColumnIfNotExists(&$errorMsg, $table, $column, $attributes = "INT( 11 ) NOT NULL DEFAULT '0'", $after = '') {
    $db = JFactory::getDBO();
    $columnExists = false;
    $upgra = 'upgrade';
    $query = 'SHOW COLUMNS FROM ' . $table;

    $db->setQuery($query);
    if (!$result = $db->query()) {
        return false;
    }
    $columnData = $db->loadObjectList();
    foreach ($columnData as $valueColumn) {
        if ($valueColumn->Field == $column) {
            $columnExists = true;
            break;
        }
    }

    if (!$columnExists) {
        if ($after != '') {
            $query = 'ALTER TABLE ' . $db->nameQuote($table) . ' ADD ' . $db->nameQuote($column) . ' ' . $attributes . ' AFTER ' . $db->nameQuote($after) . ';';
        } else {
            $query = 'ALTER TABLE ' . $db->nameQuote($table) . ' ADD ' . $db->nameQuote($column) . ' ' . $attributes . ';';
        }
        $db->setQuery($query);
        if (!$result = $db->query()) {
            return false;
        }
        $errorMsg = 'notexistcreated';
    }

    return true;
}


function check_column($table, $newcolumn, $newcolumnafter, $newcolumntype = "int(11) NOT NULL default '0'") {
    $upgra = 'upgrade';
    $db = JFactory::getDBO();
    $msg = '';
    $foundcolumn = false;

    $query = " SHOW COLUMNS FROM `#__" . $table . "`; "
    ;

    $db->setQuery($query);

    if (!$db->query()) {
        return false;
    }

    $columns = $db->loadObjectList();

    foreach ($columns as $column) {
        if ($column->Field == $newcolumn) {
            $foundcolumn = true;
            break;
        }
    }

    if (!$foundcolumn) {
        $query = " ALTER TABLE `#__" . $table . "`
                                ADD `" . $newcolumn . "` " . $newcolumntype
        ;

        if (strlen(trim($newcolumnafter)) > 0) {
            $query .= " AFTER `" . $newcolumnafter . "`";
        }

        $query .= ";";



        $db->setQuery($query);

        if (!$db->query()) {
            return false;
        }
    }

    return true;
}


// Install success. Joomla's module installer
// creates an additional module instance during
// upgrade. This seems to confuse users, so
// let's remove that now.
$db = &JFactory::getDBO();
$result = '';
 if (version_compare(JVERSION, '1.6.0', 'ge')) {
    $query = ' SELECT * FROM ' . $db->nameQuote('#__extensions') . 'where type="component" and element="com_appthaeventz" LIMIT 1;';
    $db->setQuery($query);
    $result = $db->loadResult();
}


if (empty($result)) {


$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_authorized_settings` (
  `id` int(11) NOT NULL,
  `api` varchar(255) NOT NULL,
  `transaction_key` varchar(255) NOT NULL,
  `mode` tinyint(4) NOT NULL,
  `email` varchar(255) NOT NULL
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `color` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `parent_id` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `active_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 - Inactive , 1 - Active',
  `published` tinyint(1) NOT NULL COMMENT '0 - Unpublished , 1 - Published',
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_contacts` (
  `contact_id` int(100) NOT NULL AUTO_INCREMENT,
  `event_id` int(100) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `web` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`contact_id`)
);");
$db->query();


$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_control` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `event_id` int(100) NOT NULL,
  `from_date` datetime NOT NULL,
  `to_date` datetime NOT NULL,
  `allow_overbooking` tinyint(4) NOT NULL,
  `overbooking_amount` int(50) NOT NULL,
  `notify_owner` tinyint(4) NOT NULL,
  `show_guest` tinyint(4) NOT NULL,
  `auto_approve` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_emailsettings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fromemail` text NOT NULL,
  `fromname` text NOT NULL,
  `cc` text NOT NULL,
  `bcc` text NOT NULL,
  `registration` text NOT NULL,
  `activation` text NOT NULL,
  `denied` text NOT NULL,
  `approved` text NOT NULL,
  `invite` text NOT NULL,
  `remainder` text NOT NULL,
  `moderation` text NOT NULL,
  `subscription` text NOT NULL,
  `pop_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_events` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(255) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `description` text NOT NULL,
  `category_id` varchar(100) NOT NULL,
  `tags` varchar(250) NOT NULL,
  `image_name` varchar(200) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_date` datetime NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0 - Inactive , 1 - Active',
  `parent_id` int(100) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_eventsettings` (
  `id` int(11) NOT NULL,
  `json_data_events` text NOT NULL,
  `json_data_general` text NOT NULL,
  `google_username` varchar(255) NOT NULL,
  `google_password` varchar(255) NOT NULL,
  `google_category` int(11) NOT NULL,
  `google_location` int(11) NOT NULL,
  `google_api` varchar(255) NOT NULL,
  `googleclient` varchar(255) NOT NULL,
  `googlesecret` varchar(255) NOT NULL,
  `fb_appid` varchar(255) NOT NULL,
  `fb_secret` varchar(255) NOT NULL,
  `fb_token` varchar(255) NOT NULL,
  `fb_category` int(11) NOT NULL,
  `fb_location` int(11) NOT NULL
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_events_images` (
  `image_id` int(100) NOT NULL AUTO_INCREMENT,
  `event_id` int(100) NOT NULL,
  `image_name` varchar(200) NOT NULL,
  PRIMARY KEY (`image_id`)
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_events_options` (
  `option_id` int(100) NOT NULL AUTO_INCREMENT,
  `event_id` int(100) NOT NULL,
  `share` text NOT NULL,
  `showing` text NOT NULL,
  PRIMARY KEY (`option_id`)
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_events_recurring` (
  `recurring_id` int(100) NOT NULL AUTO_INCREMENT,
  `event_id` int(100) NOT NULL,
  `repeat_interval` int(50) NOT NULL,
  `repeat_type` varchar(100) NOT NULL,
  `repeat_times` int(50) NOT NULL,
  `end_repeat` date NOT NULL,
  PRIMARY KEY (`recurring_id`)
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_importevents` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `event_from` varchar(255) NOT NULL
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_locations` (
  `location_id` int(100) NOT NULL AUTO_INCREMENT,
  `event_id` int(100) NOT NULL,
  `location_name` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `latitude` varchar(100) NOT NULL,
  `longitude` varchar(100) NOT NULL,
  PRIMARY KEY (`location_id`)
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_metas` (
  `meta_id` int(100) NOT NULL AUTO_INCREMENT,
  `event_id` int(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `keyword` varchar(200) NOT NULL,
  `meta_description` text NOT NULL,
  PRIMARY KEY (`meta_id`)
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_orderlist` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `payment_amount` int(11) NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `transaction_date` datetime NOT NULL,
  PRIMARY KEY (`order_id`)
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_others` (
  `other_id` int(100) NOT NULL AUTO_INCREMENT,
  `event_id` int(100) NOT NULL,
  `speakers` text NOT NULL,
  `sponsors` text NOT NULL,
  `who_attend` text NOT NULL,
  `benefit` text NOT NULL,
  PRIMARY KEY (`other_id`)
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_paymentmethod` (
  `id` tinyint(4) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `currency_sign` varchar(255) NOT NULL
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_paypal_settings` (
`id` int(11) NOT NULL,
`email` text NOT NULL,
`mode` tinyint(4) NOT NULL
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_reviews` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `review_rating` int(11) NOT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`review_id`)
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `send_registration_mail` tinyint(1) NOT NULL,
  `no_tickets` int(11) NOT NULL,
  `tickets_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `subscribed_on` datetime NOT NULL,
  `denied_on` datetime NOT NULL,
  `active_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_tags` (
  `tag_id` int(100) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(100) NOT NULL,
  `count` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  PRIMARY KEY (`tag_id`)
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_ticketlayout` (
  `ticketlayout_id` int(100) NOT NULL AUTO_INCREMENT,
  `event_id` int(100) NOT NULL,
  `attach_email` tinyint(4) NOT NULL,
  `ticketlayout` text NOT NULL,
  PRIMARY KEY (`ticketlayout_id`)
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `ticket_name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `seats` int(10) NOT NULL,
  `description` text NOT NULL,
  `booked_count` int(11) DEFAULT NULL,
  `balance_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
);");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__em_wiretransfer_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `details` text NOT NULL,
  PRIMARY KEY (`id`)
);");
$db->query();



//Default values to insert in the database

$db->setQuery("INSERT INTO `#__em_paymentmethod` (`id`, `payment_method`, `status`, `currency`, `currency_sign`) VALUES
(1, 'Wire Transfer', 1, 'USD', '$'),
(2, 'Paypal', 0, 'USD', '$'),
(3, 'Authorized .net', 0, 'USD', '$');");
$db->query();

$dateformat = '["","d F Y","H:i:s"]';
$db->setQuery("INSERT INTO `#__em_eventsettings` (`id`, `json_data_events`, `json_data_general`, `google_username`, `google_password`, `google_category`,`google_location`,`fb_appid`,`fb_secret`,`fb_token`,`fb_category`,`fb_location`) VALUES
(1, ' ', '$dateformat','','','','','','','','','');");
$db->query();

$registration = '["Registration to {EventName}","<p>Hello {user},</p><p>You have been subscribed to {EventName} that will start on {EventStartDate}.</p>"]';
$activation = ' ["Activation email for {EventName}","<p>Hello {user},</p><p>Your request for participation to {EventName} has been approved.</p>"]';
$denied = '["Subscription denied","<p>Hello {user},</p><p>We regret to inform you but your subscription to {EventName} was denied.</p>"]';
$approved = '["Subscription approved","<p>Hello {user},</p><p>Your subscription to {EventName} was approved.</p>"]';
$invite = '["Invitation to {EventName}","<p>Hello,</p><p>This is an invitation to the event {EventName} that is starting on {EventStartDate}.</p>"]';
$reminder = '["Reminder for {EventName}","<p>Hello {user},</p><p>This is a reminder for {EventName} stating on {EventStartDate}.</p>"]';
$moderation = '["Event {EventName} requires moderation.","<p>New event requires moderation.<br /><br /></p>"]';
$subscription = '["You have a new subscription for {EventName} from {SubscriberName}!","<p>Hello {OwnerName},</p><p>a new subscription to your event {EventName} has been made.</p><p><strong>Subscriber info:</strong></p><ul><li>Date: {SubscribeDate}</li><li>Name: {SubscriberName}</li><li>Email: {SubscriberEmail}</li></ul><p><strong>Payment related info (if available):</strong></p><ul><li>Gateway: {PaymentGateway}</li><li>Tickets: {TicketsInfo}</li><li>Total: {TicketsTotal}</li></ul>"]';
$query = "INSERT INTO `#__em_emailsettings` (`id`, `fromemail`, `fromname`, `cc`, `bcc`, `registration`, `activation`, `denied`, `approved`, `invite`, `remainder`, `moderation`, `subscription`, `pop_id`) VALUES ".
         " (1, '', '', '', '','$registration','$activation','$denied','$approved','$invite','$reminder','$moderation','$subscription','registration') ";
$db->setQuery($query);
        $db->query();

$db->setQuery("INSERT INTO `#__em_paypal_settings` (`id`, `email`, `mode`) VALUES
(1, '', 0);");
$db->query();

$db->setQuery("INSERT INTO `#__em_authorized_settings` (`id`, `api`, `transaction_key`, `mode`, `email`) VALUES
(1, '', '', 0, '');");
$db->query();

$db->setQuery("INSERT INTO `#__em_wiretransfer_settings` (`id`, `details`) VALUES
(1, '<p><strong>Bank name:</strong> Your bank name</p><p><strong>Bank Address:</strong> your bank address</p><p><strong>Bank Account Number:</strong></p><p><strong>Swift BIC Number:</strong> <strong>Beneficiary:</strong></p>');");
$db->query();


}
$mainPath =JPATH_SITE . DS ."images".DS ."appthaeventz";
if(!is_dir($mainPath))//Exist or not
{
    mkdir($mainPath,0755,true);//create a directory
}
$profileImagePath = JPATH_SITE . DS ."images".DS ."appthaeventz";

if(!is_dir($profileImagePath))//Exist or not
{
    mkdir($profileImagePath,0755,true);//create a directory
}


$srcDir= JPATH_SITE . DS ."images".DS ."appthaeventz";
$destDir_original=JPATH_SITE . DS ."images".DS ."appthaeventz";

if (file_exists($destDir_original)) {
    if (is_dir($destDir_original)) {
      if (is_writable($destDir_original)) {
              if ($handle = opendir($srcDir)) {
                  while (false !== ($file = readdir($handle))) {
                           if (is_file($srcDir . '/' . $file)) {
                                   rename($srcDir . '/' . $file, $destDir_original . '/' . $file);

                           }
                  }
                  closedir($handle);
               }
       }
     }
 }

 ?>

<p style="font-style:normal;font-size:13px;font-weight:normal; margin-top:10px;margin-left:10px;"><a href="http://www.apptha.com" target="_blank"><img src="components/com_appthaeventz/assets/images/apptha.gif" alt="Joomla! Apptha Eventz Component Installed Successfully" align="left" />&nbsp;&nbsp;Apptha</a> Apptha Eventz</p>
<p> Component Apptha Eventz Installed Successfully <img src="components/com_appthaeventz/assets/images/ok.png" alt="Joomla! Apptha Eventz" align="left" /> </p>