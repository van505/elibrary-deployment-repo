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

$mermaid = "erDiagram\n";

foreach ($tables as $tableName => $columns) {
    $mermaid .= "    {$tableName} {\n";
    foreach ($columns as $col) {
        $type = str_replace(' ', '_', $col['DATA_TYPE']);
        $name = $col['COLUMN_NAME'];
        $key = '';
        if ($col['COLUMN_KEY'] === 'PRI') $key = 'PK';
        else if ($col['COLUMN_KEY'] === 'UNI') $key = 'UK';
        else if ($col['COLUMN_KEY'] === 'MUL') $key = 'FK';
        
        $mermaid .= "        {$type} {$name} {$key}\n";
    }
    $mermaid .= "    }\n";
}

$mermaid .= "\n";

foreach ($fkeys as $fk) {
    $mermaid .= "    {$fk['REFERENCED_TABLE_NAME']} ||--o{ {$fk['TABLE_NAME']} : \"{$fk['COLUMN_NAME']}\"\n";
}

echo $mermaid;
