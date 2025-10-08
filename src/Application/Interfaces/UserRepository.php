<?php
 
namespace Application\Interfaces;
 
use Application\Entities\User;
use DateTime;

interface UserRepository
{
    public function getUser(int $id): ?User;
    public function getUserCnt(): int;
    public function getUserForUserName(string $userName): ?User;
    public function registerUser(string $userName, string $password): bool;
    public function getUsersForLikeUserName(string $userName): ?array;
}