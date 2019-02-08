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
class AppthaeventzViewcategories extends JView {

    function display($tpl=NULL) {
        $task = JRequest::getVar('task');
        if ($task == 'add') {
                JToolBarHelper::title('Event Management: Add A New Category', 'category.png');
                JToolBarHelper::apply();
                JToolBarHelper::save();
                JToolBarHelper::cancel();
               
                $model = $this->getModel('categories');
                $categories = $model->newCategory();
                $this->assignRef('categoriesList', $categories);
                parent::display();
        }

        if ($task == 'edit') {

            JToolBarHelper::title('Categories' . ': [<small>Edit</small>]', 'category.png');
            JToolBarHelper::apply();
            JToolBarHelper::save();
            JToolBarHelper::cancel();

            $model = $this->getModel();
            $id = JRequest::getVar('cid');
            $editCategory = $model->editCategory($id[0]);
            $this->assignRef('editCategory', $editCategory[0]);
            $this->assignRef('categoryList', $editCategory[1]);
          
            parent::display();
        }

        if ($task == '') {
            JToolBarHelper::title('Categories', 'category.png');
            JToolBarHelper::addNewX();
            JToolBarHelper::editListX();
            JToolBarHelper::publishList();
            JToolBarHelper::unpublishList();
            JToolBarHelper::trash();
            
            $model = $this->getModel('categories');
            $categories = $model->getCategories();
            
            $this->assignRef('categoriesList', $categories);

            parent::display();
        }
    }

}

?>
