<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\Profiles as ProfileModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Profiles extends BaseController
{
    use ResponseTrait;
    protected $profileModel;
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->profileModel = new ProfileModel();
    }

    public function index()
    {
        $profiles = $this->profileModel->findAll();
        return $this->respond($profiles);
    }
    public function show($id = null)
    {
        $profile = $this->profileModel->find($id);
        if (!$profile) {
            return $this->failNotFound('Profile not found.');
        }
        return $this->respond($profile);
    }

    public function create()
    {
        $rules = [
            'user_id'    => 'required|is_unique[profiles.user_id]',
            'first_name' => 'permit_empty|alpha_space|max_length[255]',
            'last_name'  => 'permit_empty|alpha_space|max_length[255]',
            'website'    => 'permit_empty|valid_url|max_length[255]',
            'instagram'  => 'permit_empty|alpha_numeric_punct|max_length[255]',
            'facebook'   => 'permit_empty|valid_url|max_length[255]',
            'linkedin'   => 'permit_empty|valid_url|max_length[255]',
            'twitter_x'  => 'permit_empty|alpha_numeric_punct|max_length[255]',
            'avatar'     => 'permit_empty|max_length[255]',
            'bio'        => 'permit_empty',
        ];

        $json = $this->request->getJSON(true);

        if (!$this->validateData($json ?? [], $rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $profileData = [
            'user_id'    => $json['user_id'],
            'first_name' => $json['first_name'] ?? null,
            'last_name'  => $json['last_name'] ?? null,
            'website'    => $json['website'] ?? null,
            'instagram'  => $json['instagram'] ?? null,
            'facebook'   => $json['facebook'] ?? null,
            'linkedin'   => $json['linkedin'] ?? null,
            'twitter_x'  => $json['twitter_x'] ?? null,
            'avatar'     => $json['avatar'] ?? null,
            'bio'        => $json['bio'] ?? null,
        ];

        $profileId = $this->profileModel->insert($profileData);

        if ($profileId) {
            $response = [
                'status'   => 201,
                'error'    => null,
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
    public function update($id = null)
    {
        $profile = $this->profileModel->find($id);
        if (!$profile) {
            return $this->failNotFound('Profile not found.');
        }

        $rules = [
            'user_id'    => "required|is_unique[profiles.user_id,id,{$id}]",
            'first_name' => 'permit_empty|alpha_space|max_length[255]',
            'last_name'  => 'permit_empty|alpha_space|max_length[255]',
            'website'    => 'permit_empty|valid_url|max_length[255]',
            'instagram'  => 'permit_empty|alpha_numeric_punct|max_length[255]',
            'facebook'   => 'permit_empty|valid_url|max_length[255]',
            'linkedin'   => 'permit_empty|valid_url|max_length[255]',
            'twitter_x'  => 'permit_empty|alpha_numeric_punct|max_length[255]',
            'avatar'     => 'permit_empty|max_length[255]',
            'bio'        => 'permit_empty',
        ];

        $json = $this->request->getJSON(true);

        if (!$this->validateData($json ?? [], $rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $profileData = [
            'user_id'    => $json['user_id'] ?? $profile['user_id'],
            'first_name' => $json['first_name'] ?? $profile['first_name'],
            'last_name'  => $json['last_name'] ?? $profile['last_name'],
            'website'    => $json['website'] ?? $profile['website'],
            'instagram'  => $json['instagram'] ?? $profile['instagram'],
            'facebook'   => $json['facebook'] ?? $profile['facebook'],
            'linkedin'   => $json['linkedin'] ?? $profile['linkedin'],
            'twitter_x'  => $json['twitter_x'] ?? $profile['twitter_x'],
            'avatar'     => $json['avatar'] ?? $profile['avatar'],
            'bio'        => $json['bio'] ?? $profile['bio'],
        ];

        $this->profileModel->update($id, $profileData);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Profile updated successfully.'
            ],
            'data' => $this->profileModel->find($id)
        ];
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $profile = $this->profileModel->find($id);
        if (!$profile) {
            return $this->failNotFound('Profile not found.');
        }

        $this->profileModel->delete($id);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Profile deleted successfully.'
            ]
        ];
        return $this->respond($response);
    }

}
