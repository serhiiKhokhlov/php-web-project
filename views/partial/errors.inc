<div class="alert alert-danger">
  <p>
    Please correct the following and try again:
  </p>
  <ul>
      <?php /** @var array $data The array of errors */
    foreach ($data as $errMsg): ?>
        <li><?php /** @var callable $htmlOut */
          $htmlOut($errMsg); ?></li>
    <?php endforeach; ?>
  </ul>
</div>