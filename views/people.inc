<?php
/**
 * @var array $data Expects:
 *  'pageTitle' (string)
 *  'users' (array of user data arrays)
 *  'offcanvasData' (array for offcanvas_menu partial)
 *  'searchQuery' (string, current search term)
 *  'searchController', 'searchAction'
 *  'profileController', 'profileAction' (for linking to user profiles)
 *  'controllerParameterName', 'actionParameterName' (from MVC)
 * @var callable $render
 * @var callable $htmlOut
 * @var callable $link
 * @var callable $beginForm
 * @var callable $endForm
 */

$render('partial/header', ['pageTitle' => $data['pageTitle'] ?? 'People']);
$render('partial/offcanvas_menu', $data['offcanvasData'] ?? []);

$users = $data['users'] ?? [];
$searchQuery = $data['searchQuery'] ?? '';
?>

    <div class="app-header d-flex align-items-center">
        <button class="btn btn-menu p-0 me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#profileMenuOffcanvas" aria-controls="profileMenuOffcanvas">
            <i class="bi bi-list"></i>
        </button>
        <h1 class="page-title-header text-center flex-grow-1">People</h1>
        <div style="width: 40px;"></div>
    </div>

    <div class="container py-3" style="max-width: 500px;">
        <?php $beginForm($data['searchController'] ?? 'User', $data['searchAction'] ?? 'List', [], 'GET'); ?>
        <div class="input-group mb-4">
            <input type="text" class="form-control form-control-faceblog" name="search_name" placeholder="Search by name..." value="<?php $htmlOut($searchQuery); ?>">
            <button class="btn btn-faceblog" type="submit"><i class="bi bi-search"></i></button>
        </div>
        <?php $endForm(); ?>

        <div class="user-list-container">
            <?php if (empty($users) && !empty($searchQuery)): ?>
                <p class="text-center text-muted">No users found matching "<?php $htmlOut($searchQuery); ?>".</p>
            <?php elseif (empty($users)): ?>
                <p class="text-center text-muted">No users to display.</p>
            <?php else: ?>
                <?php foreach ($users as $userItem): ?>
                    <?php
                    // Prepare data for user_list_item partial
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