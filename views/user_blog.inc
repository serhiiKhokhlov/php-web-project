<?php
/**
 * @var array $data Expects:
 *  'pageTitle' (string, e.g., "Nickname's Blog")
 *  'userNickname' (string, the blog owner's nickname)
 *  'posts' (array of post data arrays for this user)
 *  'offcanvasData' (array for offcanvas_menu partial, may need to be contextual)
 *  'peopleController', 'peopleAction' (for back link)
 *  'likeController', 'likeAction'
 *  'likedByController', 'likedByAction'
 * @var callable $render
 * @var callable $htmlOut
 * @var callable $link
 */

$render('partial/header', ['pageTitle' => $data['pageTitle'] ?? "User's Blog"]);
$render('partial/offcanvas_menu', $data['offcanvasData'] ?? []);

$userName = $data['userNickname'] ?? 'User';
$posts = $data['posts'] ?? [];
?>

    <div class="app-header d-flex align-items-center">
        <?php
        $link(
            '<i class="bi bi-arrow-left"></i>',
            $data['peopleController'] ?? 'User',
            $data['peopleAction'] ?? 'List',
            [],
            'btn btn-menu p-0 me-3',
            false
        );
        ?>
        <h1 class="page-title-header text-center flex-grow-1"><?php $htmlOut($userName); ?>'s blog</h1>
        <button class="btn btn-menu p-0 ms-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#profileMenuOffcanvas" aria-controls="profileMenuOffcanvas">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <div class="container py-3" style="width: 100%;">
        <?php if (empty($posts)): ?>
            <p class="text-center text-muted mt-5"><?php $htmlOut($userName); ?> hasn't posted anything yet.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <?php
                $post['showDelete'] = false; // Can't delete others' posts
                $post['likeController'] = $data['likeController'] ?? 'Blog';
                $post['likeAction'] = $data['likeAction'] ?? 'Like';
                $post['likedByController'] = $data['likedByController'] ?? 'Blog';
                $post['likedByAction'] = $data['likedByAction'] ?? 'Delete';
                $render('partial/blog_post_card', $post);
                ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

<?php
$render('partial/footer', []);
?>