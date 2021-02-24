<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

use Config\Services;

class Auth implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {

        try {
            $session = Services::session();
            if ((!isset($session) || isset($session)) && ($session->logged_in != true)) {
                $session->setFlashdata('message', 'The area are you trying to access requires');
                // return redirect()->to('login');
                return redirect()->to('/login');
            }
        } catch (\Exception $e) {
            $data = [
                'status' => ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Server Error'
            ];
            return Services::response()->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Ocurrio un problema en el servidor')
                ->setJSON($data);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        # code...
    }
}
