<?php

namespace Application;

use Application\Services\ViewedProfileService;

class OpenProfileCommand
{
    public function __construct(
        private ViewedProfileService $viewedProfileService,
    ){}

    public function execute($userId): void {
        $this->viewedProfileService->closeProfile();
        $this->viewedProfileService->openProfile($userId);
    }
}