<?php

namespace App\Controllers\API;

use CodeIgniter\Config\Services;
use CodeIgniter\RESTful\ResourceController;

class User extends ResourceController
{
    protected $modelName = 'App\Models\API\Usuario';
    protected $format = 'json';

    public function __construct()
    {
        helper('secure');
    }


    //  DATOS DEL USUARIO
    public function index()
    {
        $id = $_SESSION['user_id'];
        if (!isset($id)) return $this->failServerError('Server error');

        $result = $this->model->profile($id);

        $response = [
            "status" => 200,
            "message" => 'Take your data!',
            "data" => $result
        ];

        return $this->respond($response);
    }

    //  REGISTRO DEL USUARIO
    public function signup()
    {
        try {

            //Reglas de validación
            //del todo el request completo
            //con la función withRequest()
            $rules  = [
                'name'    => 'required|min_length[10]',
                'username'          => 'required|min_length[4]',
                'pass_confirm' => 'required|matches[password]',
                'password'          => 'required|min_length[4]',
                // 'email'        => 'required|valid_email'
            ];

            $validation =  Services::validation();

            $validation->setRules($rules);

            if ($validation->withRequest($this->request)->run()) {

                $exist = $this->model->where('username', $this->request->getPost('username'))->first();

                if ($exist !== null)
                    return $this->failNotFound('Username exist!');

                //Datos a Insertar
                //y Validar en la reglas de validación del
                // Modelo
                $data = [
                    'usuario_nombre' => $this->request->getPost('name'),
                    'username' => $this->request->getPost('username'),
                    'password' => hashPass($this->request->getPost('password')),
                ];

                if ($this->model->insert($data)) {

                    // $lastid = $this->model->insertID;
                    // $this->model->pivot($lastid, 1);

                    return $this->respondCreated([
                        'time' => date(DATE_RFC2822),
                        'message' => 'Created Success!',
                        'data' => [
                            'user' => $this->request->getPost('username'),
                            'pass' => $this->request->getPost('password')
                        ]
                    ]);
                } else {
                    return $this->respond([
                        "status" => 404,
                        "message" => "errors",
                        "data" => $this->model->validation->getErrors()
                    ]);
                }
            } else {
                return $this->respond([
                    'errors' => $validation->getErrors()
                ]);
            }
        } catch (\Exception $e) {
            $this->failServerError('Ha ocurrido un problema en el servidor');
        }
    }

    //  BORRAR USUARIO
    public function destroy()
    {
        $id = $_SESSION['user_id'];
        $result = $this->model->deleteAccount($id);

        $session = Services::session();
        $session->destroy();

        return $this->respond([
            'message' => 'deleted'
        ]);
    }

    //  INGRESO DEL USUARIO
    public function login()
    {
        try {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $result = $this->model->where('username', $username)->first();

            if ($result == null)
                return $this->failNotFound('User no exist!');

            if (verifyPass($password, $result['password'])) {
                $data = [
                    'user_id' => $result['id'],
                    'user_logged_in' => true
                ];

                $session = session();
                $session->set($data);

                return $this->respond([
                    'time' => date(DATE_RFC2822),
                    'message' => $result['usuario_nombre'] . ' Logged in'
                ], 200);
            } else {
                return $this->failValidationError(('contraseña invalida'));
            }

            return $this->respond('Usuario Encontrado!');
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un problema en el servidor');
        }
    }

    //  SALIDA DEL USUARIO

    public function logout()
    {
        $session = Services::session();
        $session->destroy();

        return $this->respond([
            'message' => 'logged out'
        ]);
    }


    //----------------------------------------------


    public function create_co()
    {
        try {
            $id = $_SESSION['user_id'];

            $data = [
                'empresa_nombre' => $this->request->getPost('name'),
                'correo' => $this->request->getPost('email'),
                'empresa_codigo' => "EM" . rand(0, 9) . $this->request->getPost('name'),
                'telefono' => $this->request->getPost('phone'),
            ];
            $model = new Empresa();
            $exist = $model->where('empresa_nombre', $this->request->getPost('name'))->first();

            if ($exist !== null)
                return $this->failNotFound('Company exist!');

            $result = $this->model->companies($id);
            if (is_array($result) && count($result) == 1)
                return $this->failNotFound('Upgrade your account for more companies!');

            if ($model->insert($data)) {
                $lastid = $model->insertID;

                $this->model->pivot($lastid, $id);

                return $this->respondCreated([
                    'time' => date(DATE_RFC2822),
                    'message' => 'Created Success!',
                    'data' => [
                        'company' => $this->request->getPost('name'),
                        'email' => $this->request->getPost('email')
                    ]
                ]);
            } else {
                return $this->failValidationError($this->model->validation->listErrors('clear'));
            }
        } catch (\Exception $e) {
            $this->failServerError('Ha ocurrido un problema en el servidor');
        }
    }


    public function get_co()
    {
        $id = $_SESSION['user_id'];
        if (!isset($id)) return $this->failServerError('Server error');

        $result = $this->model->companies($id);

        if (is_array($result) && count($result) <= 0)
            return $this->failNotFound('No one!');

        $response = [
            "status" => 200,
            "message" => 'Take your data!',
            "data" => $result
        ];

        return $this->respond($response);
    }

    function gethome()
    {
        return $this->respond([
            "status" => 200,
            "messsage" => "You need signup first to use the app!",
            "data" => [
                "endpoints" => [
                    "auth" => [
                        "api/auth/login" =>
                        [
                            "verb" => "POST",
                            "type" => "x-www-form-urlencoded",
                            "fields" => ["username", "password"]
                        ],
                        "api/auth/signup" =>
                        [
                            "verb" => "POST",
                            "type" => "x-www-form-urlencoded",
                            "fields" => ["name", "username", "password"]
                        ],
                    ],
                    "user" => [
                        "api/user" =>
                        [
                            "verb" => "GET",
                        ],
                        "api/user/logout" =>
                        [
                            "verb" => "GET",
                        ],
                        "api/user/companies" =>
                        [
                            "verb" => "POST",
                            "type" => "x-www-form-urlencoded",
                            "fields" => ["name", "phone", "email"]
                        ],
                    ],
                    "employee" => [
                        "api/company/login" =>
                        [
                            "verb" => "POST",
                            "type" => "x-www-form-urlencoded",
                            "fields" => ["username", "password"]
                        ],
                        "api/company/signup" =>
                        [
                            "verb" => "POST",
                            "type" => "x-www-form-urlencoded",
                            "fields" => ["name", "phone", "email", "code", "username", "password"]
                        ],
                        "api/company/user" =>
                        [
                            "verb" => "GET"
                        ],
                        "api/company/user/logout" =>
                        [
                            "verb" => "GET"
                        ],
                    ],
                    "company" => [
                        "api/company/client" =>
                        [
                            "verb" => "POST",
                            "type" => "x-www-form-urlencoded",
                            "fields" => ["name", "phone", "email"]
                        ],
                    ]
                ]
            ]
        ], 200, 'That\'s right');
    }

    public function test()
    {
        echo "Hola";
    }
}
