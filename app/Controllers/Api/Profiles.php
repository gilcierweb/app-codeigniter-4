<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\Profiles as ProfileModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * @OA\Tag(
 * name="Profiles",
 * description="Operations related to user profiles"
 * )
 */
class Profiles extends BaseController
{
    use ResponseTrait;

    protected $profileModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->profileModel = new ProfileModel();
    }

    /**
     * @OA\Get(
     * path="/profiles",
     * summary="Get a list of all user profiles",
     * tags={"Profiles"},
     * @OA\Response(
     * response=200,
     * description="A list of user profiles",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/Profile")
     * )
     * )
     * )
     */
    public function index()
    {
        $profiles = $this->profileModel->findAll();
        return $this->respond($profiles);
    }

    /**
     * @OA\Get(
     * path="/profiles/{id}",
     * summary="Get a specific user profile by ID",
     * tags={"Profiles"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the profile to retrieve",
     * @OA\Schema(
     * type="integer",
     * format="int64"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Details of the requested profile",
     * @OA\JsonContent(ref="#/components/schemas/Profile")
     * ),
     * @OA\Response(
     * response=404,
     * description="Profile not found"
     * )
     * )
     */
    public function show($id = null)
    {
        $profile = $this->profileModel->find($id);
        if (!$profile) {
            return $this->failNotFound('Profile not found.');
        }
        return $this->respond($profile);
    }

    /**
     * @OA\Post(
     * path="/profiles",
     * summary="Create a new user profile",
     * tags={"Profiles"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/NewProfileRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Profile created successfully",
     * @OA\JsonContent(ref="#/components/schemas/Profile")
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
            'user_id' => 'required|is_unique[profiles.user_id]',
            'first_name' => 'permit_empty|alpha_space|max_length[255]',
            'last_name' => 'permit_empty|alpha_space|max_length[255]',
            'website' => 'permit_empty|valid_url|max_length[255]',
            'instagram' => 'permit_empty|alpha_numeric_punct|max_length[255]',
            'facebook' => 'permit_empty|valid_url|max_length[255]',
            'linkedin' => 'permit_empty|valid_url|max_length[255]',
            'twitter_x' => 'permit_empty|alpha_numeric_punct|max_length[255]',
            'avatar' => 'permit_empty|max_length[255]',
            'bio' => 'permit_empty',
        ];

        $json = $this->request->getJSON(true);

        if (!$this->validateData($json ?? [], $rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $profileData = [
            'user_id' => $json['user_id'],
            'first_name' => $json['first_name'] ?? null,
            'last_name' => $json['last_name'] ?? null,
            'website' => $json['website'] ?? null,
            'instagram' => $json['instagram'] ?? null,
            'facebook' => $json['facebook'] ?? null,
            'linkedin' => $json['linkedin'] ?? null,
            'twitter_x' => $json['twitter_x'] ?? null,
            'avatar' => $json['avatar'] ?? null,
            'bio' => $json['bio'] ?? null,
        ];

        $profileId = $this->profileModel->insert($profileData);

        if ($profileId) {
            $response = [
                'status' => 201,
                'error' => null,
                'messages' => [
                    'success' => 'Profile created successfully.'
                ],
                'data' => $this->profileModel->find($profileId)
            ];
            return $this->respondCreated($response);
        } else {
            return $this->failServerError('Failed to create profile.');
        }
    }

    /**
     * @OA\Put(
     * path="/profiles/{id}",
     * summary="Update an existing user profile",
     * tags={"Profiles"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the profile to update",
     * @OA\Schema(
     * type="integer",
     * format="int64"
     * )
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/UpdateProfileRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Profile updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/Profile")
     * ),
     * @OA\Response(
     * response=400,
     * description="Validation errors",
     * @OA\JsonContent(ref="#/components/schemas/ValidationError")
     * ),
     * @OA\Response(
     * response=404,
     * description="Profile not found"
     * ),
     * security={
     * {"bearerAuth": {}}
     * }
     * )
     */
    public function update($id = null)
    {
        $profile = $this->profileModel->find($id);
        if (!$profile) {
            return $this->failNotFound('Profile not found.');
        }

        $rules = [
            'user_id' => "required|is_unique[profiles.user_id,id,{$id}]",
            'first_name' => 'permit_empty|alpha_space|max_length[255]',
            'last_name' => 'permit_empty|alpha_space|max_length[255]',
            'website' => 'permit_empty|valid_url|max_length[255]',
            'instagram' => 'permit_empty|alpha_numeric_punct|max_length[255]',
            'facebook' => 'permit_empty|valid_url|max_length[255]',
            'linkedin' => 'permit_empty|valid_url|max_length[255]',
            'twitter_x' => 'permit_empty|alpha_numeric_punct|max_length[255]',
            'avatar' => 'permit_empty|max_length[255]',
            'bio' => 'permit_empty',
        ];

        $json = $this->request->getJSON(true);

        if (!$this->validateData($json ?? [], $rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $profileData = [
            'user_id' => $json['user_id'] ?? $profile['user_id'],
            'first_name' => $json['first_name'] ?? $profile['first_name'],
            'last_name' => $json['last_name'] ?? $profile['last_name'],
            'website' => $json['website'] ?? $profile['website'],
            'instagram' => $json['instagram'] ?? $profile['instagram'],
            'facebook' => $json['facebook'] ?? $profile['facebook'],
            'linkedin' => $json['linkedin'] ?? $profile['linkedin'],
            'twitter_x' => $json['twitter_x'] ?? $profile['twitter_x'],
            'avatar' => $json['avatar'] ?? $profile['avatar'],
            'bio' => $json['bio'] ?? $profile['bio'],
        ];

        $this->profileModel->update($id, $profileData);

        $response = [
            'status' => 200,
            'error' => null,
            'messages' => [
                'success' => 'Profile updated successfully.'
            ],
            'data' => $this->profileModel->find($id)
        ];
        return $this->respond($response);
    }

    /**
     * @OA\Delete(
     * path="/profiles/{id}",
     * summary="Delete a user profile",
     * tags={"Profiles"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the profile to delete",
     * @OA\Schema(
     * type="integer",
     * format="int64"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Profile deleted successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="integer", example=200),
     * @OA\Property(property="error", type="null", example=null),
     * @OA\Property(
     * property="messages",
     * type="object",
     * @OA\Property(property="success", type="string", example="Profile deleted successfully.")
     * )
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Profile not found"
     * ),
     * security={
     * {"bearerAuth": {}}
     * }
     * )
     */
    public function delete($id = null)
    {
        $profile = $this->profileModel->find($id);
        if (!$profile) {
            return $this->failNotFound('Profile not found.');
        }

        $this->profileModel->delete($id);

        $response = [
            'status' => 200,
            'error' => null,
            'messages' => [
                'success' => 'Profile deleted successfully.'
            ]
        ];
        return $this->respond($response);
    }

}
/**
 * @OA\Schema(
 * schema="NewProfileRequest",
 * type="object",
 * required={"user_id"},
 * @OA\Property(property="user_id", type="integer", format="int64", example=2),
 * @OA\Property(property="first_name", type="string", nullable=true, example="Jane"),
 * @OA\Property(property="last_name", type="string", nullable=true, example="Doe"),
 * @OA\Property(property="website", type="string", nullable=true, example="https://jane.com"),
 * @OA\Property(property="instagram", type="string", nullable=true, example="janedoe_ig"),
 * @OA\Property(property="facebook", type="string", nullable=true, example="https://facebook.com/janedoe"),
 * @OA\Property(property="linkedin", type="string", nullable=true, example="https://linkedin.com/in/janedoe"),
 * @OA\Property(property="twitter_x", type="string", nullable=true, example="@janedoe_tw"),
 * @OA\Property(property="avatar", type="string", nullable=true, example="jane_profile.jpg"),
 * @OA\Property(property="bio", type="string", nullable=true, example="A brief bio about Jane Doe.")
 * )
 *
 * @OA\Schema(
 * schema="UpdateProfileRequest",
 * type="object",
 * @OA\Property(property="user_id", type="integer", format="int64", example=2),
 * @OA\Property(property="first_name", type="string", nullable=true, example="Updated Jane"),
 * @OA\Property(property="last_name", type="string", nullable=true, example="New Doe"),
 * @OA\Property(property="website", type="string", nullable=true, example="https://updatedjane.com"),
 * @OA\Property(property="instagram", type="string", nullable=true, example="updated_jane_ig"),
 * @OA\Property(property="facebook", type="string", nullable=true, example="https://facebook.com/updatedjane"),
 * @OA\Property(property="linkedin", type="string", nullable=true, example="https://linkedin.com/in/updatedjane"),
 * @OA\Property(property="twitter_x", type="string", nullable=true, example="@updated_jane_tw"),
 * @OA\Property(property="avatar", type="string", nullable=true, example="updated_jane_profile.jpg"),
 * @OA\Property(property="bio", type="string", nullable=true, example="An updated bio for Jane Doe.")
 * )
 */