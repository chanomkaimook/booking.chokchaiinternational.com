<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/API_Controller.php';


class Auth extends API_Controller
{
    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        // $this->load->model(['login_model', 'staff_model']);
    }



    public function index()
    {
        header("Access-Control-Allow-Origin: *");
        // API Configuration
        $this->_apiConfig([
            'methods' => ['GET'], // Request Execute Only POST and GET Method
        ]);

        // Data
        $data = array(
            'status' => true,
            'data' => [
                'message' => 'Hello World',
            ]
        );
        $this->api_return($data, 200);
    }
    public function login()
    {
        header("Access-Control-Allow-Origin: *");

        $this->_apiConfig([
            'methods' => ['POST'],
        ]);
        $login =  $this->login_model->checkLogin();
        if ($login) {
            $payload = [
                'id' => $login['id'],
                'name' => $login['name'],
                'code' => $login['code'],
            ];

            $this->load->library('authorization');
            $token = $this->authorization->generateToken($payload);

            $this->api_return(
                [
                    'status' => true,
                    "result" => [
                        'token' => $token,
                        'user' => array_change_key_case($login, CASE_LOWER),
                    ],

                ],
                200
            );
        } else {
            $this->api_return(
                [
                    'status' => false,
                    "message" => 'ชื่อหรือรหัสผ่านไม่ถูกต้อง'
                ],
                401
            );
        }
    }
}
?>
