<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

// // Would execute the show404 method of the App\Errors class
$routes->set404Override('App\Controllers\Home::error404');

// // Will display a custom view
// $routes->set404Override(function()
// {
// 	$session = Services::session();
// 	if($session->loggen_id){
// 		return view('pages/src/errors/404');
// 	}else{
// 		$uri = site_url('login');
// 		return redirect()->to('login'); 
// 		// return view('pages/src/gen/login');
// 	}
// });
$routes->setAutoRoute(false);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index',['filter' => 'auth']);
$routes->get('login', 'Home::login',['as' => 'login']);
$routes->get('register', 'Home::register');
$routes->get('forgot', 'Home::forgot');
$routes->get('recover', 'Home::recover');

$routes->group('api', ['namespace' => 'App\Controllers\API', 'filter' => 'auth'], function ($routes) {
	$routes->post('signup', 'User::signup');
	$routes->get('test', 'User::test');
});

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
