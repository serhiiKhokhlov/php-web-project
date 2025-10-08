<?php
/**
 * @var array $data Expects:
 *  'userNickname' (string, current user's nickname)
 *  'myBlogController', 'myBlogAction'
 *  'peopleController', 'peopleAction'
 *  'logoutController', 'logoutAction'
 * @var callable $link
 * @var callable $htmlOut
 */
$userNickname = $data['userNickname'] ?? 'Nickname';
?>
<div class="offcanvas offcanvas-start offcanvas-faceblog" tabindex="-1" id="profileMenuOffcanvas" aria-labelledby="profileMenuOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="profileMenuOffcanvasLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="profile-info d-flex justify-content-between align-items-center mb-4">
            <span><?php $htmlOut($userNickname); ?></span>
            <i class="bi bi-person-circle"></i>
        </div>

        <div class="list-group list-group-flush">
            <?php $link('My blog', $data['myBlogController'] ?? 'Blog', $data['myBlogAction'] ?? 'Index', [], 'list-group-item list-group-item-action'); ?>
            <?php $link('People', $data['peopleController'] ?? 'User', $data['peopleAction'] ?? 'List', [], 'list-group-item list-group-item-action'); ?>
        </div>

        <div class="mt-auto pt-3">
            <?php $link('Logout', $data['logoutController'] ?? 'User', $data['logoutAction'] ?? 'Logout', [], 'btn btn-faceblog w-100'); ?>
            <button type="button" class="btn btn-light w-100 mt-2 d-flex align-items-center justify-content-center" data-bs-dismiss="offcanvas">
                <i class="bi bi-arrow-left-short fs-4 me-2"></i> Back
            </button>
        </div>
    </div>
</div>