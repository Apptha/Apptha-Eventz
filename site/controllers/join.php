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
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

require_once( JPATH_BASE . DS . 'components'. DS . 'com_appthaeventz'. DS . 'helpers' . DS . 'paypal.class.php' );
require_once( JPATH_BASE . DS . 'components'. DS . 'com_appthaeventz'. DS . 'helpers' . DS . 'fpdf.php' );

class appthaeventzControllerjoin extends JController {

    
    function display($cachable = false, $urlparams = false) {
        
                          
        parent::display();
    }

    function joinPaypal(){
        $model =  $this->getModel('join');
        $model->processPayment();
    }

    function joinWiretransfer(){
        $app = JFactory::getApplication();
        $model =  $this->getModel('join');
        $model->processWire();
        $msg = 'You have sucessfully subscribed for the Event';
        $app->redirect(JRoute::_('index.php?option=com_appthaeventz&view=eventlist', false), $msg);
    }

    function joinAuth(){
        $model =  $this->getModel('join');
        $model->authorizedProcess();
    }

    function joinEvent(){
        $app = JFactory::getApplication();
        $model =  $this->getModel('join');
        $model->join();
        $msg = 'You have sucessfully subscribed to the Event.';
        $app->redirect(JRoute::_('index.php?option=com_appthaeventz&view=eventlist', false), $msg);
    }
    
    function paypalReturn() {
        $app = JFactory::getApplication();              
        $msg = JText::_('Payment Successful.');
        $app->redirect(JRoute::_('index.php?option=com_appthaeventz&view=eventlist', false), $msg);
    }   
    
    function paypalCancel() {
        $app = JFactory::getApplication();
        $msg = JText::_('Payment canceled by User');
        $app->redirect(JRoute::_('index.php?option=com_appthaeventz&view=eventlist', false), $msg);
    }
 
    
}