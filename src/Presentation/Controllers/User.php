<?php

namespace Presentation\Controllers;

use Application\CloseProfileCommand;
use Application\OpenProfileCommand;
use Application\RegisterCommand;
use Application\SignInCommand;
use Application\SignOutCommand;
use Presentation\MVC\ActionResult;
use Presentation\MVC\Controller;

class User extends Controller
{
    const PARAM_ID = 'id';
    const PARAM_USERNAME = 'nickname';
    const PARAM_PASSWORD = 'password';
    const PARAM_REPEAT_PASSWORD = 'repeat_password';

    public function __construct(
        private SignInCommand $signInCommand,
        private RegisterCommand $registerCommand,
        private SignOutCommand $signOutCommand,
        private OpenProfileCommand $openProfileCommand,
        private CloseProfileCommand $closeProfileCommand,
    )
    {}

    public function GET_Login(): ActionResult
    {
        return $this->view("login", [
            'pageTitle' => 'Login',
            'homeController' => 'Home',
            'homeAction' => 'Index',
            'loginPostController' => 'User',
            'loginPostAction' => 'Login',
            'nickname' => $this->tryGetParam(self::PARAM_USERNAME, $value) ? $value : ''
        ]);
    }

    public function GET_Register(): ActionResult
    {
        return $this->view("register", [
            'pageTitle' => 'Register',
            'homeController' => 'Home',
            'homeAction' => 'Index',
            'registerPostController' => 'User',
            'registerPostAction' => 'Register',
            'nickname' => $this->tryGetParam(self::PARAM_USERNAME, $value) ? $value : '',
            'password' => $this->tryGetParam(self::PARAM_PASSWORD, $value) ? $value : '',
            'repeat_password' => $this->tryGetParam(self::PARAM_REPEAT_PASSWORD, $value) ? $value : ''
        ]);
    }

    public function POST_Login(): ActionResult
    {
        // try to authenticate the given user
        if (!$this->signInCommand->execute($this->getParam(self::PARAM_USERNAME), $this->getParam(self::PARAM_PASSWORD))) {
            return $this->view('login', [
                'pageTitle' => 'Login',
                'homeController' => 'Home',
                'homeAction' => 'Index',
                'loginPostController' => 'User',
                'loginPostAction' => 'Login',
                'errors' => ['Invalid user name or password.'],
                'nickname' => $this->getParam(self::PARAM_USERNAME),
            ]);
        }
        return $this->redirect('Blog', 'Index');
    }

    public function POST_Register(): ActionResult
    {
        if ($this->getParam(self::PARAM_PASSWORD) != $this->getParam(self::PARAM_REPEAT_PASSWORD)) {
            return $this->view("register", [
                'pageTitle' => 'Register',
                'homeController' => 'Home',
                'homeAction' => 'Index',
                'registerPostController' => 'User',
                'registerPostAction' => 'Register',
                'nickname' => $this->getParam(self::PARAM_USERNAME),
                'password' => $this->getParam(self::PARAM_PASSWORD),
                'repeat_password' => $this->getParam(self::PARAM_REPEAT_PASSWORD),
                'errors' => ['Passwords do not match.'],
            ]);
        }

        if (!$this->registerCommand->execute($this->getParam(self::PARAM_USERNAME), $this->getParam(self::PARAM_PASSWORD))) {
            return $this->view("register", [
                'pageTitle' => 'Register',
                'homeController' => 'Home',
                'homeAction' => 'Index',
                'registerPostController' => 'User',
                'registerPostAction' => 'Register',
                'errors' => ['Username is already taken.'],
                'nickname' => $this->getParam(self::PARAM_USERNAME),
                'password' => $this->getParam(self::PARAM_PASSWORD),
                'repeat_password' => $this->getParam(self::PARAM_REPEAT_PASSWORD),
            ]);
        }

        return $this->redirect('Home', 'Index');
    }

    public function GET_OpenProfile(): ActionResult
    {

        if ($this->tryGetParam(self::PARAM_ID, $id)) {
            $this->openProfileCommand->execute($id);
        }
        return $this->redirect('Blog', 'ShowProfile');
    }


    public function GET_CloseProfile(): ActionResult
    {
        $this->closeProfileCommand->execute();
        return $this->redirect('People', 'Index');
    }

    public function GET_Logout(): ActionResult
    {
        $this->signOutCommand->execute();
        $this->closeProfileCommand->execute();
        return $this->redirect('Home', 'Index');
    }

}