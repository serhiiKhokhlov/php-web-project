<?php

namespace Application;

use Application\Interfaces\PublicationRepository;

class LikesQuery
{
    public function __construct(
        private PublicationRepository $publicationRepository,
    ){
    }

    public function execute(int $publicationId) : ?array {
        return $this->publicationRepository->getLikedForPublication($publicationId);
    }

}