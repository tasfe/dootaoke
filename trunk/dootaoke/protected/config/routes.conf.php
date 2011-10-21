<?php
/**
 * Define your URI routes here.
 *
 * $route[Request Method][Uri] = array( Controller class, action method, other options, etc. )
 *
 * RESTful api support, *=any request method, GET PUT POST DELETE
 * POST 	Create
 * GET      Read
 * PUT      Update, Create
 * DELETE 	Delete
 *
 * Use lowercase for Request Method
 *
 * If you have your controller file name different from its class name, eg. home.php HomeController
 * $route['*']['/'] = array('HomeController', 'index', 'className'=>'HomeController');
 * 
 * If you need to reverse generate URL based on route ID with DooUrlBuilder in template view, please defined the id along with the routes
 * $route['*']['/'] = array('HomeController', 'index', 'id'=>'home');
 */
$admin = array('admin'=>'1234');

$route['*']['/'] = array('MallController', 'index');
$route['*']['/to/:type/:id'] = array('MainController', 'redirect_to');
$route['*']['/ajax/remote'] = array('MainController', 'ajax_remote');
$route['*']['/cache/clear'] = array('MainController', 'clear_cache');

// 商品列表
$route['*']['/list/:p'] = array('MallItemController', 'list_items');
$route['*']['/list/:p/:page'] = array('MallItemController', 'list_items');
$route['*']['/list'] = array('MallItemController', 'list_items');

// 商品
$route['*']['/item/:id'] = array('MallItemController', 'item_detail');

// 产品列表
$route['*']['/products/:p'] = array('MallProductController', 'list_products');
$route['*']['/products/:p/:page'] = array('MallProductController', 'list_products');
$route['*']['/products'] = array('MallProductController', 'products_index');
$route['*']['/products/search'] = array('MallProductController', 'search_products');

// 产品页
$route['*']['/product/:id'] = array('MallProductController', 'product_detail');
$route['*']['/product/:id/:page'] = array('MallProductController', 'product_detail');

// 店铺
$route['*']['/shop/:p'] = array('MallShopController', 'shop_detail');
$route['*']['/shop/:p/:page'] = array('MallShopController', 'shop_detail');
$route['*']['/shop'] = array('MallShopController', 'shop_index');

//----------------------blog-------------------------------------
$route['*']['/blog'] = array('BlogController', 'home');
$route['*']['/blog/page/:pindex'] = array('BlogController', 'page');

$route['*']['/blog/article/:postId'] = array('BlogController', 'getArticle');

$route['*']['/blog/archive/:year/:month'] = array('BlogController', 'getArchive');
$route['*']['/blog/archive/:year/:month/page/:pindex'] = array('BlogController', 'getArchive');

$route['*']['/blog/tag/:name'] = array('BlogController', 'getTag');
$route['*']['/blog/tag/:name/page/:pindex'] = array('BlogController', 'getTag');

$route['*']['/blog/comment/submit'] = array('BlogController', 'newComment');


//admin home page
$route['*']['/blog/admin'] =
$route['*']['/blog/admin/post'] = array('BlogAdminController', 'home',
                              'authName'=>'Blog Admin', 'auth'=>array('admin'=>'1234'), 'authFailURL'=>'./error/loginFail');

//admin list posts pagination
$route['*']['/blog/admin/page/:pindex'] =
$route['*']['/blog/admin/post/page/:pindex'] = array('BlogAdminController', 'page',
                                                'authName'=>'Blog Admin', 'auth'=>array('admin'=>'1234'), 'authFailURL'=>'./error/loginFail');

//admin list posts Sorting (asc/desc) and pagination
$route['*']['/blog/admin/sort/:sortField/:orderType'] =
$route['*']['/blog/admin/post/sort/:sortField/:orderType'] =
$route['*']['/blog/admin/post/sort/:sortField/:orderType/page/:pindex'] = array('BlogAdminController', 'sortBy',
                                                              'authName'=>'Blog Admin', 'auth'=>array('admin'=>'1234'), 'authFailURL'=>'./error/loginFail');

//admin edit Post
$route['*']['/blog/admin/post/edit/:pid'] = array('BlogAdminController', 'getArticle',
                              'authName'=>'Blog Admin', 'auth'=>array('admin'=>'1234'), 'authFailURL'=>'./error/loginFail');

$route['post']['/blog/admin/post/save'] = array('BlogAdminController', 'savePostChanges',
                              'authName'=>'Blog Admin', 'auth'=>array('admin'=>'1234'), 'authFailURL'=>'./error/loginFail');


//admin create Post
$route['*']['/blog/admin/post/create'] = array('BlogAdminController', 'createPost',
                              'authName'=>'Blog Admin', 'auth'=>array('admin'=>'1234'), 'authFailURL'=>'./error/loginFail');

$route['post']['/blog/admin/post/saveNew'] = array('BlogAdminController', 'saveNewPost',
                              'authName'=>'Blog Admin', 'auth'=>array('admin'=>'1234'), 'authFailURL'=>'./error/loginFail');

//admin delete Post
$route['*']['/blog/admin/post/delete/:pid'] = array('BlogAdminController', 'deletePost',
                              'authName'=>'Blog Admin', 'auth'=>array('admin'=>'1234'), 'authFailURL'=>'./error/loginFail');

//admin list unapproved comments
$route['*']['/blog/admin/comment'] = array('BlogAdminController', 'listComment',
                              'authName'=>'Blog Admin', 'auth'=>array('admin'=>'1234'), 'authFailURL'=>'./error/loginFail');

$route['*']['/blog/admin/comment/approve/:cid'] = array('BlogAdminController', 'approveComment',
                              'authName'=>'Blog Admin', 'auth'=>array('admin'=>'1234'), 'authFailURL'=>'./error/loginFail');

$route['*']['/blog/admin/comment/reject/:cid'] = array('BlogAdminController', 'rejectComment',
                              'authName'=>'Blog Admin', 'auth'=>array('admin'=>'1234'), 'authFailURL'=>'./error/loginFail');
//----------------------blog--------------------------------

$route['*']['/error'] = array('ErrorController', 'index');
//error displays
$route['*']['/blog/error'] = array('ErrorController', 'defaultError');
$route['*']['/blog/error/loginFail'] = array('ErrorController', 'loginError');
$route['*']['/blog/error/postNotFound/:pid'] = array('ErrorController', 'postError');
?>