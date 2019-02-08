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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');


class AppthaeventzControllersubscriptions extends JController
{
    function display($cachable = false, $urlparams = false) //Function to display the subscription
       {

        $viewName   = JRequest::getVar( 'view', 'subscriptions' );
        $viewLayout = JRequest::getVar( 'layout', 'subscriptions' );
        $view =  $this->getView($viewName);
        if ($model = $this->getModel('subscriptions'))
        {
            $view->setModel($model, true);
        }

        $view->setLayout($viewLayout);
        $view->display();
    }

    function add($cachable = false, $urlparams = false) // Function to add a new subscription
    {
        $view =  $this->getView('subscriptions');
        if ($model =  $this->getModel('subscriptions'))
        {
            $view->setModel($model, true);
        }
        
        $view->setLayout('addsubscriptions');
        $view->display();
    }

    function edit($cachable = false, $urlparams = false) // Function to edit a particular subscription
    {
        $view =  $this->getView('subscriptions');
        if ($model =  $this->getModel('subscriptions'))
        {
            $view->setModel($model, true);
        }
        $view->setLayout('editsubscriptions');
        $view->display();
    }

    function save($cachable = false, $urlparams = false) // Function to store and stay on the same page till we click on save button[Apply]
    {

            $model =  $this->getModel('subscriptions');

            $insert_id = $model->saveSubscriptions();
            if($insert_id)
            {

            $link = 'index.php?option=com_appthaeventz&view=subscriptions&task=edit&cid[]='.$insert_id;
            $this->setRedirect($link, 'Subscriptions saved!');
            }

    }
    
    function saveclose($cachable = false, $urlparams = false) // Function to store and close
    {

            $model =  $this->getModel('subscriptions');

            $insert_id = $model->saveSubscriptions();
            if($insert_id)
            {

            $link = 'index.php?option=com_appthaeventz&view=subscriptions';
            $this->setRedirect($link, 'Subscriptions saved!');
            }

    }


    function apply($cachable = false, $urlparams = false) // Function to store and stay on the same page till we click on save button[Apply]
    {
        $model =  $this->getModel('subscriptions');
        $insert_id = $model->applySubscriptions();
        
        if($insert_id)
        {


        $link = 'index.php?option=com_appthaeventz&view=subscriptions&task=edit&cid[]='.$insert_id;
        $this->setRedirect($link, 'Subscriptions updated!');
        }

    }

    function applyclose($cachable = false, $urlparams = false) // Function to store and stay on the same page till we click on save button[Apply]
    {
        $model =  $this->getModel('subscriptions');
        $insert_id = $model->applySubscriptions();

        if($insert_id)
        {


        $link = 'index.php?option=com_appthaeventz&view=subscriptions';
        $this->setRedirect($link, 'Subscriptions updated!');
        }

    }

    function cancel($cachable = false, $urlparams = false) // Function to cancel some operation
    {
        $this->setRedirect('index.php?option=com_appthaeventz&view=subscriptions');
    }


    function remove($cachable = false, $urlparams = false) // Function to delete a subscription
    {
        $arrayIDs = JRequest::getVar('cid', null, 'default', 'array'); //Reads cid as an array
        if ($arrayIDs[0] === null)
        { //Make sure the cid parameter was in the request
            JError::raiseError(500, 'cid parameter missing from the request');
        }
        $model = $this->getModel('subscriptions');
        $model->deleteSubscriptions($arrayIDs);
         $this->setRedirect('index.php?option=com_appthaeventz&view=subscriptions','Trashed...');
    }

    function approve($cachable = false, $urlparams = false) // Function to approve a subscription
    {

        $detail = JRequest::get('POST');
        $model = $this->getModel('subscriptions');
        $model->appSubscriptions($detail);

       $this->setRedirect('index.php?option=com_appthaeventz&view=subscriptions');
    }

    function pending($cachable = false, $urlparams = false) // Function to pend a subscription
    {

        $detail = JRequest::get('POST');
        $model = $this->getModel('subscriptions');
        $model->pendSubscriptions($detail);

       $this->setRedirect('index.php?option=com_appthaeventz&view=subscriptions');
    }

    function deny($cachable = false, $urlparams = false) // Function to deny a subscription
    {

        $detail = JRequest::get('POST');
        $model = $this->getModel('subscriptions');
        $model->denySubscriptions($detail);

       $this->setRedirect('index.php?option=com_appthaeventz&view=subscriptions');
    }

    function resend($cachable = false, $urlparams = false) // Function to deny a subscription
    {
        $arrayIDs = JRequest::getVar('cid', null, 'default', 'array'); //Reads cid as an array
        $model = $this->getModel('subscriptions');
        $success = $model->resendMailActivation();
        if($success != '')
            $this->setRedirect('index.php?option=com_appthaeventz&view=subscriptions&task=edit&cid[]='.$success,'Activation mail has been sent successfully...');
    }
}
?>
