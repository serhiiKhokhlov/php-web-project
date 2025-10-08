<?php

namespace Application;

use Application\Services\ViewedProfileService;

class CloseProfileCommand
{
    public function __construct(
        private ViewedProfileService $viewedProfileService
    ){}

    public function execute(){
        $this->viewedProfileService->closeProfile();
    }
}