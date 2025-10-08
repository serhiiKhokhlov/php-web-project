<?php
/**
 * @var array $data Expects:
 *  'pageTitle' (string)
 *  'stats' (array with keys: 'already_using', 'publications_made', 'publications_last_24h', 'last_contribution_on')
 *  'loginController', 'loginAction'
 *  'registerController', 'registerAction'
 * @var callable $render
 * @var callable $htmlOut
 * @var callable $link
 */

$render('partial/header', ['pageTitle' => $data['pageTitle'] ?? 'Welcome to Faceblog']);
$stats = $data['stats'] ?? [];
?>
    <div class="vh-100-flex">
        <div class="app-container text-center">
            <h1 class="fw-bold display-5 mb-4">Welcome to<br>Faceblog!</h1>

            <div class="mb-4">
                <div class="stat-item">
                    <span class="stat-label">already using:</span>
                    <span class="stat-value"><?php $htmlOut($stats['already_using'] ?? 'N/A'); ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">publications made:</span>
                    <span class="stat-value"><?php $htmlOut($stats['publications_made'] ?? 'N/A'); ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">publications for the last 24 hours:</span>
                    <span class="stat-value"><?php $htmlOut($stats['publications_last_24h'] ?? 'N/A'); ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">last contribution on:</span>
                    <span class="stat-value"><?php $htmlOut($stats['last_contribution_on'] ?? 'N/A'); ?></span>
                </div>
            </div>

            <?php $link('Login', $data['loginController'] ?? 'User', $data['loginAction'] ?? 'Login', [], 'btn btn-faceblog btn-lg w-100 mb-3'); ?>
            <?php $link('New account', $data['registerController'] ?? 'User', $data['registerAction'] ?? 'Register', [], 'btn btn-faceblog btn-lg w-100'); ?>
        </div>
    </div>
<?php
$render('partial/footer', []);
?>