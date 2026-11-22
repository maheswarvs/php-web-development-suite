<?php
declare(strict_types=1);
require __DIR__ . '/includes/layout.php';
page_head('Home', '');
?>
<div class="stage">
  <h1>Three small PHP applications, built properly.</h1>
  <p class="lead">Sessions, server time and input validation. Each app does one job and shares one design language.</p>
  <div class="suite">
    <a href="app1-session/"><span class="n">1</span><div><h2>Session welcome and logout</h2><p>Personal greeting, live session timer, and a summary of your visit when you leave.</p></div><span class="tag">Sessions</span></a>
    <a href="app2-clock/"><span class="n">2</span><div><h2>Date, time and day</h2><p>The server's clock from PHP next to a live clock from JavaScript.</p></div><span class="tag">date() + JS</span></a>
    <a href="app3-access/"><span class="n">3</span><div><h2>Age-based access control</h2><p>Enter your name and age. The server decides whether you get in.</p></div><span class="tag">Validation</span></a>
  </div>
</div>
<?php page_foot();
