<?php
$c = file_get_contents(__DIR__ . '/routes/api.php');
$c = preg_replace("/Route::apiResource\('([a-z0-9\-]+)',\s*([A-Za-z0-9_\\\:]+)\);/", "Route::apiResource('$1', $2, ['as' => 'api']);", $c);
file_put_contents(__DIR__ . '/routes/api.php', $c);
echo "Done";
