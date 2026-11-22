<?php
declare(strict_types=1);

function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

/** Renders the shared document head and top bar. $root is the relative path back to the suite root. */
function page_head(string $title, string $root, string $active = '', string $right = ''): void { ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> · PHP Interactive Suite</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Manrope:wght@400;600&display=swap">
<link rel="stylesheet" href="<?= $root ?>assets/style.css">
</head>
<body>
<header class="bar">
  <a class="brand" href="<?= $root ?>index.php">PHP Interactive Suite</a>
  <nav aria-label="Applications">
    <a href="<?= $root ?>app1-session/"<?= $active === '1' ? ' aria-current="page"' : '' ?>>Session</a>
    <a href="<?= $root ?>app2-clock/"<?= $active === '2' ? ' aria-current="page"' : '' ?>>Clock</a>
    <a href="<?= $root ?>app3-access/"<?= $active === '3' ? ' aria-current="page"' : '' ?>>Access</a>
  </nav>
  <?= $right ?>
</header>
<main>
<?php }

function page_foot(string $extra = ''): void { ?>
</main>
<footer>Built with PHP <?= e(PHP_VERSION) ?> and vanilla JavaScript. No frameworks.</footer>
<?= $extra ?>
</body>
</html>
<?php }
