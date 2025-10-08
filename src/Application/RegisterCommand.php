<?php

namespace Application;

use Application\Interfaces\UserRepository;

class RegisterCommand
{
    public function __construct(
        private UserRepository $userRepository
    ) {

    }

    public function execute(string $userName, string $password): bool {
        return $this->userRepository->registerUser($userName, $password);
    }
}