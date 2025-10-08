<?php

namespace Application\Services;

use Application\Interfaces\Session;

class ViewedProfileService
{
    const PARAM_VIEWED_PROFILE_ID = 'viewed_profile_id';
    public function __construct(
       private Session $session,
    ){}

    public function getViewedProfileId(): ?int {
        return $this->session->get(self::PARAM_VIEWED_PROFILE_ID);
    }

    public function openProfile(int $userId): void {
        $this->session->put(self::PARAM_VIEWED_PROFILE_ID, $userId);
    }

    public function closeProfile(): void {
        $this->session->delete(self::PARAM_VIEWED_PROFILE_ID);
    }
}