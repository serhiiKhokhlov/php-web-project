<?php

namespace Application;

use Application\Interfaces\UserRepository;

class PeopleQuery
{
    public function __construct(
        private UserRepository $userRepository
    ){}

    public function execute(string $promptNickname): ?array
    {
        return $this->userRepository->getUsersForLikeUserName($promptNickname);
    }
}