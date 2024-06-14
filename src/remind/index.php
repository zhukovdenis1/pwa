<?php

declare(strict_types=1);

$db = getDb();
$request = getRequest();
$template = getTemplate();

$template->addBrcr('Тестирование', '/remind');

$categoryId = (int) $request->get('category_id');

if ($request->post('submit')) {
    $saveData['done'] = $request->post('done');
    $saveData['direction'] = $request->post('direction');
    $saveData['word_id'] = (int) $request->post('word_id');

    if (!is_null($saveData['done']) && $saveData['direction'] && $saveData['word_id']) {
        $db->save('exercise', $saveData);
        $word = $db->getOne('SELECT * FROM `word` WHERE id=?', [$saveData['word_id']]);
        if ($word) {
            $nextExerciseData = getNextExerciseDate($word, $saveData['done']);
            $help = $request->post('help');
            $saveResult = $db->edit(
                'word',
                ['next_exercise_at' => $nextExerciseData, 'help' => $help],
                'WHERE `id`=' . $saveData['word_id']
            );
        }
    }
}

$where = 'w.`next_exercise_at`<NOW()';
if ($categoryId) {
    $where .= " AND w.`category_id`=$categoryId";
}
$word = $db->getOne('SELECT w.*, s.name as source_name, se.name as session_name FROM `word` w
                           LEFT JOIN `word_source` s ON w.`source_id`=s.`id`
                           LEFT JOIN `word_session` se ON w.`session_id`=se.`id`
                           WHERE ' . $where . ' ORDER BY `next_exercise_at` ASC LIMIT 1');

if ($word) {
    $tagsResult = $db->getAll('SELECT * FROM `word_tag`');
    $category = $db->getOne('SELECT * FROM `word_category` WHERE id = ?', [$word['category_id']]);
    $tags = [];
    $tagsSelected = explode(',', $word['tags']);
    foreach ($tagsResult as $tag) {
        if (in_array($tag['id'], $tagsSelected)) {
            $tags[$tag['id']] = $tag['name'];
        }
    }

    $exerciseCountResult = $db->getOne(
        'SELECT COUNT(*) as `amount` 
                       FROM `exercise` 
                       WHERE `word_id` = ? AND `done`>3',
        [$word['id']]
    );

    $direction = $exerciseCountResult['amount'] % 2 ? 'backward' : 'forward';

    $exercises = $db->getAll(
        'SELECT * FROM `exercise` WHERE `word_id`=? ORDER BY `created_at` DESC',
        [$word['id']]
    );
}

include(dirname(__FILE__) . '/index.tpl');
