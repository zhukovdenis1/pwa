<?php

declare(strict_types=1);

$app = getApp();
$db = getDb();
$request = getRequest();
$session = getSession();
$template = getTemplate();

$categoryId = (int) $request->request('category_id', 0);
$wordId = (int) $request->get('id');

if ($wordId) {
    $formData = $db->getOne('SELECT w.* FROM `word` w  WHERE w.id = ?', [$wordId]);
    $categoryId = $formData['category_id'];
} else {
    $formData = ['important' => 1];
    $sourceCached = $categoryId ? ($session->get('word_source.' . $categoryId) ?: '') : '';
    $sessionCached = $categoryId ? ($session->get('word_sessuib.' . $categoryId) ?: '') : '';

    $cachedTime = $sourceCached['created_at'] ?? 0;
    if (time() - $cachedTime < 60 * 60) {
        $formData['source_id'] = $sourceCached['value'] ?? 0;
    } else {
        $formData['source_id'] = 0;
    }

    $cachedTime = $sessionCached['created_at'] ?? 0;
    if (time() - $cachedTime < 60 * 60) {
        $formData['session_id'] = $sessionCached['value'] ?? 0;
    } else {
        $formData['session_id'] = 0;
    }
}

$category = $db->getOne('SELECT * FROM `word_category` WHERE `id`=?', [$categoryId]);

$template->addBrcr($category['name'], '/english?category_id=' . $categoryId);

if ($wordId) {
    $template->addBrcr('Редактирование', '/english?id=' . $wordId);
}


$sourceData = $db->getOne('SELECT name FROM `word_source` WHERE `id` = ?', [$formData['source_id']]);
$sessionData = $db->getOne('SELECT name FROM `word_session` WHERE `id` = ?', [$formData['session_id']]);

if ($sourceData) {
    $formData['source_name'] = $sourceData['name'];
}

if ($sessionData) {
    $formData['session_name'] = $sessionData['name'];
}



if ($category) {
    $cfg = json_decode($category['config'], true);

    $data = $db->getAll('SELECT * FROM `word` WHERE `category_id`=? ORDER BY `id` DESC', [$category['id']]);

    $tags = $db->getAll(
        'SELECT *, 0 as `selected` 
                FROM `word_tag` 
                WHERE FIND_IN_SET(?,`category_ids`) 
                ORDER BY `id` ASC',
        [$category['id']]
    );

    if ($wordId) {
        $selectedTags = explode(',', $formData['tags']);
        foreach ($tags as &$t) {
            if (in_array($t['id'], $selectedTags)) {
                $t['selected'] = 1;
            }
        }
    }

    include(dirname(__FILE__) . '/index.tpl');
} else {
    $app->redirect('/');
}
