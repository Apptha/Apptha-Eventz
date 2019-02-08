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


class AppthaeventzControllercategories extends JController
{
        function __construct()
        {
            parent::__construct();

            // Register Extra tasks

            $this->registerTask( 'orderup' , 'move');
            $this->registerTask( 'orderdown' , 'move');
        }

        function display($cachable = false, $urlparams = false) //Function to display the Category list
        {

            $viewName   = JRequest::getVar( 'view', 'categories' );
            $view =  $this->getView($viewName);
            if ($model = $this->getModel('categories'))
            {
                $view->setModel($model, true);
            }

            $view->setLayout($viewName);
            $view->display();
        }

        function add($cachable = false, $urlparams = false) // Function to add a new category
        {
            $view =  $this->getView('categories');
            if ($model =  $this->getModel('categories'))
            {
                $view->setModel($model, true);
            }
            $view->setLayout('addcategories');
            $view->display();
        }

        function edit($cachable = false, $urlparams = false) // Function to edit a particular category
        {
            $view =  $this->getView('categories');
            if ($model =  $this->getModel('categories'))
            {
                $view->setModel($model, true);
            }
            $view->setLayout('editcategories');
            $view->display();
        }

        function apply($cachable = false, $urlparams = false) // Function to store and stay on the same page till we click on save button[Apply]
        {

            $model =  $this->getModel('categories');

            $insert_id = $model->saveCategory();
            if($insert_id)
            {
                $link='index.php?option=com_appthaeventz&view=categories&task=edit&cid[]='.$insert_id;
                $this->setRedirect($link, 'Category Saved!');
            }
            else {
                $link='index.php?option=com_appthaeventz&view=categories';
                $this->setRedirect($link, 'Category Already Exists','error');
            }

        }

        function save($cachable = false, $urlparams = false) // Function to store and stay on the same page till we click on save button[Apply]
        {

            $model =  $this->getModel('categories');

            $insert_id = $model->saveCategory();
            if($insert_id)
            {
                $link='index.php?option=com_appthaeventz&view=categories';
                $this->setRedirect($link, 'Category Saved!');
            }
            else {
                $link='index.php?option=com_appthaeventz&view=categories';
                $this->setRedirect($link, 'Category Already Exists','error');
            }

        }

        function cancel($cachable = false, $urlparams = false) // Function to cancel some operation
        {
            $this->setRedirect('index.php?option=com_appthaeventz&view=categories');
        }

        function publish($cachable = false, $urlparams = false) // Function to publish a category
        {
            $detail = JRequest::get('POST');
            $arrayIDs = JRequest::getVar('cid', null, 'default', 'array'); //Reads cid as an array
            $model = $this->getModel('categories');
            $res = $model->pubCategories($arrayIDs,$detail);
            if($res)
                $this->setRedirect('index.php?option=com_appthaeventz&view=categories');
            else
                $this->setRedirect('index.php?option=com_appthaeventz&view=categories', 'Unable to publish some record(s) since parent is unpublished','error');

        }

         function unpublish($cachable = false, $urlparams = false) // Function to unpublish a category
         {
            $detail = JRequest::get('POST');
            $arrayIDs = JRequest::getVar('cid', null, 'default', 'array'); //Reads cid as an array
            $model = $this->getModel('categories');
            $res = $model->unpubCategories($arrayIDs,$detail);
            if($res == 0 )
                $this->setRedirect('index.php?option=com_appthaeventz&view=categories');
            else
                $this->setRedirect('index.php?option=com_appthaeventz&view=categories', 'Unable to unpublish some record(s) since event(s) uses the category','error');

          }

        function remove($cachable = false, $urlparams = false) // Function to delete a category
        {
            $arrayIDs = JRequest::getVar('cid', null, 'default', 'array'); //Reads cid as an array
            if ($arrayIDs[0] === null)
            { //Make sure the cid parameter was in the request
                JError::raiseError(500, 'cid parameter missing from the request');
            }
            $model = $this->getModel('categories');
            $delStatus = $model->deleteCategories($arrayIDs);
            if($delStatus == '0')
                $this->setRedirect('index.php?option=com_appthaeventz&view=categories','Unable to trash since the Event(s) uses the category','error');
            else
                $this->setRedirect('index.php?option=com_appthaeventz&view=categories','Trashed...');
        }

        //save order
        function saveOrder()
        {
                $model = $this->getModel('categories');
                $model->saveorder();
        }

        //move
        function move()
        {
                $model = $this->getModel('categories');
                $model->move();
        }

}
?>
