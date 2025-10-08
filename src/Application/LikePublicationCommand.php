<?php

namespace Application;

use Application\Interfaces\PublicationRepository;
use Application\Services\AuthenticationService;

class LikePublicationCommand
{
    public function __construct(
        private PublicationRepository $publicationRepository,
        private AuthenticationService $authenticationService
    ){}

    private function hasLike(int $publicationId): bool
    {
        $likes = $this->publicationRepository->getLikedForPublication($publicationId);
        foreach ($likes as $like) {
            if ($like->getId() === $this->authenticationService->getUserId()) return true;
        }
        return false;
    }

    public function execute(int $publicationId) {
        if ($this->hasLike($publicationId)) {
            $this->publicationRepository->removeLike($publicationId, $this->authenticationService->getUserId());
        } else {
            $this->publicationRepository->addLike($publicationId, $this->authenticationService->getUserId());
        }
    }
}