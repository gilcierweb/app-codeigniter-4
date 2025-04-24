<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\Users as UserModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

error_reporting(-1);
ini_set('display_errors', 1);

class Users extends BaseController
{
    use ResponseTrait;

    protected $userModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->userModel = new UserModel();

    }

    public function index()
    {
        $users = $this->userModel->findAll();

        return $this->respond($users);
    }

    public function show($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->failNotFound('User not found');
        }

        return $this->respond($user);
    }

    public function create()
    {
        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|max_length[255]|is_unique[users.username]',
            'email' => 'required|valid_email|max_length[255]|is_unique[users.email]',
            'password' => 'required|min_length[6]|max_length[255]',
        ];

        $json = $this->request->getJSON(true);

        if (!$this->validateData($json ?? [], $rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $userData = [
            'username' => $json['username'],
            'email' => $json['email'],
            'password' => password_hash($json['password'], PASSWORD_BCRYPT),
        ];
        $userId = $this->userModel->insert($userData);

        if ($userId) {

            $response = [
                'status' => 201,
                'error' => null,
                'messages' => [
                    'success' => 'User created successfully.'
                ],
                'data' => $this->userModel->find($userId)
            ];
            return $this->respondCreated($response);
        } else {
            return $this->failValidationErrors($this->userModel->errors());
        }

    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->failNotFound('User not found.');
        }

        $rules = [
            'username' => "required|alpha_numeric_space|min_length[3]|max_length[255]|is_unique[users.username,id,{$id}]",
            'email'    => "required|valid_email|max_length[255]|is_unique[users.email,id,{$id}]",
            'password' => 'permit_empty|min_length[6]|max_length[255]',
        ];

        $json = $this->request->getJSON(true);

        if (!$this->validateData($json ?? [], $rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $userData = [
            'username' => $json['username'] ?? $user['username'],
            'email'    => $json['email'] ?? $user['email'],
        ];
        if (!empty($json['password'])) {
            $userData['password'] = password_hash($json['password'], PASSWORD_BCRYPT);
        }
        $this->userModel->update($id, $userData);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'User updated successfully.'
            ],
            'data' => $this->userModel->find($id)
        ];
        return $this->respond($response);
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->failNotFound('User not found.');
        }

        $this->userModel->delete($id);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'User deleted successfully.'
            ]
        ];
        return $this->respond($response);
    }
}
