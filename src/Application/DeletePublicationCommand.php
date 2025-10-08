<?php

namespace Application;

use Application\Interfaces\PublicationRepository;

class DeletePublicationCommand
{
    public function __construct(
        private PublicationRepository $publicationRepository,
    ) {}

    public function execute(int $publicationId) {
        $this->publicationRepository->deletePublication($publicationId);
    }
}