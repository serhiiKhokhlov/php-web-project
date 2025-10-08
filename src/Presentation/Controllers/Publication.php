<?php

namespace Presentation\Controllers;

use Application\DeletePublicationCommand;
use Application\LikePublicationCommand;
use Application\LikesQuery;
use Application\OpenedProfileQuery;
use Presentation\MVC\ActionResult;
use Presentation\MVC\Controller;

class Publication extends Controller
{
    const PARAM_POST_TITLE = 'post_title';
    const PARAM_POST_ID = 'post_id';

    public function __construct(
        private DeletePublicationCommand $deletePublicationCommand,
        private LikePublicationCommand $likePublicationCommand,
        private LikesQuery $likesQuery,
        private OpenedProfileQuery $openedProfileQuery,
    ){}

    public function GET_LikedBy() : ActionResult
    {
        $likers = null;
        if ($this->tryGetParam(self::PARAM_POST_ID, $postId)) {

            foreach ($this->likesQuery->execute($postId) as $liker) {
                $likers[] = [
                    'userId' => $liker->getId(),
                    'nickname' => $liker->getUsername(),
                    'sinceDate' => $liker->getRegisterDate()->format('Y-m-d'),
                ];
            }
        }

        $profile = $this->openedProfileQuery->execute();
        if (!$this->tryGetParam(self::PARAM_POST_TITLE, $postTitle))
            $postTitle = null;

        return $this->view('liked_by', [
            'pageTitle' => 'Liked by',
            'likers' => $likers,
            'postTitle' => $postTitle,
            'backController' => 'Blog',
            'backAction' => $profile ? 'ShowProfile' : 'Index',
            'profileController' => 'User',
            'profileAction' => 'OpenProfile'
        ]);
    }

    public function POST_Delete() : ActionResult
    {
        if ($this->tryGetParam("id", $publicationId)) {
            $this->deletePublicationCommand->execute($publicationId);
        }
        return $this->redirect('Blog', 'Index');
    }

    public function POST_Like() : ActionResult
    {
        if ($this->tryGetParam("id", $publicationId)) {
            $this->likePublicationCommand->execute($publicationId);
        }
        return $this->redirect('Blog', 'ShowProfile') ;
    }

}