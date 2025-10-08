<?php
 
namespace Application;
 
class SignOutCommand
{
    public function __construct(private Services\AuthenticationService $authenticationService)
    {
    }
 
    public function execute(): void
    {
        $this->authenticationService->signOut();
    }
}