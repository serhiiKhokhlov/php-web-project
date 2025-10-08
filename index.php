<?php

use Application\CloseProfileCommand;
use Application\OpenProfileCommand;

spl_autoload_register(function ($class) {
    $file = __DIR__ . '/src/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once($file);
    }
});

$sp = new \ServiceProvider();

$sp->register(\Presentation\MVC\MVC::class, function() {
    return new \Presentation\MVC\MVC();
}, isSingleton: true);

//services
$sp->register(\Application\Services\AuthenticationService::class);
$sp->register(\Application\Services\ViewedProfileService::class);

//commands and queries
$sp->register(\Application\SignedInUserQuery::class);
$sp->register(\Application\SignInCommand::class);
$sp->register(\Application\SignOutCommand::class);
$sp->register(\Application\StatsQuery::class);
$sp->register(\Application\RegisterCommand::class);
$sp->register(\Application\PublicationQuery::class);
$sp->register(\Application\LikesQuery::class);
$sp->register(\Application\DeletePublicationCommand::class);
$sp->register(\Application\LikePublicationCommand::class);
$sp->register(\Application\NewPublicationCommand::class);
$sp->register(OpenProfileCommand::class);
$sp->register(CloseProfileCommand::class);
$sp->register(\Application\OpenedProfileQuery::class);
$sp->register(\Application\PeopleQuery::class);

//controllers
$sp->register(\Presentation\Controllers\Home::class);
$sp->register(\Presentation\Controllers\User::class);
$sp->register(\Presentation\Controllers\Blog::class);
$sp->register(\Presentation\Controllers\Publication::class);
$sp->register(\Presentation\Controllers\People::class);

//infrastructure
$sp->register(\Infrastructure\Session::class, isSingleton: true);
$sp->register(\Application\Interfaces\Session::class, \Infrastructure\Session::class);

$sp->register(\Infrastructure\Repository::class, function() {
    return new \Infrastructure\Repository('localhost', 'root', '', 'faceblog');
}, isSingleton: true);
$sp->register(\Application\Interfaces\UserRepository::class, \Infrastructure\Repository::class);
$sp->register(\Application\Interfaces\PublicationRepository::class, \Infrastructure\Repository::class);


$sp->resolve(\Presentation\MVC\MVC::class)->handleRequest($sp);