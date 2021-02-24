<?php

namespace App\Controllers;

use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Home extends BaseController
{
	public function index()
	{
		echo view('pages/templates/header');
		echo view('pages/templates/navbar');
		echo view('pages/templates/sidebar');
		echo view('pages/src/index');
		echo view('pages/templates/main-footer');
		echo view('pages/templates/footer');
	}

	public function login()
	{
		echo view('pages/src/gen/login');
	}

	public function register()
	{
		echo view('pages/src/gen/register');
	}

	public function forgot()
	{
		echo view('pages/src/gen/forgot-password');
	}

	public function recover()
	{
		echo view('pages/src/gen/recover-password');
	}

	public function error404()
	{
		// $session = Services::session();
		// if ($session->islogged_in) {
		// 	echo view('pages/src/errors/404');
		// } else {
		// 	return redirect('login');
		// 	// return view('pages/src/gen/login');
		// }
		// echo view('pages/src/errors/404');
		return redirect()->back();
	}

	//--------------------------------------------------------------------

}
