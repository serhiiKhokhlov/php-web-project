<?php
/**
 * @var array $data Expects:
 *  'pageTitle' (string)
 *  'likers' (array of user data for who liked the post)
 *  'postTitle' (optional, string, title of the post that was liked)
 *  'backController', 'backAction', 'backParams' (for back link to the post or blog)
 *  'profileController', 'profileAction' (for linking to user profiles from user_list_item)
 * @var callable $render
 * @var callable $htmlOut
 * @var callable $link
 */

$render('partial/header', ['pageTitle' => $data['pageTitle'] ?? 'Liked By']);
$likers = $data['likers'] ?? [];
?>
    <div class="app-container">
        <div class="d-flex align-items-center mb-4">
            <?php
            $link(
                '<i class="bi bi-arrow-left"></i>',
                $data['backController'] ?? 'Blog',
                $data['backAction'] ?? 'Mine',
                $data['backParams'] ?? [],
                'btn-back me-3',
                false
            );
            ?>
            <h2 class="page-title flex-grow-1 text-center mb-0">
                <?php echo isset($data['postTitle']) ? 'Liked "' . htmlspecialchars($data['postTitle']) . '"' : 'Liked'; // Use htmlspecialchars for safety as $htmlOut is not directly used here ?>
            </h2>
        </div>

        <div class="user-list-container">
            <?php if (empty($likers)): ?>
                <p class="text-center text-muted">No one has liked this yet.</p>
            <?php else: ?>
                <?php foreach ($likers as $userItem): ?>
                    <?php
                    $userItemData = $userItem;
                    $userItemData['profileController'] = $data['profileController'] ?? 'User';
                    $userItemData['profileAction'] = $data['profileAction'] ?? 'Blog';
                    $render('partial/user_list_item', $userItemData);
                    ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
<?php
$render('partial/footer', []);
?>