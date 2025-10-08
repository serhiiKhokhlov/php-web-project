<?php
/**
 * @var array $data Expects:
 *  'pageTitle' (string)
 *  'formActionController', 'formActionName' (for form submission)
 *  'myBlogController', 'myBlogAction' (for back button)
 * @var callable $render
 * @var callable $beginForm
 * @var callable $endForm
 * @var callable $link
 */

$render('partial/header', ['pageTitle' => $data['pageTitle'] ?? 'New Post']);
?>
    <div class="app-container">
        <?php $beginForm($data['formActionController'] ?? 'Blog', $data['formActionName'] ?? 'NewPublication', [], 'POST'); ?>
        <div class="mb-3">
            <input type="text" class="form-control form-control-faceblog form-control-lg" name="title" placeholder="Enter title" required>
        </div>
        <div class="mb-4">
            <textarea class="form-control form-control-faceblog" name="content" rows="10" placeholder="Enter the text..." required></textarea>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <?php
            $link(
                '<i class="bi bi-arrow-left"></i>',
                $data['myBlogController'] ?? 'Blog',
                $data['myBlogAction'] ?? 'Mine',
                [],
                'btn btn-light btn-round-action shadow-sm',
                false
            );
            ?>
            <button type="submit" class="btn btn-faceblog btn-round-action shadow-sm">
                <i class="bi bi-check-lg"></i>
            </button>
        </div>
        <?php $endForm(); ?>
    </div>
<?php
$render('partial/footer', []);
?>