<?php
declare(strict_types=1);
require __DIR__ . '/../includes/layout.php';
session_start();

const MIN_AGE = 18;   // access threshold; change here to adjust the rule

$errors = []; $result = null;
$name   = trim((string)($_POST['name'] ?? ''));
$ageRaw = trim((string)($_POST['age'] ?? ''));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'] ?? '', (string)($_POST['csrf'] ?? ''))) {
        $errors['form'] = 'Your form expired. Please try again.';
    }
    if (!preg_match('/^[\p{L}][\p{L} .\'-]{1,59}$/u', $name)) {
        $errors['name'] = 'Enter your full name using letters only (2–60 characters).';
    }
    $age = filter_var($ageRaw, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 120]]);
    if ($age === false) {
        $errors['age'] = 'Enter a whole number between 1 and 120.';
    }
    if (!$errors) {
        $result = ['name' => $name, 'age' => $age, 'granted' => $age >= MIN_AGE, 'wait' => max(0, MIN_AGE - $age)];
    }
}
$_SESSION['csrf'] = $_SESSION['csrf'] ?? bin2hex(random_bytes(16));

page_head('Access check', '../', '3');
?>
<div class="stage">
<?php if ($result): $r = $result; ?>
  <div class="panel verdict <?= $r['granted'] ? '' : 'denied' ?>" role="status">
    <?php if ($r['granted']): ?>
      <h1>Welcome in, <?= e($r['name']) ?>.</h1>
      <p class="lead">Access granted. At <?= (int)$r['age'] ?>, you meet the minimum age of <?= MIN_AGE ?>.</p>
    <?php else: ?>
      <h1>Sorry, <?= e($r['name']) ?>, not yet.</h1>
      <p class="lead">Access denied. This area is for visitors aged <?= MIN_AGE ?> or older. You can come back in <?= (int)$r['wait'] ?> year<?= $r['wait'] === 1 ? '' : 's' ?>.</p>
    <?php endif; ?>
    <p style="margin-top:2rem"><a class="btn ghost" href="./">Check another person</a></p>
  </div>
<?php else: ?>
  <h1>Check your access.</h1>
  <p class="lead">Visitors aged <?= MIN_AGE ?> or older can enter. The check runs on the server, so it can't be skipped in the browser.</p>
  <form class="panel" method="post" novalidate>
    <input type="hidden" name="csrf" value="<?= e((string)$_SESSION['csrf']) ?>">
    <?php if (isset($errors['form'])): ?><span class="err"><?= e($errors['form']) ?></span><?php endif; ?>
    <label>Full name
      <input name="name" value="<?= e($name) ?>" autocomplete="name" maxlength="60" required <?= isset($errors['name']) ? 'aria-invalid="true"' : '' ?>>
      <?php if (isset($errors['name'])): ?><span class="err"><?= e($errors['name']) ?></span><?php endif; ?>
    </label>
    <label>Age <span class="hint">in years</span>
      <input name="age" type="number" inputmode="numeric" min="1" max="120" value="<?= e($ageRaw) ?>" required <?= isset($errors['age']) ? 'aria-invalid="true"' : '' ?>>
      <?php if (isset($errors['age'])): ?><span class="err"><?= e($errors['age']) ?></span><?php endif; ?>
    </label>
    <button class="btn" type="submit">Check access</button>
  </form>
<?php endif; ?>
</div>
<?php page_foot();
