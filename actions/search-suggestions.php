<?php
require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: application/json');

$q = trim($_GET['q'] ?? '');
if (strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

$repo    = new ProductRepository();
$results = $repo->search($q);
$output  = [];

foreach (array_slice($results, 0, 5) as $p) {
    $output[] = [
        'id'    => (int)$p['id'],
        'name'  => $p['name'],
        'price' => formatPrice((float)$p['price']),
        'image' => $p['image'],
        'url'   => 'search.php?q=' . urlencode($p['name']),
    ];
}

echo json_encode($output);
