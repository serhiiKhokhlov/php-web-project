<?php

namespace Infrastructure; // Or Infrastructure if that's where it lives

use Application\Entities\Publication;
use Application\Entities\User;
use Application\Interfaces\PublicationRepository;
use Application\Interfaces\UserRepository;
use DateTime;
use DateTimeZone;

// Assuming this entity exists
// For consistent DateTime handling

class FakeRepository implements PublicationRepository, UserRepository
{
    private array $mockUsers;
    private array $mockPublications = [];
    private array $mockLikes = []; // Stores [ 'publicationId' => [userId1, userId2, ...], ... ]

    public function __construct()
    {
        $utc = new DateTimeZone('UTC');
        $now = new DateTime('now', $utc);

        // Initialize with some mock data
        // User IDs should be integers
        $this->mockUsers = [
            1 => ['id' => 1, 'username' => 'Alice',  'registerDate' => (new DateTime('2023-01-15 10:00:00', $utc)), 'passwordHash' => password_hash('pass123', PASSWORD_DEFAULT)],
            2 => ['id' => 2, 'username' => 'Bob', 'registerDate' => (new DateTime('2023-02-20 14:30:00', $utc)), 'passwordHash' => password_hash('pass456', PASSWORD_DEFAULT)],
            3 => ['id' => 3, 'username' => 'Charlie','registerDate' => (new DateTime('2023-03-10 08:15:00', $utc)), 'passwordHash' => password_hash('pass789', PASSWORD_DEFAULT)],
        ];

        // Blog IDs should be integers. authorId refers to a user ID.
        // Timestamps should be DateTime objects for consistency.
        $now = new DateTime('now', new DateTimeZone('UTC'));
        $yesterday = (new DateTime('now', new DateTimeZone('UTC')))->modify('-1 day');
        $twoDaysAgo = (new DateTime('now', new DateTimeZone('UTC')))->modify('-2 days');

        $this->mockPublications = [
            101 => ['id' => 101, 'title' => 'First Post by Alice', 'content' => 'This is the content of the first post.', 'authorId' => 1, 'createdAt' => $now],
            102 => ['id' => 102, 'title' => 'Bob Shares Thoughts', 'content' => 'Bob\'s insightful commentary.', 'authorId' => 2, 'createdAt' => $yesterday],
            103 => ['id' => 103, 'title' => 'Alice\'s Second Entry', 'content' => 'More from Alice.', 'authorId' => 1, 'createdAt' => $yesterday->modify('-2 hours')], // Make it slightly different from Bob's
            104 => ['id' => 104, 'title' => 'A Post from Charlie', 'content' => 'Charlie chimes in!', 'authorId' => 3, 'createdAt' => $twoDaysAgo],
            105 => ['id' => 105, 'title' => 'Recent Update by Bob', 'content' => 'Bob\'s very recent thoughts.', 'authorId' => 2, 'createdAt' => (new DateTime('now', new DateTimeZone('UTC')))->modify('-2 hours')],
        ];

        // Likes: publicationId => [userIds who liked it]
        $this->mockLikes = [
            101 => [2, 3], // Bob and Charlie liked Alice's first post
            102 => [1],    // Alice liked Bob's post
            105 => [1, 3], // Alice and Charlie liked Bob's recent post
        ];
    }

    // --- UserRepository Methods ---

    public function getUser(int $id): ?User
    {
        if (isset($this->mockUsers[$id])) {
            $userData = $this->mockUsers[$id];
            return new User($userData['id'], $userData['username'], $userData['registerDate'],$userData['passwordHash']);
        }
        return null;
    }

    public function getUserForUserName(string $userName): ?User
    {
        foreach ($this->mockUsers as $userData) {
            if (strtolower($userData['username']) === strtolower($userName)) {
                return new User($userData['id'], $userData['username'], $userData['registerDate'], $userData['passwordHash']);
            }
        }
        return null;
    }

    public function getUsersForLikeUserName(string $userName): ?array
    {
        $users = [];
        foreach ($this->mockUsers as $userData) {
            if (str_contains(strtolower($userData['username']), strtolower($userName))) {
                $users[] = new User($userData['id'], $userData['username'], $userData['registerDate'], $userData['passwordHash']);
            }
        }
        return $users;
    }

    /**
     * Added for completeness if needed by other parts of the application.
     * Not strictly defined by the interface in the question, but common.
     */
    public function getAllUsers(): array
    {
        $users = [];
        foreach ($this->mockUsers as $userData) {
            $users[] = new User($userData['id'], $userData['username'], $userData['registerDate'], $userData['passwordHash']);
        }
        return $users;
    }

    public function registerUser(string $userName, string $password): bool
    {
        error_log("\nRegistering user $userName\n");
        foreach ($this->mockUsers as $userData) {
            if (strtolower($userData['username']) === strtolower($userName)) {return false;}
        }
        $newId = !empty($this->mockUsers) ? max(array_keys($this->mockUsers)) + 1 : 1;
        $this->mockUsers[$newId] = ['id' => $newId, 'username' => $userName, 'passwordHash' => password_hash($password, PASSWORD_DEFAULT)];
        return true;
    }

    /**
     * For user count on welcome page.
     */
    public function getUserCnt(): int
    {
        return count($this->mockUsers);
    }


    // --- PublicationRepository Methods ---

    public function getPublicationCnt(): int
    {
        return count($this->mockPublications);
    }

    public function getRecentPublicationCnt(int $recentHours = 24): int
    {
        $recentPubsCnt = 0;
        $thresholdTime = (new DateTime('now', new DateTimeZone('UTC')))->modify("-{$recentHours} hours");

        foreach ($this->mockPublications as $pubData) {
            if ($pubData['createdAt'] >= $thresholdTime) {
                $recentPubsCnt+=1;
            }
        }

        return $recentPubsCnt;
    }

    public function getLastPublicationDate(): ?DateTime
    {
        if (empty($this->mockPublications)) {
            return null;
        }

        $lastDate = null;
        foreach ($this->mockPublications as $pubData) {
            if ($lastDate === null || $pubData['createdAt'] > $lastDate) {
                $lastDate = $pubData['createdAt'];
            }
        }
        return $lastDate;
    }

    public function getPublicationsForUser(int $userId): ?array
    {
        $userPubsData = [];
        foreach ($this->mockPublications as $pubData) {
            if ($pubData['authorId'] === $userId) {
                $userPubsData[] = $pubData;
            }
        }

        if (empty($userPubsData)) {
            return null; // Or an empty array, depending on desired contract
        }

        // Sort by creation date, newest first
        usort($userPubsData, function ($a, $b) {
            return $b['createdAt'] <=> $a['createdAt'];
        });

        $publications = [];
        foreach ($userPubsData as $data) {
            $publications[] = new Publication($data['id'], $data['title'], $data['content'], $data['authorId'], $data['createdAt']);
        }
        return $publications;
    }

    public function getPublication(int $publicationId): ?Publication
    {
        if (isset($this->mockPublications[$publicationId])) {
            $data = $this->mockPublications[$publicationId];
            return new Publication($data['id'], $data['title'], $data['content'], $data['authorId'], $data['createdAt']);
        }
        return null;
    }

    public function getLikedForPublication(int $publicationId): ?array
    {
        if (isset($this->mockLikes[$publicationId])) {
            $likerUserIds = $this->mockLikes[$publicationId];
            $likers = [];
            foreach ($likerUserIds as $userId) {
                $user = $this->getUser($userId); // Use existing method to get User object
                if ($user) {
                    $likers[] = $user;
                }
            }
            return !empty($likers) ? $likers : null; // Return null if no valid users found, or empty array
        }
        return null; // No likes for this publication or publication doesn't exist in likes map
    }

    /**
     * Gets the count of likes for a specific publication.
     * This method might be useful for display purposes.
     */
    public function getLikeCountForPublication(int $publicationId): int
    {
        return isset($this->mockLikes[$publicationId]) ? count($this->mockLikes[$publicationId]) : 0;
    }

    /**
     * Adds a new publication. For a fake repository, we might need this for testing.
     * In a real scenario, this would persist to a database.
     * Returns the ID of the newly created publication.
     */
    public function addPublication(string $title, string $content, int $authorId)
    {
        $newId = !empty($this->mockPublications) ? max(array_keys($this->mockPublications)) + 1 : 1;
        $this->mockPublications[$newId] = [
            'id' => $newId,
            'title' => $title,
            'content' => $content,
            'authorId' => $authorId,
            'createdAt' => new DateTime('now', new DateTimeZone('UTC'))
        ];
    }

    /**
     * Deletes a publication.
     */
    public function deletePublication(int $publicationId): bool
    {
        if (isset($this->mockPublications[$publicationId])) {
            unset($this->mockPublications[$publicationId]);
            // Also remove any likes associated with this publication
            if (isset($this->mockLikes[$publicationId])) {
                unset($this->mockLikes[$publicationId]);
            }
            return true;
        }
        return false;
    }

    /**
     * Adds a like from a user to a publication.
     */
    public function addLike(int $publicationId, int $userId): bool
    {
        // Ensure publication and user exist (optional for fake repo, but good practice)
        if (!isset($this->mockPublications[$publicationId]) || !isset($this->mockUsers[$userId])) {
            return false; // Or throw an exception
        }

        if (!isset($this->mockLikes[$publicationId])) {
            $this->mockLikes[$publicationId] = [];
        }

        // Add user if not already liked
        if (!in_array($userId, $this->mockLikes[$publicationId])) {
            $this->mockLikes[$publicationId][] = $userId;
        }
        return true;
    }

    /**
     * Removes a like from a user for a publication.
     */
    public function removeLike(int $publicationId, int $userId): bool
    {
        if (isset($this->mockLikes[$publicationId])) {
            $key = array_search($userId, $this->mockLikes[$publicationId]);
            if ($key !== false) {
                unset($this->mockLikes[$publicationId][$key]);
                // Optional: if no likes left, remove the publication entry from mockLikes
                if (empty($this->mockLikes[$publicationId])) {
                    unset($this->mockLikes[$publicationId]);
                }
                return true;
            }
        }
        return false;
    }
}