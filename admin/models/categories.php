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
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
jimport('joomla.filesystem.file');


class AppthaeventzModelcategories extends JModel {

   function getCategories() {

        global $option, $mainframe;
        $mainframe = JFactory::getApplication();
        $total = 0;
        $option = 'com_categories';
        $filter_order = $mainframe->getUserStateFromRequest($option . 'filter_order_category', 'filter_order', 'a.lft', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option . 'filter_order_Dir', 'filter_order_Dir', 'asc', 'word');
        $filter_id = $mainframe->getUserStateFromRequest($option . 'filter_id', 'filter_id', '', 'int');
        $search_filter = JRequest::getVar('filter_search');
        $status_filter_category=  JRequest::getInt('filter_category');//get the status of the category
        // page navigation
        $limit = $mainframe->getUserStateFromRequest($option . '.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest($option . '.limitstart', 'limitstart', 0, 'int');

        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        //search filter
        $where = '';
        $arrWhere = array();
        if($search_filter != '')
        {
            $arrWhere[] = " WHERE a.name like '%$search_filter%' ";
        }else if($status_filter_category=='1')
        {
           
             $arrWhere[] = " WHERE a.published='1'"; //query for displaying the category
        }else if($status_filter_category=='2')
        {
            
             $arrWhere[] = " WHERE a.published='0'"; //query for displaying the category
        }else if($status_filter_category=='3')
        {
            
             $arrWhere[] = " WHERE a.published='-2'"; //query for displaying the category
        }else if($status_filter_category=='4')
        {
             $arrWhere[] = " WHERE a.published!='-2'"; //query for displaying the category
        }else {
            
                    $arrWhere[]=" WHERE a.published!='-2'";
                }
        
        

        if(!empty($arrWhere) )
            $where = implode('and',$arrWhere);

        $db =  JFactory::getDBO();
     
        $query = 'SELECT a.id , a.name , COUNT(DISTINCT b.id) AS level,a.parent_id,a.published,a.color' .
                 ' FROM #__em_categories AS a' .
                 ' LEFT JOIN `#__em_categories` AS b ON a.lft > b.lft AND a.rgt < b.rgt' .
                 $where .                        //' WHERE a.published = 1' .
                 ' GROUP BY a.id, a.name, a.lft, a.rgt' ;
                 ' ORDER BY a.lft ASC';
                 
        $db->setQuery($query);
        //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
            $db->query();
            $total = $db->getNumRows();
        }

        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);

        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;
        $lists['search'] = $search_filter;

        $db->setQuery("$query ORDER BY $filter_order $filter_order_Dir LIMIT $pageNav->limitstart,$pageNav->limit");
        //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
            $categoriesList = $db->loadObjectList();
        }
    
        return array('pageNav' => $pageNav, 'limitstart' => $limitstart, 'limit' => $limit, 'lists' => $lists, 'categoriesList' => $categoriesList,'order_Dir'=>$filter_order_Dir,'order'=>$filter_order);
    }


   function newCategory(){
       $db=JFactory::getDbo();
        global $option, $mainframe;
        $mainframe = JFactory::getApplication();

        $db->setQuery(
			'SELECT a.id AS value, a.name AS text, COUNT(DISTINCT b.id) AS level' .
			' FROM #__em_categories AS a' .
			' LEFT JOIN `#__em_categories` AS b ON a.lft > b.lft AND a.rgt < b.rgt' .
			' WHERE a.published = 1' .
			' GROUP BY a.id, a.name, a.lft, a.rgt' .
			' ORDER BY a.lft ASC'
		);
        $options = $db->loadObjectList();

        foreach ($options as &$option)
        {
                $option->text = str_repeat('- ', $option->level).$option->text;
        }

        return $options;
   }

   function editCategory($id){      //To get the edit details
       $db=JFactory::getDbo();
        global $option, $mainframe;
        $mainframe = JFactory::getApplication();
        
        $arrayIDs = JRequest::getVar('cid', null, 'default', 'array'); //Reads cid as an array

        $query = 'SELECT id,name,color,description,parent_id,lft,rgt,published FROM #__em_categories where id = '.$id;
        $db->setQuery( $query );
        //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
            $category = $db->loadObject();
        }
        

        $db->setQuery(
			'SELECT a.id AS value, a.name AS text,a.lft, COUNT(DISTINCT b.id) AS level' .
			' FROM #__em_categories AS a' .
			' LEFT JOIN `#__em_categories` AS b ON a.lft > b.lft AND a.rgt < b.rgt' .
			' WHERE a.published = 1 ' .
			' GROUP BY a.id, a.name, a.lft, a.rgt' .
			' ORDER BY a.lft ASC'
        );
       
        $categorylist = $db->loadObjectList();

        foreach ($categorylist as &$option)
        {
                $option->text = str_repeat('- ', $option->level).$option->text;
        }

        return array($category, $categorylist);

   }

   function saveCategory(){         //To save the categories
    $db=JFactory::getDbo();

    $row =  $this->getTable('categories');

    $detail = JRequest::get('post');
    $detail = array_map('trim',$detail);    //to remove white spaces


    if (!$row->bind($detail)) {
        JError::raiseError(500, 'Error binding data');
    }

    if ($row->id == $row->parent_id)
            $row->parent_id = 0;


    if (!$row->check()) {
        JError::raiseError(500, 'Invalid data');
    }
    if (!$row->store()) {
        $errorMessage = $row->getError();
        JError::raiseError(500, 'Error binding data: ' . $errorMessage);
    }
    
    $this->rebuild(0, 0);

    $row->checkin(); // to get the last inserted id
    $row_id = $row->id;

    return $row_id;

   }

   public function getCategoryCount($catName){      //ajax function to find category already exists
       $db = JFactory::getDBO();
       $query = "SELECT id FROM `#__em_categories` WHERE `name` = '$catName' and published != '-2' ";
       $db->setQuery( $query );
       //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
             $objResult = $db->loadResult();
        }
      
       return $objResult;
   }

   public function getEditCategoryCount($catName,$catId){      //ajax function to find category already exists in Edit
       $db = JFactory::getDBO();
       $query = "SELECT id FROM `#__em_categories` WHERE `name` = '$catName' and `id` != '$catId' and published != '-2'  ";
       $db->setQuery( $query );
        //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
             $objResult = $db->loadResult();
        }
       return $objResult;
   }

   public function rebuild($parent_id = 0, $left = 0)
   {
        // get the database object
        $db = JFactory::getDBO();

        // get all children of this node
        $db->setQuery(
                'SELECT id FROM #__em_categories
                 WHERE parent_id='. (int)$parent_id .
                ' ORDER BY parent_id, name'
        );
        $children = $db->loadObjectList();

        // the right value of this node is the left value + 1
        $right = $left + 1;

        // execute this function recursively over all children
        for ($i=0, $n=count($children); $i < $n; $i++)
        {
                // $right is the current right value, which is incremented on recursion return
                $right = $this->rebuild($children[$i]->id, $right);

                // if there is an update failure, return false to break out of the recursion
                if ($right === false) {
                        return false;
                }
        }

        // we've got the left value, and now that we've processed
        // the children of this node we also know the right value
        $db->setQuery(
                'UPDATE #__em_categories
                 SET lft='. (int)$left .', rgt='. (int)$right .
                ' WHERE id='. (int)$parent_id
        );
        // if there is an update failure, return false to break out of the recursion
        if (!$db->query()) {
                return false;
        }

        // return the right value of this node + 1
        return $right + 1;
    }

    function pubCategories($arrayIDs,$detail) {     //publish and unpublish categories
        $db = JFactory::getDBO();

            $publish = 1;


        if(count($arrayIDs))
        {
                asort($arrayIDs);
                $cids = implode( ',', $arrayIDs );
                $query = "SELECT lft,rgt,parent_id FROM `#__em_categories` WHERE `id` IN ( $cids ) ";
                $db->setQuery( $query );
                $options = $db->loadObjectList();

                foreach ($options as &$option)
                {
                    $lft = $option->lft;
                    $rgt = $option->rgt;

                    if($option->parent_id == '0'){
                        $query = "UPDATE `#__em_categories` SET published = '$publish' WHERE `lft` BETWEEN $lft AND $rgt ";
                        $db->setQuery( $query );
                        $db->query();
                        $a = 1;
                    }else{
                        //To check whether published or not
                        $query = "SELECT published FROM `#__em_categories` WHERE `id` = '$option->parent_id' ";
                        $db->setQuery( $query );
                        $objPublish = $db->loadObject();

                        if($objPublish->published != '0'){  //Allow only if published
                            $query = "UPDATE `#__em_categories` SET published = '$publish' WHERE `lft` BETWEEN $lft AND $rgt  ";
                            $db->setQuery( $query );
                            $db->query();
                            $a = 1;
                        }else{
                            $a = 0;
                        }
                    }
                }
               
             return $a;
        }

    }

     function unpubCategories($arrayIDs,$detail) {     //publish and unpublish categories
        $db = JFactory::getDBO();

        $publish = 0;

        if(count($arrayIDs))
        {
                asort($arrayIDs);
                $cids = implode( ',', $arrayIDs );
                $query = "SELECT lft,rgt,parent_id FROM `#__em_categories` WHERE `id` IN ( $cids ) ";
                $db->setQuery( $query );
                $options = $db->loadObjectList();
                $a = 0;
                foreach ($options as &$option)
                {
                    $lft = $option->lft;
                    $rgt = $option->rgt;

                    $query = "SELECT id FROM `#__em_categories` WHERE lft between $lft and $rgt and published != '-2' ";
                    $db->setQuery( $query );
                    $eventIds = $db->loadResultArray();
                    $res = array();
                    foreach($eventIds as $ev){
                        $res[] = $ev.',|,'.$ev.'|'.$ev;
                    }
                    $result = implode('|',$res);

                    $query = "SELECT count(id) FROM `#__em_events` WHERE category_id REGEXP '$result'";
                    $db->setQuery( $query );
                    $eventCount = $db->loadResult();

                    if($eventCount <= 0){
                        $query = "UPDATE `#__em_categories` SET published = '$publish' WHERE `lft` BETWEEN $lft AND $rgt and published != '-2' ";
                        $db->setQuery( $query );
                        $db->query();
                    }else{
                        $a++;
                    }

                }
        }
        
        
            return $a;
    }

    function deleteCategories($arrayIDs) {  //delete the categories

        global $db;
        $db = JFactory::getDBO();
        if(count($arrayIDs))
        {
                $cids = implode( ',', $arrayIDs );
                $query = "SELECT id,lft,rgt FROM `#__em_categories` WHERE `id` IN ( $cids )";
                $db->setQuery( $query );
                $options = $db->loadObjectList();
                $flag = 0;
                foreach ($options as $option)
                {
                    $query = "SELECT count(id) FROM `#__em_events` WHERE category_id REGEXP ',$option->id|$option->id,|$option->id' AND published !='-2' ";
                    $db->setQuery( $query );
                    $eventCount = $db->loadResult();

                    if( $eventCount > 0 ){
                        $flag++;
                    }else{
                        $lft = $option->lft;
                        $rgt = $option->rgt;
                        $query = "UPDATE `#__em_categories` SET  published = '-2' WHERE `lft` BETWEEN $lft AND $rgt";
                        $db->setQuery( $query );
                        $db->query();
                    }

                }
                $this->rebuild(0, 0);
                $objStat = ($flag > 0)?'0':'1';
                return $objStat;
        }


    }

}

?>
