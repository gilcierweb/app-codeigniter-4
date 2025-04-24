<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\Users as UserModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * @OA\Info(
 * title="User Management API",
 * version="1.0.0",
 * description="API for managing users and their profiles",
 * @OA\Contact(
 * email="your.email@example.com"
 * ),
 * @OA\License(
 * name="MIT"
 * )
 * )
 * @OA\Server(
 * url="http://localhost:8080/api",
 * description="Development API Server"
 * )
 *
 * @OA\Tag(
 * name="Users",
 * description="Operations related to users"
 * )
 * @OA\SecurityScheme(
 * securityScheme="bearerAuth",
 * in="header",
 * name="Authorization",
 * type="http",
 * scheme="bearer",
 * bearerFormat="JWT"
 * )
 *
 * @OA\Schema(
 * schema="User",
 * type="object",
 * @OA\Property(property="id", type="integer", format="int64", example=1),
 * @OA\Property(property="username", type="string", example="johndoe"),
 * @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 * @OA\Property(property="created_at", type="string", format="date-time"),
 * @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 * schema="Profile",
 * type="object",
 * @OA\Property(property="id", type="integer", format="int64", example=1),
 * @OA\Property(property="user_id", type="integer", format="int64", example=1),
 * @OA\Property(property="first_name", type="string", nullable=true, example="John"),
 * @OA\Property(property="last_name", type="string", nullable=true, example="Doe"),
 * @OA\Property(property="website", type="string", nullable=true, example="https://example.com"),
 * @OA\Property(property="instagram", type="string", nullable=true, example="johndoe_ig"),
 * @OA\Property(property="facebook", type="string", nullable=true, example="https://facebook.com/johndoe"),
 * @OA\Property(property="linkedin", type="string", nullable=true, example="https://linkedin.com/in/johndoe"),
 * @OA\Property(property="twitter_x", type="string", nullable=true, example="@johndoe_tw"),
 * @OA\Property(property="avatar", type="string", nullable=true, example="profile.jpg"),
 * @OA\Property(property="bio", type="string", nullable=true, example="A short bio about John Doe."),
 * @OA\Property(property="created_at", type="string", format="date-time"),
 * @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 * schema="UserWithProfile",
 * type="object",
 * allOf={
 * @OA\Schema(ref="#/components/schemas/User"),
 * @OA\Schema(
 * type="object",
 * @OA\Property(property="profile", ref="#/components/schemas/Profile")
 * )
 * }
 * )
 *
 * @OA\Schema(
 * schema="NewUserRequest",
 * type="object",
 * required={"username", "email", "password"},
 * @OA\Property(property="username", type="string", example="newuser"),
 * @OA\Property(property="email", type="string", format="email", example="new@example.com"),
 * @OA\Property(property="password", type="string", example="secure123"),
 * @OA\Property(property="first_name", type="string", nullable=true, example="New"),
 * @OA\Property(property="last_name", type="string", nullable=true, example="User")
 * )
 *
 * @OA\Schema(
 * schema="UpdateUserRequest",
 * type="object",
 * @OA\Property(property="username", type="string", example="updateduser"),
 * @OA\Property(property="email", type="string", format="email", example="updated@example.com"),
 * @OA\Property(property="password", type="string", example="newsecure"),
 * @OA\Property(property="first_name", type="string", nullable=true, example="Updated"),
 * @OA\Property(property="last_name", type="string", nullable=true, example="User")
 * )
 *
 * @OA\Schema(
 * schema="ValidationError",
 * type="object",
 * @OA\Property(
 * property="errors",
 * type="object",
 * @OA\Property(property="username", type="array", @OA\Items(type="string", example="The username field is required.")),
 * @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email field must be a valid email address.")),
 * @OA\Property(property="password", type="array", @OA\Items(type="string", example="The password field is required."))
 * )
 * )
 */

class Users extends BaseController
{
    use ResponseTrait;

    protected $userModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->userModel = new UserModel();

    }
    /**
     * @OA\Get(
     * path="/users",
     * summary="Get a list of all users with their profiles",
     * tags={"Users"},
     * @OA\Response(
     * response=200,
     * description="A list of users with their profiles",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/UserWithProfile")
     * )
     * )
     * )
     */
    public function index()
    {
        $users = $this->userModel->findAll();

        return $this->respond($users);
    }
    /**
     * @OA\Get(
     * path="/users/{id}",
     * summary="Get a specific user by ID with their profile",
     * tags={"Users"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the user to retrieve",
     * @OA\Schema(
     * type="integer",
     * format="int64"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Details of the requested user with their profile",
     * @OA\JsonContent(ref="#/components/schemas/UserWithProfile")
     * ),
     * @OA\Response(
     * response=404,
     * description="User not found"
     * )
     * )
     */
    public function show($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->failNotFound('User not found');
        }

        return $this->respond($user);
    }
    /**
     * @OA\Post(
     * path="/users",
     * summary="Create a new user with basic profile information",
     * tags={"Users"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/NewUserRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="User created successfully",
     * @OA\JsonContent(ref="#/components/schemas/UserWithProfile")
     * ),
     * @OA\Response(
     * response=400,
     * description="Validation errors",
     * @OA\JsonContent(ref="#/components/schemas/ValidationError")
     * ),
     * security={
     * {"bearerAuth": {}}
     * }
     * )
     */
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
    /**
     * @OA\Put(
     * path="/users/{id}",
     * summary="Update an existing user with their profile information",
     * tags={"Users"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the user to update",
     * @OA\Schema(
     * type="integer",
     * format="int64"
     * )
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/UpdateUserRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="User updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/UserWithProfile")
     * ),
     * @OA\Response(
     * response=400,
     * description="Validation errors",
     * @OA\JsonContent(ref="#/components/schemas/ValidationError")
     * ),
     * @OA\Response(
     * response=404,
     * description="User not found"
     * ),
     * security={
     * {"bearerAuth": {}}
     * }
     * )
     */
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
    /**
     * @OA\Delete(
     * path="/users/{id}",
     * summary="Delete a user and their associated profile",
     * tags={"Users"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the user to delete",
     * @OA\Schema(
     * type="integer",
     * format="int64"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="User and their profile deleted successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="integer", example=200),
     * @OA\Property(property="error", type="null", example=null),
     * @OA\Property(
     * property="messages",
     * type="object",
     * @OA\Property(property="success", type="string", example="User and profile deleted successfully.")
     * )
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="User not found"
     * ),
     * security={
     * {"bearerAuth": {}}
     * }
     * )
     */
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
