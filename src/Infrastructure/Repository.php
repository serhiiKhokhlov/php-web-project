<?php

namespace Infrastructure;

use Application\Entities\Publication;
use Application\Entities\User;
use Application\Interfaces\PublicationRepository;
use Application\Interfaces\UserRepository;
use DateTime;
use DateTimeZone;
use mysqli; // For type hinting

class Repository implements PublicationRepository, UserRepository
{
    private string $server;
    private string $userName;
    private string $password;
    private string $database;
    private DateTimeZone $utc;

    public function __construct(string $server, string $userName, string $password, string $database)
    {
        $this->server = $server;
        $this->userName = $userName;
        $this->password = $password;
        $this->database = $database;
        $this->utc = new DateTimeZone('UTC'); // For consistent DateTime handling
    }

    // === private helper methods (adapted from reference) ===

    private function getConnection(): mysqli
    {
        $con = new \mysqli($this->server, $this->userName, $this->password, $this->database);
        if ($con->connect_error) {
            die('Unable to connect to database. Error: ' . $con->connect_error);
        }
        $con->set_charset('utf8mb4');
        return $con;
    }

    /**
     * Executes a simple query without parameters.
     * WARNING: Do NOT use with user input directly in the query string to avoid SQL injection.
     */
    private function executeQuery(mysqli $connection, string $query)
    {
        $result = $connection->query($query);
        if (!$result) {
            die("Error in query '$query': " . $connection->error);
        }
        return $result;
    }

    /**
     * Prepares, binds parameters, and executes a statement.
     * $paramTypes: string like 'iss' (integer, string, string)
     * $params: array of parameters
     */
    private function executeStatement(mysqli $connection, string $query, string $paramTypes = "", array $params = [])
    {
        $statement = $connection->prepare($query);
        if (!$statement) {
            die("Error in prepared statement '$query': " . $connection->error);
        }
        if ($paramTypes && $params) {
            $statement->bind_param($paramTypes, ...$params);
        }
        if (!$statement->execute()) {
            die("Error executing prepared statement '$query': " . $statement->error);
        }
        return $statement;
    }

    // Helper to map a DB row to a User entity
    private function mapRowToUser(object $row): User
    {
        return new User(
            (int)$row->ID,
            $row->Username,
            new DateTime($row->RegDate, $this->utc),
            $row->PasswdHash
        );
    }

    // Helper to map a DB row to a Publication entity
    private function mapRowToPublication(object $row): Publication
    {
        return new Publication(
            (int)$row->ID,
            $row->Title,
            $row->Content,
            (int)$row->AuthorID,
            new DateTime($row->CreatedAt, $this->utc)
        );
    }

    // --- UserRepository Methods ---

    public function getUser(int $id): ?User
    {
        $user = null;
        $connection = $this->getConnection();
        $statement = $this->executeStatement(
            $connection,
            'SELECT ID, Username, RegDate, PasswdHash FROM `User` WHERE ID = ?',
            'i',
            [$id]
        );

        $result = $statement->get_result();
        if ($row = $result->fetch_object()) {
            $user = $this->mapRowToUser($row);
        }
        $statement->close();
        $connection->close();
        return $user;
    }

    public function getUserForUserName(string $userName): ?User
    {
        $user = null;
        $connection = $this->getConnection();
        $statement = $this->executeStatement(
            $connection,
            'SELECT ID, Username, RegDate, PasswdHash FROM `User` WHERE Username = ?',
            's',
            [$userName]
        );

        $result = $statement->get_result();
        if ($row = $result->fetch_object()) {
            $user = $this->mapRowToUser($row);
        }
        $statement->close();
        $connection->close();
        return $user;
    }

    public function getUsersForLikeUserName(string $userName): array
    {
        $users = [];
        $connection = $this->getConnection();
        $searchTerm = "%{$userName}%";
        $statement = $this->executeStatement(
            $connection,
            'SELECT ID, Username, RegDate, PasswdHash FROM `User` WHERE Username LIKE ?',
            's',
            [$searchTerm]
        );

        $result = $statement->get_result();
        while ($row = $result->fetch_object()) {
            $users[] = $this->mapRowToUser($row);
        }
        $statement->close();
        $connection->close();
        return $users;
    }

    public function getAllUsers(): array
    {
        $users = [];
        $connection = $this->getConnection();
        $res = $this->executeQuery($connection, 'SELECT ID, Username, RegDate, PasswdHash FROM `User`');
        while ($row = $res->fetch_object()) {
            $users[] = $this->mapRowToUser($row);
        }
        $res->close();
        $connection->close();
        return $users;
    }

    public function registerUser(string $userName, string $password): bool
    {
        $connection = $this->getConnection();
        $existingUser = $this->getUserForUserName($userName);
        if ($existingUser) {
            $connection->close();
            return false;
        }

        // If getUserForUserName didn't open its own connection or closed it, we re-ensure
        if ($connection->connect_errno) { // Re-check connection if it was closed
            $connection = $this->getConnection();
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $regDate = (new DateTime('now', $this->utc))->format('Y-m-d H:i:s');

        $statement = $this->executeStatement(
            $connection,
            'INSERT INTO `User` (Username, PasswdHash, RegDate) VALUES (?, ?, ?)',
            'sss',
            [$userName, $passwordHash, $regDate]
        );

        $success = $statement->affected_rows > 0;
        $statement->close();
        $connection->close();
        return $success;
    }

    public function getUserCnt(): int
    {
        $count = 0;
        $connection = $this->getConnection();
        $res = $this->executeQuery($connection, 'SELECT COUNT(*) as count FROM `User`');
        if ($row = $res->fetch_object()) {
            $count = (int)$row->count;
        }
        $res->close();
        $connection->close();
        return $count;
    }

    // --- PublicationRepository Methods ---

    public function getPublicationCnt(): int
    {
        $count = 0;
        $connection = $this->getConnection();
        $res = $this->executeQuery($connection, 'SELECT COUNT(*) as count FROM `Publication`');
        if ($row = $res->fetch_object()) {
            $count = (int)$row->count;
        }
        $res->close();
        $connection->close();
        return $count;
    }

    public function getRecentPublicationCnt(int $recentHours = 24): int
    {
        $count = 0;
        $connection = $this->getConnection();
        // Calculate the threshold time in UTC for the database query
        $thresholdDateTime = (new DateTime('now', $this->utc))->modify("-{$recentHours} hours");
        $thresholdSql = $thresholdDateTime->format('Y-m-d H:i:s');

        $statement = $this->executeStatement(
            $connection,
            'SELECT COUNT(*) as count FROM `Publication` WHERE CreatedAt >= ?',
            's',
            [$thresholdSql]
        );
        $result = $statement->get_result();
        if ($row = $result->fetch_object()) {
            $count = (int)$row->count;
        }
        $statement->close();
        $connection->close();
        return $count;
    }

    public function getLastPublicationDate(): ?DateTime
    {
        $lastDate = null;
        $connection = $this->getConnection();
        $res = $this->executeQuery($connection, 'SELECT MAX(CreatedAt) as max_date FROM `Publication`');
        if ($row = $res->fetch_object()) {
            if ($row->max_date !== null) {
                $lastDate = new DateTime($row->max_date, $this->utc);
            }
        }
        $res->close();
        $connection->close();
        return $lastDate;
    }

    public function getPublicationsForUser(int $userId): array // ?array was in fake, but array is more common
    {
        $publications = [];
        $connection = $this->getConnection();
        $statement = $this->executeStatement(
            $connection,
            'SELECT ID, Title, Content, AuthorID, CreatedAt FROM `Publication` WHERE AuthorID = ? ORDER BY CreatedAt DESC',
            'i',
            [$userId]
        );

        $result = $statement->get_result();
        while ($row = $result->fetch_object()) {
            $publications[] = $this->mapRowToPublication($row);
        }
        $statement->close();
        $connection->close();
        return $publications;
    }

    public function getPublication(int $publicationId): ?Publication
    {
        $publication = null;
        $connection = $this->getConnection();
        $statement = $this->executeStatement(
            $connection,
            'SELECT ID, Title, Content, AuthorID, CreatedAt FROM `Publication` WHERE ID = ?',
            'i',
            [$publicationId]
        );

        $result = $statement->get_result();
        if ($row = $result->fetch_object()) {
            $publication = $this->mapRowToPublication($row);
        }
        $statement->close();
        $connection->close();
        return $publication;
    }

    public function getLikedForPublication(int $publicationId): array // ?array was in fake
    {
        $likers = [];
        $connection = $this->getConnection();
        $statement = $this->executeStatement(
            $connection,
            'SELECT u.ID, u.Username, u.RegDate, u.PasswdHash
             FROM `User` u
             JOIN `Like` l ON u.ID = l.LikedByID
             WHERE l.LikedPublicationID = ?',
            'i',
            [$publicationId]
        );

        $result = $statement->get_result();
        while ($row = $result->fetch_object()) {
            $likers[] = $this->mapRowToUser($row);
        }
        $statement->close();
        $connection->close();
        return $likers;
    }

    public function getLikeCountForPublication(int $publicationId): int
    {
        $count = 0;
        $connection = $this->getConnection();
        $statement = $this->executeStatement(
            $connection,
            'SELECT COUNT(*) as count FROM `Like` WHERE LikedPublicationID = ?',
            'i',
            [$publicationId]
        );
        $result = $statement->get_result();
        if ($row = $result->fetch_object()) {
            $count = (int)$row->count;
        }
        $statement->close();
        $connection->close();
        return $count;
    }

    public function addPublication(string $title, string $content, int $authorId): ?int
    {
        $connection = $this->getConnection();
        $createdAt = (new DateTime('now', $this->utc))->format('Y-m-d H:i:s');

        $statement = $this->executeStatement(
            $connection,
            'INSERT INTO `Publication` (Title, Content, AuthorID, CreatedAt) VALUES (?, ?, ?, ?)',
            'ssis',
            [$title, $content, $authorId, $createdAt]
        );

        $newId = $statement->insert_id ?: null;
        $statement->close();
        $connection->close();
        return $newId;
    }

    public function deletePublication(int $publicationId): bool
    {
        $connection = $this->getConnection();
        // Note: Likes will be deleted automatically due to ON DELETE CASCADE if set in DB schema

        $statement = $this->executeStatement(
            $connection,
            'DELETE FROM `Publication` WHERE ID = ?',
            'i',
            [$publicationId]
        );

        $success = $statement->affected_rows > 0;
        $statement->close();
        $connection->close();
        return $success;
    }

    public function addLike(int $publicationId, int $userId): bool
    {
        $connection = $this->getConnection();

        // Using INSERT IGNORE to prevent errors if the like already exists (due to PRIMARY KEY)
        $statement = $this->executeStatement(
            $connection,
            'INSERT IGNORE INTO `Like` (LikedPublicationID, LikedByID) VALUES (?, ?)',
            'ii',
            [$publicationId, $userId]
        );

        $success = $statement->affected_rows > 0; // Will be 0 if IGNORE prevented an insert
        $statement->close();
        $connection->close();
        return $success;
    }

    public function removeLike(int $publicationId, int $userId): bool
    {
        $connection = $this->getConnection();
        $statement = $this->executeStatement(
            $connection,
            'DELETE FROM `Like` WHERE LikedPublicationID = ? AND LikedByID = ?',
            'ii',
            [$publicationId, $userId]
        );

        $success = $statement->affected_rows > 0;
        $statement->close();
        $connection->close();
        return $success;
    }
}