<?php

namespace Presentation\Controllers;

use Application\CloseProfileCommand;
use Application\LikesQuery;
use Application\NewPublicationCommand;
use Application\OpenedProfileQuery;
use Application\PublicationQuery;
use Application\SignedInUserQuery;
use Presentation\MVC\ActionResult;
use Presentation\MVC\Controller;

class Blog extends Controller
{
    const PARAM_TITLE = 'title';
    const PARAM_CONTENT = 'content';

    public function __construct(
        private SignedInUserQuery $signedInUserQuery,
        private PublicationQuery $publicationQuery,
        private LikesQuery $likesQuery,
        private NewPublicationCommand $newPublicationCommand,
        private OpenedProfileQuery $openedProfileQuery,
        private CloseProfileCommand  $closeProfileCommand
    )
    {}

    public function GET_ShowProfile(): ActionResult
    {
        $openedUser = $this->openedProfileQuery->execute();
        if (!$openedUser || $openedUser->id === $this->signedInUserQuery->execute()->id) return $this->redirect('Blog', 'Index');

        $publications = $this->publicationQuery->execute($openedUser->id);
        $posts = [];

        foreach ($publications as $publication) {
            $likers = $this->likesQuery->execute($publication->getId());
            $isLikedByCurrentUser = false;
            if ($likers !== null) {
                foreach ($likers as $liker) {
                    if ($liker->getId() === $this->signedInUserQuery->execute()->id) {
                        $isLikedByCurrentUser = true;
                        break;
                    }
                }
            }

            $posts[] = [
                'postId' => $publication->getId(),
                'title' => $publication->getTitle(),
                'content' => $publication->getContent(),
                'likes' => $likers ? count($likers) : 0,
                'timestamp' => $publication->getCreatedAt()->format('m-d H:i'),
                'isLikedByCurrentUser' => $isLikedByCurrentUser,
            ];
        }

        return $this->view('user_blog', [
            'pageTitle' => $openedUser->userName . '\'s Blog',
            'posts' => $posts,
            'userNickname' => $openedUser->userName,
            'offcanvasData' => [
                'userNickname' => $this->signedInUserQuery->execute()->userName,
                'myBlogController' => 'Blog', 'myBlogAction' => 'Index',
                'peopleController' => 'People', 'peopleAction' => 'Index',
                'logoutController' => 'User', 'logoutAction' => 'Logout',
            ],
            'newPostController' => 'Blog', 'newPostAction' => 'NewPublication',
            'peopleController' => 'User', 'peopleAction' => 'CloseProfile',
            'likeController' => 'Publication', 'likeAction' => 'Like',
            'likedByController' => 'Publication', 'likedByAction' => 'LikedBy',
        ]);
    }

    public function GET_Index() : ActionResult
    {
        $this->closeProfileCommand->execute();
        $signedUser = $this->signedInUserQuery->execute();


       // $viewToOpen = $signedUser->id === $openedUser->id ? 'my_blog' : 'user_blog';

        $publications = $this->publicationQuery->execute($signedUser->id);
        $posts = [];

        foreach ($publications as $publication) {
            $likes = $this->likesQuery->execute($publication->getId());

            $posts[] = [
                'postId' => $publication->getId(),
                'title' => $publication->getTitle(),
                'content' => $publication->getContent(),
                'likes' => $likes ? count($likes) : 0,
                'timestamp' => $publication->getCreatedAt()->format('m-d H:i'),
            ];
        }

        return $this->view('my_blog', [
            'pageTitle' => $signedUser->userName . '\'s Blog',
            'posts' => $posts,
            'userNickname' => $signedUser->userName,
            'offcanvasData' => [
                'userNickname' => $signedUser->userName,
                'myBlogController' => 'Blog', 'myBlogAction' => 'Index',
                'peopleController' => 'People', 'peopleAction' => 'Index',
                'logoutController' => 'User', 'logoutAction' => 'Logout',
            ],
            'newPostController' => 'Blog', 'newPostAction' => 'NewPublication',
            'deletePostController' => 'Publication', 'deletePostAction' => 'Delete',
            'likedByController' => 'Publication', 'likedByAction' => 'LikedBy'
        ]);
    }

    public function GET_NewPublication(): ActionResult
    {
        return $this->view("new_post", [
            'pageTitle' => 'New publication',
            'formActionController' => 'Blog', 'formActionName' => 'NewPublication',
            'myBlogController' => 'Blog', 'myBlogAction' => 'Index',
        ]);
    }

    public function POST_NewPublication(): ActionResult
    {
        if ($this->tryGetParam(self::PARAM_TITLE, $title) && $this->tryGetParam(self::PARAM_CONTENT, $content) ) {
            $this->newPublicationCommand->execute($title, $content);
        }
        return $this->redirect("Blog", "Index");
    }
}