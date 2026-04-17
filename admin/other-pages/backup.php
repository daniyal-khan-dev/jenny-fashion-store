<?php

$dbUser = "root";
$dbPass = "";
$dbName = "jenny_fashion_store";

// IMPORTANT: full path
$mysqldump = "C:\\xampp\\mysql\\bin\\mysqldump.exe";

$filename = 'jenny_fashion_store_backup_' . date('Y-m-d_H-i-s') . '.sql';

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

$cmd = "\"$mysqldump\" -u $dbUser $dbName";

passthru($cmd);
exit;