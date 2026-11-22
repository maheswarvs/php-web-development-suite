<?php
declare(strict_types=1);
require __DIR__ . '/../includes/layout.php';
session_start();

// Logout only via POST with a valid CSRF token, so a stray link can't sign someone out.
if ($_SERVER['REQUEST_METHOD'] !== 'POST'
    || !isset($_SESSION['name'], $_SESSION['start'])
    || !hash_equals($_SESSION['csrf'] ?? '', (string)($_POST['csrf'] ?? ''))) {
    header('Location: index.php'); exit;
}

$name    = $_SESSION['name'];
$seconds = max(0, time() - (int)$_SESSION['start']);   // total time calculated in PHP

// Destroy the session securely: clear data, expire the cookie, destroy the server-side session.
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $c = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $c['path'], $c['domain'], $c['secure'], $c['httponly']);
}
session_destroy();

$h = intdiv($seconds, 3600); $m = intdiv($seconds % 3600, 60); $s = $seconds % 60;
$parts = array_filter([$h ? "$h hr" : '', ($h || $m) ? "$m min" : '', "$s sec"]);

header('Cache-Control: no-store');
page_head('Signed out', '../', '1');
?>
<div class="stage">
  <div class="panel verdict">
    <h1>Thank you, <?= e($name) ?>.</h1>
    <p class="lead">You've been signed out and your session data has been deleted.</p>
    <div class="stats">
      <div class="panel"><small>Visitor</small><strong><?= e($name) ?></strong></div>
      <div class="panel"><small>Total time on site</small><strong><?= e(implode(' ', $parts)) ?></strong></div>
    </div>
    <p style="margin-top:2rem"><a class="btn" href="index.php">Start a new session</a></p>
  </div>
</div>
<?php page_foot();
