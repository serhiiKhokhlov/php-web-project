<?php
/**
 * @var array $data Expects:
 *  'pageTitle' (string)
 *  'posts' (array of post data arrays)
 *  'userNickname' (string)
 *  'offcanvasData' (array for offcanvas_menu partial)
 *  'newPostController', 'newPostAction'
 *  'deletePostController', 'deletePostAction' (for blog_post_card)
 *  'likedByController', 'likedByAction'
 * @var callable $render
 * @var callable $htmlOut
 * @var callable $link
 */

$render('partial/header', ['pageTitle' => $data['pageTitle'] ?? 'My Blog']);
$render('partial/offcanvas_menu', $data['offcanvasData'] ?? []);

$posts = $data['posts'] ?? [];
?>

    <div class="app-header d-flex align-items-center">
        <button class="btn btn-menu p-0 me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#profileMenuOffcanvas" aria-controls="profileMenuOffcanvas">
            <i class="bi bi-list"></i>
        </button>
        <h1 class="page-title-header text-center flex-grow-1">My blog</h1>
        <div style="width: 40px;"></div> <?php // Spacer for balance ?>
    </div>

    <div class="container py-3" style="width=100% ; padding-bottom: 100px;">
        <?php if (empty($posts)): ?>
            <p class="text-center text-muted mt-5">You haven't posted anything yet. Why not create your first post?</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <?php
                $post['showDelete'] = true;
                $post['deleteController'] = $data['deletePostController'] ?? 'Blog';
                $post['deleteAction'] = $data['deletePostAction'] ?? 'Delete';
                $post['likedByController'] = $data['likedByController'] ?? 'Blog';
                $post['likedByAction'] = $data['likedByAction'] ?? 'Delete';
                $render('partial/blog_post_card', $post);
                ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

<?php
$link(
    '<i class="bi bi-plus-lg"></i>',
    $data['newPostController'] ?? 'Post',
    $data['newPostAction'] ?? 'Create',
    [],
    'btn btn-faceblog fab shadow',
    false
);
?>

<?php
$render('partial/footer', []);
?>