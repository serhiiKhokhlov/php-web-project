<?php

namespace Application;

use Application\Interfaces\PublicationRepository;

class PublicationQuery
{
    public function __construct(
        private PublicationRepository $publicationRepository,
    ){}

    public function execute(int $userId) : ?array
    {
        return $this->publicationRepository->getPublicationsForUser($userId);
    }
}