<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Community;

$communities = Community::select('id', 'name', 'municipality', 'province', 'status')
    ->orderBy('name')
    ->get();

echo "\n============================================\n";
echo "      COMMUNITIES - READABLE FORMAT\n";
echo "============================================\n\n";

foreach ($communities as $c) {
    echo sprintf("[%d] %s\n", $c->id, $c->name);
    echo sprintf("    📍 %s, %s\n", $c->municipality, $c->province);
    echo sprintf("    Status: %s\n\n", strtoupper($c->status));
}

echo "\n============================================\n";
echo "      CSV FORMAT FOR JOTFORM IMPORT\n";
echo "============================================\n\n";

echo "Value,Label\n";
foreach ($communities as $c) {
    echo sprintf('"%d","%s (%s, %s)"%s', 
        $c->id, 
        $c->name, 
        $c->municipality, 
        $c->province,
        "\n"
    );
}

echo "\n\nTotal Communities: " . $communities->count() . "\n\n";
