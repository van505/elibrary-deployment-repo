<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=ebook_library_system', 'root', '');
$stmt = $pdo->query("SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE, COLUMN_KEY, IS_NULLABLE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'ebook_library_system' ORDER BY TABLE_NAME, ORDINAL_POSITION");
$tables = [];
foreach ($stmt as $row) {
    $tables[$row['TABLE_NAME']][] = $row;
}

$fkeys_stmt = $pdo->query("SELECT TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'ebook_library_system' AND REFERENCED_TABLE_NAME IS NOT NULL");
$fkeys = [];
foreach ($fkeys_stmt as $row) {
    $fkeys[] = $row;
}

echo json_encode(['tables' => $tables, 'fkeys' => $fkeys], JSON_PRETTY_PRINT);
