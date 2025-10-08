<?php

namespace Application\Entities;

use DateTime;

class Publication
{
    public function __construct(
        private int $id,
        private string $title,
        private string $content,
        private string $authorId,
        private DateTime $createdAt,
    ) {
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function getAuthorId(): int {
        return $this->authorId;
    }

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }
}