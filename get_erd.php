<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tables = collect(DB::select('SHOW TABLES'))->map(fn($val) => array_values((array)$val)[0])->toArray();

$mermaid = "erDiagram\n";

foreach ($tables as $table) {
    if (in_array($table, ['migrations', 'password_reset_tokens', 'personal_access_tokens', 'sessions', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs'])) continue;
    
    $mermaid .= "    $table {\n";
    $columns = DB::select("SHOW COLUMNS FROM $table");
    
    foreach ($columns as $col) {
        $field = $col->Field;
        $type = preg_replace('/\(.*\)/', '', $col->Type);
        $type = preg_replace('/[^a-zA-Z0-9_]/', '', $type);
        
        $key = "";
        if ($col->Key == 'PRI') {
            $key = "PK";
        } elseif ($col->Key == 'MUL' || str_ends_with($field, '_id')) {
            $key = "FK";
        }
        
        $mermaid .= "        $type $field $key\n";
    }
    $mermaid .= "    }\n";
}

$dbName = DB::connection()->getDatabaseName();
$fkQuery = "
SELECT 
    TABLE_NAME, 
    COLUMN_NAME, 
    REFERENCED_TABLE_NAME, 
    REFERENCED_COLUMN_NAME 
FROM 
    information_schema.KEY_COLUMN_USAGE 
WHERE 
    TABLE_SCHEMA = '$dbName' 
    AND REFERENCED_TABLE_NAME IS NOT NULL
";
$fks = DB::select($fkQuery);

foreach ($fks as $fk) {
    if (in_array($fk->TABLE_NAME, $tables) && in_array($fk->REFERENCED_TABLE_NAME, $tables)) {
        $mermaid .= "    {$fk->REFERENCED_TABLE_NAME} ||--o{ {$fk->TABLE_NAME} : \"{$fk->COLUMN_NAME}\"\n";
    }
}

file_put_contents('database_erd.txt', $mermaid);
echo "Done";
