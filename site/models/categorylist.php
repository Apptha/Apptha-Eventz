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
//no direct access
defined('_JEXEC') or die();
jimport('joomla.application.component.model');
jimport('joomla.user.helper');

class appthaeventzModelcategorylist extends JModel {
public function categoryList()
{
    $db = JFactory::getDBO();

    $option = 'com_appthaeventz';
        // page navigation
    $page='';
    $limit = 5; //how many items to show per page

    $pageName = JRequest::getVar('page');
    if(isset($pageName) )
        $page = $pageName;
    if($page)
            $start = ($page - 1) * $limit; 	//first item to display on this page
    else
            $start = 0;

    $query="SELECT  count(id)
            FROM  `#__em_categories`
            WHERE `published` =1";
    $db->setQuery($query);
    $total = $db->loadResult();

    $query="SELECT  `name`,`id` ,`description`
            FROM  `#__em_categories` 
            WHERE `published` =1 LIMIT $start, $limit";
    $db->setQuery($query);
    $category_list = $db->loadObjectList();

    $total_pages = $total;
    $pagination = $this->getPagination($total_pages,$page,$limit);

    return array('pageNav' => $pagination, 'category_list' => $category_list);

}

function getPagination($total_pages,$page,$limit)
    {
        // How many adjacent pages should be shown on each side?
        $adjacents = 3;
        $targetpage = "index.php?option=com_appthaeventz&view=categorylist"; 	//your file name  (the name of this file)

        /* Setup page vars for display. */
        if ($page == 0) $page = 1;
        $prev = $page - 1;
        $next = $page + 1;
        $lastpage = ceil($total_pages/$limit);
        $lpm1 = $lastpage - 1;

        $pagination = '';//array();
        $pagination1 = "";
        $pagination2 = "";
        $pagination3 = "";
        if($lastpage > 1)
        {
            $pagination .= "<ul class=\"pagination\">";
            //previous button
            if ($page > 1)
                    $pagination.= "<li><a href=\"$targetpage&page=$prev\"  > « Previous </a></li>";
            else
                    $pagination.= "<li><a href=\"#\"  > « Previous </a></li>";

            //pages
            if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
            {
                    for ($counter = 1; $counter <= $lastpage; $counter++)
                    {
                            if ($counter == $page)
                                    $pagination.= "<li><a href=\"#\" class=\"current\"  > $counter </a></li>";
                            else
                                    $pagination.= "<li><a href=\"$targetpage&page=$counter\" >$counter</a></li>";
                    }
            }
            elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
            {
                    //close to beginning; only hide later pages
                    if($page < 1 + ($adjacents * 2))
                    {
                            for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                            {
                                    if ($counter == $page)
                                            $pagination.= "<li><a href=\"#\" class=\"current\"  > $counter </a></li>";
                                    else
                                            $pagination.= "<li><a href=\"$targetpage&page=$counter\" >$counter</a></li>";
                            }
                            $pagination.= "...";
                            $pagination.= "<li><a href=\"$targetpage&page=$lpm1\" >$lpm1</a></li>";
                            $pagination.= "<li><a href=\"$targetpage&page=$lastpage\" >$lastpage</a></li>";
                    }
                    //in middle; hide some front and some back
                    elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                    {
                            $pagination.= "<li><a href=\"$targetpage&page=1\" >1</a></li>";
                            $pagination.= "<li><a href=\"$targetpage&page=2\" >2</a></li>";
                            $pagination.= "...";
                            for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                            {
                                    if ($counter == $page)
                                            $pagination.= "<li><a href=\"#\" class=\"current\"  > $counter </a></li>";
                                    else
                                            $pagination.= "<li><a href=\"$targetpage&page=$counter\" >$counter</a></li>";
                            }
                            $pagination.= "...";
                            $pagination.= "<li><a href=\"$targetpage&page=$lpm1\" >$lpm1</a></li>";
                            $pagination.= "<li><a href=\"$targetpage&page=$lastpage\" >$lastpage</a></li>";
                    }
                    //close to end; only hide early pages
                    else
                    {
                            $pagination.= "<li><a href=\"$targetpage&page=1\" >1</a></li>";
                            $pagination.= "<li><a href=\"$targetpage&page=2\" >2</a></li>";
                            $pagination.= "...";
                            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                            {
                                    if ($counter == $page)
                                            $pagination.= "<li><a href=\"#\" class=\"current\"  > $counter </a></li>";
                                    else
                                            $pagination.= "<li><a href=\"$targetpage&page=$counter\" >$counter</a></li>";
                            }
                    }
            }

            if ($page < $counter - 1)
                    $pagination.= "<li><a href=\"$targetpage&page=$next\" > Next » </a></li>";
            else
                    $pagination.= "<li><a href=\"#\" class=\"current\"  > Next » </a></li>";
            $pagination.= "</ul>";
        }

        return $pagination;
    }
    

}

?>