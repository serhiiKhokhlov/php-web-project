<?php

namespace Application;

use Application\Interfaces\PublicationRepository;
use Application\Services\AuthenticationService;

class NewPublicationCommand
{
    public function __construct(
        private PublicationRepository $publicationRepository,
        private AuthenticationService $authenticationService
    ) {}

    public function execute($title, $content) {
        $this->publicationRepository->addPublication(
          $title,
          $content,
          $this->authenticationService->getUserId()
        );
    }
}