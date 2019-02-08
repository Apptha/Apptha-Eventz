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

function appthaeventzBuildRoute(&$query) {
    $segments = array();
 

     if (isset($query['view']))
     {
        $segments[] = $query['view'];
        unset($query['view']);
    }
	if (isset($query['tmpl'])) {
        $segments[]="tmpl";
        $segments[] = $query['tmpl'];
        unset($query['tmpl']);
    }
    if (isset($query['page'])) {
        $segments[] = $query['page'];
        unset($query['page']);
    }
    if (isset($query['category_id'])) {
        $segments[] = $query['category_id'];
        unset($query['category_id']);
    }
	if (isset($query['events_id'])) {
        $segments[] = $query['events_id'];
        unset($query['events_id']);
    }
	
    if (isset($query['tag_name'])) {
        $segments[] = "tag_name";
        $segments[] = $query['tag_name'];
        unset($query['tag_name']);
    } 
     if (isset($query['location'])) {
        $segments[] = "location";
        $segments[] = $query['location'];
        unset($query['location']);
    } if (isset($query['task'])) {
        $segments[]="task";
        $segments[] = $query['task'];
        unset($query['task']);
    }
    if (isset($query['event_id'])) {
        $segments[] = $query['event_id'];
        unset($query['event_id']);
    } 
    if (isset($query['location_id'])) {
        $segments[]="location_id";
        $segments[] = $query['location_id'];
        unset($query['location_id']);
    } 
    unset( $query['view'] );
  //print_r($segments);
  
    return $segments;
}

/**
 * @param	array	A named array
 * @param	array
 *
 * Formats:
 *
 * index.php?/banners/task/bid/Itemid
 *
 * index.php?/banners/bid/Itemid
 */
function appthaeventzParseRoute($segments) {


    $vars = array();
    // view is always the first element of the array
    $count = count($segments);
    if ($count) {
        switch ($segments[0]) {
            case 'categorylist':
                $vars['view'] = 'categorylist';
                if (isset($segments[1])  )
				{  
				    $vars['page'] = $segments[1];
                                     unset($segments[1]);
                                }

                break;
               case 'locationmap':
                $vars['view'] = 'locationmap';
                
                   if (isset($segments[1]) && $segments[1]=='location' )
				{
                      $vars['location'] = $segments[2];
                                    $location_name = explode( ':', $segments[2] );
                                    $vars['location'] = $location_name[1];
					
                                    unset($segments[2]);
                                    unset($segments[1]);
                                }
                break;
                  
            case 'eventlist':
                $vars['view'] = 'eventlist';
                             if (isset($segments[1]) && ($segments[1])=='tag_name' )
				{  
                                    $vars['tag_name'] = $segments[2];
					unset($segments[2]);
				}
                             if (isset($segments[1]) && is_numeric($segments[1]) )
				{  
                                    $vars['page'] = $segments[1];
					unset($segments[1]);
				}
                                   if (isset($segments[1]) && ($segments[1])=='location_id' )
				{
                                    $vars['location_id'] = $segments[2];
					unset($segments[1]);
                                        unset($segments[2]);
				}
            			if (isset($segments[1]) )
				{  
				    $vars['category_id'] = $segments[1];
                                    $id = explode( ':', $segments[1] );
                                    $vars['category_id'] = (int) $id[0];
					unset($segments[1]);
				}
                                
				
					
					
                break;
                  case 'search':
                $vars['view'] = 'search';
            			
                break;
             case 'calendar':
                $vars['view'] = 'calendar';

                break;
             case 'events':
                $vars['view'] = 'events';
                 
                if (isset($segments[1]) )
				{  
				    $vars['events_id'] = $segments[1];
                                    $id = explode( ':', $segments[1] );
                                    $vars['events_id'] = (int) $id[0];
					unset($segments[1]);
				}
                break;
                case 'join':
                $vars['view'] = 'join';
                 
              if (isset($segments[1]) && $segments[1]=='task' )
				{  
				    $vars['task'] = $segments[2];
                                    $vars['event_id'] = $segments[3];
                                    $id = explode( ':', $segments[3] );
                                    $vars['event_id'] = (int) $id[0];
		                    unset($segments[3]);
                                    unset($segments[2]);
				}
                               
              if (isset($segments[1]) && $segments[1]=='tmpl' )
				{  
                  
				    $vars['tmpl'] = $segments[2];
                                    $vars['event_id'] = $segments[3];
                                    $id = explode( ':', $segments[3] );
                                    $vars['event_id'] = (int) $id[0];
		                    unset($segments[3]);
                                    unset($segments[2]);
				}
                if (isset($segments[1]) )
				{  
				    $vars['joinAuth'] = $segments[1];
                                    unset($segments[1]);
				}
                break;
                
                case 'invite':
                $vars['view'] = 'invite';
                     if (isset($segments[1]) )
				{
				    $vars['event_id'] = $segments[1];
                                    unset($segments[1]);
                                }
                break;
            case 'gmailinvite':
                $vars['view'] = 'gmailinvite';
            			
                break;
             case 'facebookinvite':
                $vars['view'] = 'facebookinvite';
                  if (isset($segments[1]) )
				{
				    $vars['event_id'] = $segments[1];
                                    unset($segments[1]);
                                }
                break;
            case 'location':
                $vars['view'] = 'location';

                  if (isset($segments[1]) && is_numeric($segments[1]) )
				{  
				    $vars['page'] = $segments[1];
                                    unset($segments[1]);
                                }
                
                break;

        }
    }
	
    return $vars;
}

