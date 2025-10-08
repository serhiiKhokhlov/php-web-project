<?php

namespace Application;

use Application\Interfaces\PublicationRepository;
use Application\Interfaces\UserRepository;

class StatsQuery
{
    public function __construct(
        private PublicationRepository $publicationRepository,
        private UserRepository $userRepository
    ) {
    }

    public function execute(): ?array
    {
        $publication = $this->publicationRepository->getLastPublicationDate();
        $publication = $publication ? $publication->format('d M Y') : '';
        $stats = [
            'already_using' => $this->userRepository->getUserCnt() ?? 0,
            'publications_made' => $this->publicationRepository->getPublicationCnt() ?? 0,
            'publications_last_24h' => $this->publicationRepository->getRecentPublicationCnt(24) ?? 0,
            'last_contribution_on' => $publication
        ];
        return $stats;
    }
}