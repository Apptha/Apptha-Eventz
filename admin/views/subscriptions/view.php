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
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
class AppthaeventzViewsubscriptions extends JView {

    function display($tpl=NULL) {
        $task = JRequest::getVar('task');
        if ($task == 'add') {
                JToolBarHelper::title('Subscriptions' . ': [<small>Add</small>]', 'subscriptions.png');
                JToolBarHelper::apply('save');
                JToolBarHelper::save('saveclose');
                JToolBarHelper::cancel();
                
                $model = $this->getModel('subscriptions');
                $subscriptionList = $model->newSubscriptions();
                $this->assignRef('subscriptionList', $subscriptionList);
                parent::display();
        }

        if ($task == 'edit') {

            JToolBarHelper::title('Subscriptions' . ': [<small>Edit</small>]', 'subscriptions.png');
            JToolBarHelper::apply();
            JToolBarHelper::save('applyclose');
            JToolBarHelper::cancel();

            $model = $this->getModel();
            $id = JRequest::getVar('cid');
            $editSubscriptions = $model->editSubscriptions($id[0]);
            $this->assignRef('editSubscriptions', $editSubscriptions[0]);
            $this->assignRef('subscriptionList', $editSubscriptions[1]);
          
            parent::display();
        }

        if ($task == '') {
            JToolBarHelper::title('Subscriptions', 'subscriptions.png');
            JToolBarHelper::addNewX();
            JToolBarHelper::editListX();
            JToolBarHelper::customX('approve','approve.png','','Approve',true);
            JToolBarHelper::customX('pending','pending.png','','Pending',true);
            JToolBarHelper::customX('deny','deny.png','','Deny',true);
            JToolBarHelper::trash();
         
            $model = $this->getModel('subscriptions');
            $categories = $model->getSubscriptions();
                
            $this->assignRef('subscriptionsList', $categories);

            parent::display();
        }
    }

}

?>
