<?php
 
namespace Application\Entities;
 
use DateTime;

class User
{
    public function __construct(
        private int $id,
        private string $userName,
        private DateTime $registerDate,
        private string $passwordHash
    ) {
    }
 
    public function getId(): int
    {
        return $this->id;
    }
 
    public function getUserName(): string
    {
        return $this->userName;
    }
 
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getRegisterDate(): DateTime
    {
        return $this->registerDate;
    }
}