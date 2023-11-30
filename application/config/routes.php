<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login/ctl_login';
$route['404_override'] = 'error_404';
$route['translate_uri_dashes'] = FALSE;



$route['api/v1/test']['get']                                = 'api/auth/index';

// $route['api/v1/bill']['get']                                = 'api/bill/list';
// $route['api/v1/bill/search']['get']                         = 'api/bill/search';
// $route['api/v1/bill/mylabs']['get']                         = 'api/bill/mylabs';
// $route['api/v1/bill/list']['get']                           = 'api/bill/list';
// $route['api/v1/bill/(:num)']['get']                         = 'api/bill/view/$1';
// $route['api/v1/bill/(:num)/labs']['get']                    = 'api/bill/labs/$1';
// $route['api/v1/bill/code/(:any)']['get']                    = 'api/bill/code/$1';
// $route['api/v1/bill/(:num)/items']['get']                   = 'api/bill/items/$1';
// $route['api/v1/bill/list_labs']['get']                      = 'api/bill/list_labs';
// $route['api/v1/bill/progress_status']['get']                = 'api/bill/progress_status';
// $route['api/v1/bill/(:num)/progress_status']['post']        = 'api/bill/set_progress_status/$1';
// $route['api/v1/bill/(:num)/progress_complete']['post']      = 'api/bill/set_progress_complete/$1';
// $route['api/v1/bill/item/tracking-status']['get']           = 'api/bill/tracking_status';
// $route['api/v1/bill/item/(:num)/tracking-status']['post']   = 'api/bill/set_tracking_status/$1';
// $route['api/v1/bill/item/(:num)/tracking-complete']['post'] = 'api/bill/set_tracking_complete/$1';

// #ดูข้อมูลติดตามสถานะ traking ใบขอรับบริการ จาก ID
// $route['api/v1/bill/tracking/(:any)']['get'] = 'api/bill/tracking/$1';