<?php
declare(strict_types=1);
require __DIR__ . '/../includes/layout.php';
session_start();

if (isset($_SESSION['name'])) { header('Location: welcome.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string)($_POST['name'] ?? ''));
    if (!hash_equals($_SESSION['csrf'] ?? '', (string)($_POST['csrf'] ?? ''))) {
        $error = 'Your form expired. Please try again.';
    } elseif (!preg_match('/^[\p{L}][\p{L} .\'-]{0,39}$/u', $name)) {
        $error = 'Use 1–40 letters. Spaces, hyphens and apostrophes are fine.';
    } else {
        session_regenerate_id(true);               // prevents session fixation
        $_SESSION['name']  = $name;
        $_SESSION['start'] = time();               // login time (Unix timestamp)
        $_SESSION['csrf']  = bin2hex(random_bytes(16));
        header('Location: welcome.php');
        exit;
    }
}
$_SESSION['csrf'] = $_SESSION['csrf'] ?? bin2hex(random_bytes(16));

page_head('Sign in', '../', '1');
?>
<div class="stage">
  <h1>Tell us who's visiting.</h1>
  <p class="lead">Your name starts a session on the server. Nothing is kept after you log out.</p>
  <form class="panel" method="post" novalidate>
    <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
    <label>Your name
      <input name="name" value="<?= e((string)($_POST['name'] ?? '')) ?>" autocomplete="given-name" maxlength="40" required autofocus
             <?= $error ? 'aria-invalid="true" aria-describedby="err"' : '' ?>>
      <?php if ($error): ?><span class="err" id="err"><?= e($error) ?></span><?php endif; ?>
    </label>
    <button class="btn" type="submit">Start session</button>
  </form>
</div>
<?php page_foot();
