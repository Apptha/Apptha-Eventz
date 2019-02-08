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
JHTML::_('behavior.modal');
/**
 * Banners list controller class.
 *
 * @package		
 * @subpackage	
 * @since		
 */
class AppthaeventzControlleraddevent extends JController
{
	 function display($cachable = false, $urlparams = false)
       {

        $viewName = JRequest::getVar('view', 'addevent');
        $view = $this->getView($viewName);
        if ($model = $this->getModel('addevent'))
        {
            $view->setModel($model, true);
        }
        $view->setLayout($viewName);
        $view->display();
       
     }

     function cancel()
     {
        $this->setRedirect('index.php?option=com_appthaeventz&view=events');
     }

     function apply()
     {
      $model = $this->getModel('addevent');
      $cid = JRequest::getvar('cid',null,'get' );
      $tabing = JRequest::getvar('tabs',null,'post' );

            if($cid):
                $id = $model->updateevents();
                else:
                $id = $model->saveevents();
                endif;
                $msg="Event Saved";
                $this->setRedirect('index.php?option=com_appthaeventz&view=addevent&cid='.$id.'&tabs='.$tabing,$msg);

     }
      function save()
     {
      $model = $this->getModel('addevent');
      $cid = JRequest::getvar('cid',null,'get' );

            if($cid):
                $id = $model->updateevents();
                else:
                $id = $model->saveevents();
                endif;
                $msg="Event Saved";
                $this->setRedirect('index.php?option=com_appthaeventz&view=events',$msg);
            
     }

     function upload() //upload image
     {
         $model = $this->getModel('addevent');
         $img_name = $model->uploads();
                $doc = JFactory::getDocument();
                $js = "
                    window.parent.document.getElementById('img_name').value = '".$img_name."';
                    window.parent.SqueezeBox.close(); ";
                $doc->addScriptDeclaration($js);
    }
    function multiupload()
    {
         $id = JRequest::getvar('cid',null,'GET' );
         $model = $this->getModel('addevent');
         $model->multiuploads();
         $this->setRedirect('index.php?option=com_appthaeventz&view=addevent&layout=multiupload&tmpl=component&cid='.$id);

    }

    function location() //location
     {
    $model = $this->getModel('addevent');
    $location_name = JRequest::getvar('location_name',null,'post' );
    $address = JRequest::getvar('address',null,'post' );
    $lat = JRequest::getvar('lat',null,'post' );
    $lng = JRequest::getvar('lng',null,'post' );
    $doc = JFactory::getDocument();
    $js = "
    window.parent.document.getElementById('location').value = '".$location_name."';
    window.parent.document.getElementById('address').value = '".$address."';
    window.parent.document.getElementById('latitude').value = '".$lat."';
    window.parent.document.getElementById('longitude').value = '".$lng."';
    window.parent.SqueezeBox.close(); ";
    $doc->addScriptDeclaration($js);
    }

    function gettag()
    {
        $model = $this->getModel('addevent');
        $model->getTag();
    }
   
    function category()
    {
        $tabs = JRequest::getvar('tabs',null,'post' );
        $cid = JRequest::getvar('cid',null,'post' );
        $model =  $this->getModel('addevent');
        $c=$model->checkCategory();
        if($c)
        {
         $msg="Category Already Exists";
         $this->setRedirect('index.php?option=com_appthaeventz&view=addevent&layout=category&tmpl=component&cid='.$cid,$msg);

        }
        $model =  $this->getModel('categories');
        $model->saveCategory();
        $doc = JFactory::getDocument();
                $js = "window.parent.location.reload();
                       window.parent.SqueezeBox.close(); ";
                $doc->addScriptDeclaration($js);
    }
    function imagedelete()
    {
         $id = JRequest::getvar('cid',null,'GET' );
         $model = $this->getModel('addevent');
         $model->imageDelete();
         $this->setRedirect('index.php?option=com_appthaeventz&view=addevent&layout=multiupload&tmpl=component&cid='.$id);

    }
    function recurring()
    {
        $model = $this->getModel('addevent');
        $model->recurring();
    }

    

    
     
}
