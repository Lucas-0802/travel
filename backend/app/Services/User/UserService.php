<?php

namespace App\Services\User;

use App\Repositories\User\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data): object
    {   
        $emailExists = $this->userRepository->findByEmail($data['email']);  

        if (!empty($emailExists)) {
            return (object) [
                'message' => 'this_email_is_already_registered',
                'status' => 409
            ];
        }

        $data['password'] = bcrypt($data['password']);
        $this->userRepository->register($data);
        return (object) [
            'message' => 'user_registered_successfully',
            'status' => 201
        ];
    }
}
