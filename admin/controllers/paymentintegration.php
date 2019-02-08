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
// No direct access.
defined('_JEXEC') or die('Restricted access');
// import Joomla controller library
jimport('joomla.application.component.controller');

class AppthaeventzControllerpaymentintegration extends JController {

    function display($cachable = false, $urlparams = false) {

        $viewName = JRequest::getVar('view', 'paymentintegration');
        $viewLayout = JRequest::getVar('layout', 'paymentintegration');
        $view = $this->getView($viewName);
        if ($model = $this->getModel('paymentintegration')) {
            $view->setModel($model, true);
        }
        //set layout for paymentintegration
        $view->setLayout($viewLayout);
        $view->display();
    }

    function save() {

        $detail = JRequest::get('POST');

        $cid = $detail['cid'];
        $model = $this->getModel('paymentintegration');
        if ($cid == '1') {
           
            //call savewiretransfermodel function to save wiretransfer details.
            $model->savewiretransfer($detail);
            //redirect if the category is wiretransfer
            $this->setRedirect('index.php?option=com_appthaeventz&view=paymentintegration', 'wiretransfer details Saved!');
        } else if ($cid == '2') {
            //call savewiretransfermodel function to save paypal details.
            $model->savepaypal($detail);
            //redirect if the category is paypal
            $this->setRedirect('index.php?option=com_appthaeventz&view=paymentintegration', 'Paypal details Saved!');
        } else if ($cid == '3') {
            //call savewiretransfermodel function to save authorized details.
            $model->saveauthorized($detail);
            //redirect if the category is authorized .net
            $this->setRedirect('index.php?option=com_appthaeventz&view=paymentintegration', 'Authorized.Net details Saved!');
        }
    }

    function apply() {

        $detail = JRequest::get('POST');

        $cid = $detail['cid'];
        $model = $this->getModel('paymentintegration');
        if ($cid == '1') {

            //call savewiretransfermodel function to save wiretransfer details.
            $model->savewiretransfer($detail);
            //redirect if the category is wiretransfer
            $this->setRedirect('index.php?option=com_appthaeventz&view=paymentintegration&task=edit&cid=1', 'wiretransfer details Saved!');
        } else if ($cid == '2') {
           //call savewiretransfermodel function to save paypal details.
            $model->savepaypal($detail);
            //redirect if the category is paypal
            $this->setRedirect('index.php?option=com_appthaeventz&view=paymentintegration&task=edit&cid=2', 'Paypal details Saved!');
        } else if ($cid == '3') {
            //call savewiretransfermodel function to save authorized details.
            $model->saveauthorized($detail);
            //redirect if the category is authorized .net
            $this->setRedirect('index.php?option=com_appthaeventz&view=paymentintegration&task=edit&cid=3', 'Authorized.Net details Saved!');
        }
    }

    function publish() {
        $detail = JRequest::get('POST');
        $model = $this->getModel('paymentintegration');
        //call to changestatus model function to publish the selected payment method
        $model->changeStatus($detail);
        $this->setRedirect('index.php?option=com_appthaeventz&view=paymentintegration');
    }

    function unpublish() {
        $detail = JRequest::get('POST');
        $model = $this->getModel('paymentintegration');
        //call to changestatus model function to unpublish the selected payment method
        $model->changeStatus($detail);
        $this->setRedirect('index.php?option=com_appthaeventz&view=paymentintegration');
    }

    function cancel() {
        $cid = JRequest::getVar('cid');      
        $this->setRedirect('index.php?option=com_appthaeventz&view=paymentintegration');
        
    }

}
