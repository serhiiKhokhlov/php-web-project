<?php
/**
 * @var array $data Expects:
 *  'pageTitle' (string)
 *  'homeController', 'homeAction' (for back link)
 *  'registerPostController', 'registerPostAction' (for form submission)
 * @var callable $render
 * @var callable $htmlOut
 * @var callable $link
 * @var callable $beginForm
 * @var callable $endForm
 */

$render('partial/header', ['errors' => $data['errors'] ?? null, 'pageTitle' => $data['pageTitle'] ?? 'New Account']);
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
                <h2 class="page-title flex-grow-1 text-center mb-0">New account</h2>
            </div>

            <?php $beginForm($data['registerPostController'] ?? 'User', $data['registerPostAction'] ?? 'Register', [], 'POST'); ?>
            <div class="mb-3">
                <input type="text" class="form-control form-control-faceblog form-control-lg" name="nickname" placeholder="Enter nickname" value="<?php $htmlOut($data['nickname']);?>" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control form-control-faceblog form-control-lg" name="password" placeholder="Enter password" value="<?php $htmlOut($data['password']);?>" required>
            </div>
            <div class="mb-4">
                <input type="password" class="form-control form-control-faceblog form-control-lg" name="repeat_password" placeholder="Repeat password" value="<?php $htmlOut($data['repeat_password']);?>" required>
            </div>
            <button type="submit" class="btn btn-faceblog btn-lg w-100">Create</button>
            <?php $endForm(); ?>
        </div>
    </div>
<?php
$render('partial/footer', []);
?>