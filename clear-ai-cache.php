<?php
use Illuminate\Database\Capsule\Manager as DB;

define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Clear AI summary cache for community 32
\Illuminate\Support\Facades\DB::table('community_assessment_summaries')
    ->where('community_id', 32)
    ->update([
        'ai_summary' => null,
        'ai_summary_generated_at' => null
    ]);

echo "Cache cleared successfully!\n";

