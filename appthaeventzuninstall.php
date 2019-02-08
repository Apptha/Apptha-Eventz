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

/**
 * Description :    Uninstallation file
 */
jimport('joomla.installer.installer');
//No direct access
defined('_JEXEC') or die('Restricted access');
error_reporting(0);
// Imports
jimport('joomla.installer.installer');
$db = JFactory::getDBO();

$db->setQuery("DROP TABLE IF EXISTS `#__em_authorized_settings_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_authorized_settings` TO `#__em_authorized_settings_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_categories_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_categories` TO `#__em_categories_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_contacts_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_contacts` TO `#__em_contacts_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_control_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_control` TO `#__em_control_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_emailsettings_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_emailsettings` TO `#__em_emailsettings_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_events_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_events` TO `#__em_events_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_eventsettings_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_eventsettings` TO `#__em_eventsettings_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_events_images_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_events_images` TO `#__em_events_images_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_events_options_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_events_options` TO `#__em_events_options_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_events_recurring_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_events_recurring` TO `#__em_events_recurring_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_importevents_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_importevents` TO `#__em_importevents_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_locations_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_locations` TO `#__em_locations_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_metas_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_metas` TO `#__em_metas_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_orderlist_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_orderlist` TO `#__em_orderlist_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_others_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_others` TO `#__em_others_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_paymentmethod_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_paymentmethod` TO `#__em_paymentmethod_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_paypal_settings_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_paypal_settings` TO `#__em_paypal_settings_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_reviews_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_reviews` TO `#__em_reviews_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_subscriptions_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_subscriptions` TO `#__em_subscriptions_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_tags_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_tags` TO `#__em_tags_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_ticketlayout_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_ticketlayout` TO `#__em_ticketlayout_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_tickets_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_tickets` TO `#__em_tickets_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__em_wiretransfer_settings_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__em_wiretransfer_settings` TO `#__em_wiretransfer_settings_backup`");
$db->query();


?>