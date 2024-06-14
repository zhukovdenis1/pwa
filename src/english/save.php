<?php
// phpcs:ignoreFile

declare(strict_types=1);

$request = getRequest();
$db = getDb();
$session = getSession();

if ($request->post('submit')) {
    $id = (int) $request->post('id') ?: 0;
    $data['important'] = (int) $request->post('important') ?: 0;
    $data['category_id'] = (int) $request->post('category_id') ?: 0;
    $data['forward'] = $request->post('forward') ?: '';
    $data['backward'] = $request->post('backward') ?: '';
    $data['description'] = $request->post('description') ?: '';
    $data['source_id'] = $request->post('source_id') ?: '';
    $data['session_id'] = $request->post('session_id') ?: '';
    $data['help'] = $request->post('help') ?: '';
    $tags = explode(',', $request->post('tags'));
    $data['tags'] =  $tags ? implode(',', $tags) : '';

    $session->set('word_source.' . $data['category_id'], ['value' => $data['source_id'], 'created_at' => time()]);
    $session->set('word_session.' . $data['category_id'], ['value' => $data['session_id'], 'created_at' => time()]);

    if (validateWordForm($data)) {
        $db = getDb();

        if ($id) {
            $db->edit('word', $data, 'WHERE id = ' . $id);
        } else {
            $data['next_exercise_at'] = getNextExerciseDate($data);
            $db->save('word', $data);

            if ($data['session_id']) {
                $db->edit('word_session', ['finished_at' => 'NOW()'], 'WHERE id = ' . $data['session_id']);
            }
        }
    }
}

$app = getApp();
$app->redirect('/english?category_id=' . $request->request('category_id'));

function validateWordForm(array $data): bool
{
    $result = true;
    if (empty($data['forward'])) {
        $result = false;
    }

    return $result;
}
