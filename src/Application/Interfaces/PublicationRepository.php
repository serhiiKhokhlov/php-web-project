<?php

namespace Application\Interfaces;

use Application\Entities\Publication;
use DateTime;

interface PublicationRepository
{
    public function getPublicationCnt(): int;
    public function getRecentPublicationCnt(int $recentHours = 24): int;
    public function getLastPublicationDate(): ?DateTime;
    public function getPublicationsForUser(int $userId): ?array;
    public function getPublication(int $publicationId): ?Publication;
    public function getLikedForPublication(int $publicationId): ?array;
    public function addPublication(string $title, string $content, int $authorId);
    public function deletePublication(int $publicationId);
    public function addLike(int $publicationId, int $userId): bool;
    public function removeLike(int $publicationId, int $userId): bool;
}