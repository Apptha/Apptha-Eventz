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
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );


$controllerName = JRequest::getVar( 'view','dashboard');
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_appthaeventz/assets/css/subscription.css');
$document->addStyleSheet('components/com_appthaeventz/assets/appthaeventz.css');

$dashboard_active = $events_active = $categories_active = $subscriptions_active = $paymentintegrationclients_active = $import_active = $settings_active = 'false';
switch ($controllerName)
{
	case "dashboard":
		$dashboard_active = true;
		break;
	case "events":
		$events_active = true;
		break;
	case "categories":
		$categories_active = true;
		break;
            case "subscriptions":
		$subscriptions_active = true;
		break;
	case "paymentintegration":
		$paymentintegrationclients_active = true;
		break;
	case "import":
		$import_active = true;
		break;
        case "settings":
		$settings_active = true;
		break;
}

//adding menus

JSubMenuHelper::addEntry(JText::_('Dashboard'), 'index.php?option=com_appthaeventz&view=dashboard', $dashboard_active);
JSubMenuHelper::addEntry(JText::_('Events'), 'index.php?option=com_appthaeventz&view=events', $events_active);
JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_appthaeventz&view=categories', $categories_active);
JSubMenuHelper::addEntry(JText::_('Subscriptions'), 'index.php?option=com_appthaeventz&view=subscriptions', $subscriptions_active);
JSubMenuHelper::addEntry(JText::_('Payment Integration'), 'index.php?option=com_appthaeventz&view=paymentintegration', $paymentintegrationclients_active);
JSubMenuHelper::addEntry(JText::_('Import'), 'index.php?option=com_appthaeventz&view=import&task=add', $import_active);
JSubMenuHelper::addEntry(JText::_('Settings'), 'index.php?option=com_appthaeventz&view=settings', $settings_active);


// managing controllers

// Temporary interceptor
$task = JRequest::getCmd('task');

require_once( JPATH_COMPONENT.DS.'controllers'.DS.$controllerName.'.php' );

$controllerName = 'AppthaeventzController'.$controllerName;
// Create the controller
$controller = new $controllerName();

// Perform the Request task
$controller->execute( $task );

// Redirect if set by the controller
$controller->redirect();


