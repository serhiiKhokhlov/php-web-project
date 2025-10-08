<?php
/**
 * @var array $data Expects:
 *  'title', 'content', 'likes', 'timestamp'
 *  'postId' (optional, for delete link)
 *  'isLikedByCurrentUser' (boolean, optional - to control heart fill state)
 *  'showDelete' (boolean, optional)
 *  'deleteController' (string, e.g., 'Blog')
 *  'deleteAction' (string, e.g., 'Delete')
 *  'likeController' (string, e.g., 'Blog' or 'Like')
 *  'likeAction' (string, e.g., 'ToggleLike')
 *  'likedByController', 'likedByAction'
 *  'likes  '
 * @var callable $htmlOut
 * @var callable $link
 * @var callable $beginForm
 * @var callable $endForm
 */

$isLiked = isset($data['isLikedByCurrentUser']) && $data['isLikedByCurrentUser'];
?>
<div class="blog-card">
    <div class="d-flex justify-content-between align-items-start mb-1">
        <h5 class="card-title mb-0"><?php $htmlOut($data['title']); ?></h5>
        <?php if (isset($data['showDelete']) && $data['showDelete'] && isset($data['postId'])): ?>

            <?php $beginForm($data['deleteController'] ?? 'Blog', $data['deleteAction'] ?? 'Delete', ['id' => $data['postId']], 'POST', 'd-inline'); ?>
            <button type="submit" class="btn btn-sm p-0 text-decoration-none action-icon-delete" title="Delete">
                <?php // Your delete SVG here, e.g.: ?>
                <svg xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960" width="25px" fill="currentColor" class="action-icon-svg"><path d="M280-120q-33 0-56.5-23.5T200-200v-480h-40v-80h200v-40h240v40h200v80h-40v480q0 33-23.5 56.5T680-120H280Zm400-560H280v480h400v-480ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-640v480-480Z"/></svg>
            </button>
            <?php $endForm(); ?>

        <?php endif; ?>
    </div>

    <div class="card-meta d-flex justify-content-between align-items-center mb-2">
        <div class="d-flex align-items-center">
            <?php
            $heartSvg = '';
            if ($isLiked) {
                // Liked state SVG
                $heartSvg = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#EA3323" class="action-icon-svg heart-icon"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg>';
            } else {
                // Unliked state SVG
                $heartSvg = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000" class="action-icon-svg heart-icon"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg>';
            }

            if (isset($data['postId']) && isset($data['likeController']) && isset($data['likeAction'])) {

                $beginForm($data['likeController'], $data['likeAction'], ['id' => $data['postId']], 'POST', 'd-inline action-icon-like ' . ($isLiked ? 'liked' : ''));
                echo '<button type="submit" class="btn btn-sm p-0 me-1 text-decoration-none" title="' . ($isLiked ? 'Unlike' : 'Like') . '">';
                echo $heartSvg;
                echo '</button>';
                $endForm();

            } else {
                // Fallback if like action is not configured for this card (e.g., viewing someone else's non-interactive blog)
                echo $heartSvg;
            }
            ?>
            <?php
            $likeCountContent = '<span class="like-count" style="font-size: 1.3em;">' . htmlspecialchars((int)($data['likes'] ?? 0), ENT_QUOTES, 'UTF-8') . '</span>'; // Prepare content first

            if (isset($data['postId']) && isset($data['likedByController']) && isset($data['likedByAction']) && (int)($data['likes'] ?? 0) > 0) {
                $link(
                    $likeCountContent,
                    $data['likedByController'],
                    $data['likedByAction'],
                    ['post_title' => $data['title'],
                     'post_id' => $data['postId']],
                    'like-count-link',
                    false
                );
            } else {
                echo $likeCountContent;
            }
            ?>
        </div>
        <span class="timestamp"><?php $htmlOut($data['timestamp'] ?? ''); ?></span>
    </div>
    <p style="font-size: 1.2em"><?php $htmlOut($data['content']); ?></p>
</div>