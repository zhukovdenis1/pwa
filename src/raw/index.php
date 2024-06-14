<?php

declare(strict_types=1);

$app = getApp();
$db = getDb();
$request = getRequest();
$session = getSession();
$template = getTemplate();

$rawId = (int) $request->get('id');
$deleteId = (int) $request->get('delete');

$template->addBrcr('Сырые данные', '/raw');

if ($rawId) {
    $formData = $db->getOne('SELECT * FROM raw WHERE id = ?', [$rawId]);
    $template->addBrcr('Редактирование', '/raw?id=' . $rawId);
} elseif ($deleteId) {
    $db->edit('raw', ['deleted_at' => 'NOW()'], 'WHERE `id`=' . $deleteId);
    $app->redirect('/raw');
} else {
    $sourceCached = $session->get('raw_source');
    $cachedTime = $sourceCached['created_at'] ?? 0;
    if (time() - $cachedTime < 60 * 60) {
        $formData['source'] = $sourceCached['value'] ?? 0;
    } else {
        $formData['source'] = '';
    }
}

$data = $db->getAll('SELECT * FROM `raw` WHERE `deleted_at` IS NULL ORDER BY `id` DESC');

include(dirname(__FILE__) . '/index.tpl');
