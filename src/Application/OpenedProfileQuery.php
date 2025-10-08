<?php

namespace Application;

use Application\Interfaces\UserRepository;
use Application\Services\ViewedProfileService;

class OpenedProfileQuery
{
    public function __construct(
        private ViewedProfileService $viewedProfileService,
        private UserRepository $userRepository
    ){}

    public function execute(): ?UserData
    {

        $id = $this->viewedProfileService->getViewedProfileId();
        if ($id === null) {
            return null;
        }

        $user = $this->userRepository->getUser($id);
        if ($user === null) {
            return null;
        }

        return new UserData(
          $user->getId(),
          $user->getUsername(),
        );
    }
}