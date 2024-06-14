<?php

declare(strict_types=1);

$db = getDb();
$request = getRequest();

$result = $db->getOne('SELECT count(*) AS c FROM `raw` WHERE `deleted_at` IS NULL');
$amountRaw = $result['c'] ?? 0;
$result = $db->getAll(
    'SELECT count(*) AS c, `category_id` FROM `word` WHERE `next_exercise_at` < NOW() GROUP BY `category_id`'
);
$amountWord = [];
$amountWordTotal = 0;
foreach ($result as $r) {
    $amountWord[$r['category_id']] = $r['c'];
    $amountWordTotal += $r['c'];
}

include(dirname(__FILE__) . '/main.tpl');
