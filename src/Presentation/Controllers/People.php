<?php

namespace Presentation\Controllers;

use Application\PeopleQuery;
use Application\SignedInUserQuery;
use Presentation\MVC\Controller;

class People extends Controller
{
    const PARAM_SEARCH_NAME = "search_name";
    public function __construct(
        private PeopleQuery $peopleQuery,
        private SignedInUserQuery $signedInUserQuery
    ){}

    public function GET_Index() {
        $curUser = $this->signedInUserQuery->execute();
        $users = [];
        $promptNickname = null;
        if ($this->tryGetParam(self::PARAM_SEARCH_NAME, $promptNickname)) {
            $allUsers = $this->peopleQuery->execute($promptNickname);
            foreach ($allUsers as $user) {
                if ($user->getId() !== $curUser->id) {
                    $users[] = [
                        'userId' => $user->getId(),
                        'nickname' => $user->getUsername(),
                        'sinceDate' => $user->getRegisterDate()->format('Y-m-d'),
                    ];
                }
            }
        }

        return $this->view('people', [
            'pageTitle' => 'People',
            'users' => $users,
            'offcanvasData' => [
                'userNickname' => $curUser->userName,
                'myBlogController' => 'Blog', 'myBlogAction' => 'Index',
                'peopleController' => 'People', 'peopleAction' => 'Index',
                'logoutController' => 'User', 'logoutAction' => 'Logout',
            ],
            'searchQuery' => $promptNickname,
            'searchController' => 'People', 'searchAction' => 'Index',
            'profileController' => 'User', 'profileAction' => 'OpenProfile',
        ]);
    }
}