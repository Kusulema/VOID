<?php
// Debug page to inspect language selection behaviour
// Visit: http://localhost/VOID4/debug_lang.php and click the links
session_start();
$logDir = __DIR__ . '/debug';
if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
$logFile = $logDir . '/lang_debug.log';

$allowedLangs = ['en','ru','et'];
// Mirror the same priority used by layout.php (GET -> session -> cookie -> default)
if (!empty($_GET['lang']) && in_array($_GET['lang'], $allowedLangs, true)) {
    $langCode = $_GET['lang'];
    $_SESSION['lang'] = $langCode;
    setcookie('site_lang', $langCode, time() + (3600 * 24 * 365), '/');
} elseif (!empty($_SESSION['lang']) && in_array($_SESSION['lang'], $allowedLangs, true)) {
    $langCode = $_SESSION['lang'];
} elseif (!empty($_COOKIE['site_lang']) && in_array($_COOKIE['site_lang'], $allowedLangs, true)) {
    $langCode = $_COOKIE['site_lang'];
} else {
    $langCode = 'en';
}

// Build simple nav fragment
$basePath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$build = function($code) use ($basePath) {
    $q = http_build_query(array_merge($_GET, ['lang' => $code]));
    return $basePath . ($q ? '?' . $q : '');
};

$nav = sprintf('<div class="lang-under-brand">\n');
foreach ($allowedLangs as $c) {
    $active = $langCode === $c ? ' active' : '';
    $nav .= sprintf('  <a class="lang-link%s" href="%s" data-lang="%s">%s</a>\n', $active, htmlspecialchars($build($c)), $c, strtoupper($c));
}
$nav .= "</div>\n";

// Write log entry
$entry = date('c') . " | URI=" . ($_SERVER['REQUEST_URI'] ?? '-') . " | GET=" . json_encode($_GET) . " | SESSION=" . json_encode($_SESSION) . " | COOKIE=" . json_encode($_COOKIE) . "\n";
@file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);

?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>LANG DEBUG</title>
  <style>body{font-family:Inter,Arial,Helvetica,sans-serif;background:#111;color:#eee;padding:18px} .lang-link{display:inline-block;padding:8px 12px;border-radius:6px;margin-right:6px;background:#222;border:1px solid #600;color:#ddd;text-decoration:none} .lang-link.active{background:#ff4d4d;color:#fff;border-color:#ff4d4d;box-shadow:0 0 10px rgba(255,77,77,0.35)}</style>
</head>
<body>
  <h2>Language debug</h2>
  <p>Server-chosen language: <strong><?php echo htmlspecialchars($langCode); ?></strong></p>
  <p>Session value: <code>$_SESSION['lang'] = <?php echo htmlspecialchars(isset($_SESSION['lang']) ? $_SESSION['lang'] : ''); ?></code></p>
  <p>Cookie value: <code>site_lang = <?php echo htmlspecialchars($_COOKIE['site_lang'] ?? ''); ?></code></p>

  <h3>Navigation fragment (server-rendered)</h3>
  <?php echo $nav; ?>

  <h3>Quick links</h3>
  <ul>
    <li><a href="/VOID4/">/VOID4/ (root)</a></li>
    <li><a href="/VOID4/?lang=en">?lang=en</a></li>
    <li><a href="/VOID4/?lang=ru">?lang=ru</a></li>
    <li><a href="/VOID4/?lang=et">?lang=et</a></li>
  </ul>

  <h3>Server log (last 40 lines)</h3>
  <pre style="background:#000;color:#9f9;padding:10px;max-height:320px;overflow:auto"><?php
    if (is_readable($logFile)) {
        $lines = explode("\n", trim(@file_get_contents($logFile)));
        $tail = array_slice($lines, -40);
        echo htmlspecialchars(implode("\n", $tail));
    } else {
        echo "(no log yet)";
    }
  ?></pre>

  <p>Open these links and after each load return here: the server will append a debug line to <code>debug/lang_debug.log</code>.</p>
</body>
</html>