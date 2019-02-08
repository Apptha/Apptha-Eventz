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

class AppthaeventzControllerajaxfind extends JController
{
    /**
     * Method to display the view
     */
    function display($cachable = false, $urlparams = false)
    {
           //get user id for logincheck
        parent::display();
    }

    function getCategory()      //to get the count of the category which already exists
    {

        $mainframe = JFactory::getApplication();

        $cat_name = JRequest::getVar('name');

        $model = $this->getModel('categories');
        $results = $model->getCategoryCount($cat_name);
        echo $results;
        die();
    }

    function getEditCategory()      //to get the count of the category which already exists
    {

        $mainframe = JFactory::getApplication();

        $cat_id = JRequest::getInt('id');
        $cat_name = JRequest::getVar('name');

        $model = $this->getModel('categories');
        $results = $model->getEditCategoryCount($cat_name,$cat_id);
        echo $results;
        die();
    }

    function getSubscriber()      //to get the count of the category which already exists
    {

        $mainframe = JFactory::getApplication();

        $cat_name = JRequest::getVar('name');

        $model = $this->getModel('subscriptions');
        $results = $model->getSubscriberCount($cat_name);
        echo $results;
        die();
    }

    function getEditSubscriber()      //to get the count of the category which already exists
    {

        $mainframe = JFactory::getApplication();

        $cat_id = JRequest::getInt('id');
        $cat_name = JRequest::getVar('name');

        $model = $this->getModel('subscriptions');
        $results = $model->getEditSubscriberCount($cat_name,$cat_id);
        echo $results;
        die();
    }

    function getTicketCount()      //to get the count of the tickets
    {

        $mainframe = JFactory::getApplication();

        $tkt_id = JRequest::getInt('id');
        //$cat_name = JRequest::getVar('name');
        if($tkt_id != 0){
            $model = $this->getModel('subscriptions');
            $results = $model->getCount($tkt_id);
        }else{
            $results = '';
        }
        echo $results;
        die();
    }
    
    
}
?>