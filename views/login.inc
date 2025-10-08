<?php
/**
 * @var array $data Expects:
 *  'pageTitle' (string)
 *  'homeController', 'homeAction' (for back link)
 *  'loginPostController', 'loginPostAction' (for form submission)
 *  'errors' (optional string, if login failed)
 *  'nickname' (when login failed)
 * @var callable $render
 * @var callable $htmlOut
 * @var callable $link
 * @var callable $beginForm
 * @var callable $endForm
 */

$render('partial/header', ['errors' => $data['errors'] ?? null,  'pageTitle' => $data['pageTitle'] ?? 'Login']);
?>
    <div class="vh-100-flex">
        <div class="app-container">
            <div class="d-flex align-items-center mb-4">
                <?php
                $link(
                    '<i class="bi bi-arrow-left"></i>',
                    $data['homeController'] ?? 'Home',
                    $data['homeAction'] ?? 'Index',
                    [],
                    'btn-back me-3',
                    false
                );
                ?>
                <h2 class="page-title flex-grow-1 text-center mb-0">Login</h2>
            </div>

            <?php if (isset($data['error_message'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php $htmlOut($data['error_message']); ?>
                </div>
            <?php endif; ?>

            <?php $beginForm($data['loginPostController'] ?? 'User', $data['loginPostAction'] ?? 'Login', [], 'POST'); ?>
            <div class="mb-3">
                <input type="text" class="form-control form-control-faceblog form-control-lg" name="nickname" placeholder="Enter nickname" value="<?php $htmlOut($data['nickname']);?>" required>
            </div>
            <div class="mb-4">
                <input type="password" class="form-control form-control-faceblog form-control-lg" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn btn-faceblog btn-lg w-100">Login</button>
            <?php $endForm(); ?>
        </div>
    </div>
<?php
$render('partial/footer', []);
?>