<?php
/**
 * @var array $data Expects:
 *  'nickname'
 *  'sinceDate' (optional)
 *  'userId' (optional, for profile link)
 *  'profileController' (string, e.g., 'User')
 *  'profileAction' (string, e.g., 'Blog')
 * @var callable $htmlOut
 * @var callable $link
 */
?>
<div class="user-list-item d-flex justify-content-between align-items-center">
    <div>
        <strong class="fs-5"><?php $htmlOut($data['nickname']); ?></strong>
        <?php if (isset($data['sinceDate']) && $data['sinceDate']): ?>
            <div class="user-since">since <?php $htmlOut($data['sinceDate']); ?></div>
        <?php endif; ?>
    </div>
    <?php if (isset($data['userId']) && isset($data['profileController']) && isset($data['profileAction'])): ?>
        <?php
        $link(
            '<i class="bi bi-person-fill"></i>',
            $data['profileController'],
            $data['profileAction'],
            ['id' => $data['userId']],
            'text-decoration-none',
            false
        );
        ?>
    <?php else: ?>
        <i class="bi bi-person-fill"></i> <?php // Fallback if no link params provided ?>
    <?php endif; ?>
</div>