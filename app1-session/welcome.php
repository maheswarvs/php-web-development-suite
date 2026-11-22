<?php
declare(strict_types=1);
require __DIR__ . '/../includes/layout.php';
session_start();

if (!isset($_SESSION['name'], $_SESSION['start'])) { header('Location: index.php'); exit; }

$name  = $_SESSION['name'];
$start = (int)$_SESSION['start'];
$meta  = '<div class="meta">Session started<br><b>' . e(date('h:i:s A', $start)) . '</b></div>';

page_head('Welcome', '../', '1', $meta);
?>
<div class="stage">
  <h1>Hello, <?= e($name) ?>! Welcome back.</h1>
  <p class="lead">You're signed in. The timer counts in your browser; the final figure is calculated by PHP when you log out.</p>
  <div class="stats">
    <div class="panel"><small>Time on site</small><strong id="timer" data-start="<?= $start ?>">00:00:00</strong></div>
    <div class="panel"><small>Started on</small><strong><?= e(date('d M Y', $start)) ?></strong></div>
  </div>
  <form method="post" action="logout.php" style="margin-top:2rem">
    <input type="hidden" name="csrf" value="<?= e((string)($_SESSION['csrf'] ?? '')) ?>">
    <button class="btn" type="submit">Log out</button>
  </form>
</div>
<?php page_foot('<script>
const el=document.getElementById("timer"),start=+el.dataset.start*1000,p=n=>String(n).padStart(2,"0");
function tick(){const s=Math.max(0,Math.floor((Date.now()-start)/1000));el.textContent=p(Math.floor(s/3600))+":"+p(Math.floor(s%3600/60))+":"+p(s%60)}
tick();setInterval(tick,1000);
</script>');
