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

/**
 * Banners list controller class.
 *
 * @package		
 * @subpackage	
 * @since		
 */
class AppthaeventzControllerevents extends JController
{
	 function display($cachable = false, $urlparams = false)
       {

        $viewName = JRequest::getVar('view', 'events');
        $view = $this->getView($viewName);
        if ($model = $this->getModel('events'))
        {
            $view->setModel($model, true);
        }
        $view->setLayout($viewName);
        $view->display();
       
       }

     function add()
     {
                $this->setRedirect('index.php?option=com_appthaeventz&view=addevent');
           
     }
    function edit()
     {
     $cid=JRequest::getVar('cid','','post','array');
      if($cid[0]):
                $this->setRedirect('index.php?option=com_appthaeventz&view=addevent&cid='.$cid[0]);
      endif;
    }
    function remove(){
         $arrayIDs = JRequest::getVar('cid', null, 'default', 'array');
         $model = $this->getModel('events');
         $insert=$model->deleteEvent($arrayIDs);
        if(!empty ($arrayIDs)){
            if(count($arrayIDs) == 1){
                $msg= count($arrayIDs)." banner successfully trashed...";
            }
            else{
                $msg= count($arrayIDs)." banners successfully trashed...";
            }
        }
        else{
            $msg= "Trashed...";
        }
         $this->setRedirect('index.php?option=com_appthaeventz&view=events',$msg);
    }
        
    function publish($cachable = false, $urlparams = false) // Function to publish a category
        {
            $detail = JRequest::get('POST');
            $model = $this->getModel('events');
            $model->pubEvents($detail);
           $this->setRedirect('index.php?option=com_appthaeventz&view=events');
        }

    function unpublish($cachable = false, $urlparams = false) // Function to publish a category
        {
            $detail = JRequest::get('POST');
            $model = $this->getModel('events');
            $model->pubEvents($detail);
           $this->setRedirect('index.php?option=com_appthaeventz&view=events');
        }

     function exportIcal(){
        $arrayIDs = JRequest::getVar('cid', null, 'default', 'array'); //Reads cid as an array
        if ($arrayIDs[0] === null)
        { //Make sure the cid parameter was in the request
            JError::raiseError(500, 'cid parameter missing from the request');
        }

        $model = $this->getModel('events');
        $res = $model->exportCal($arrayIDs);
        if($res == 0)
            $this->setRedirect('index.php?option=com_appthaeventz&view=events', 'Please select single file from the list to export','error');

    }


    function exportCsv(){
        $arrayIDs = JRequest::getVar('cid', null, 'default', 'array'); //Reads cid as an array
        if ($arrayIDs[0] === null)
        { //Make sure the cid parameter was in the request
            JError::raiseError(500, 'cid parameter missing from the request');
        }

        $model = $this->getModel('events');
        $res = $model->exportCsvfmt($arrayIDs);

    }
}
