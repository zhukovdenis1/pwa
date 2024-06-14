<?php
// phpcs:ignoreFile

declare(strict_types=1);

$request = getRequest();
$db = getDb();
$session = getSession();

if ($request->post('submit')) {
    $id = (int) $request->post('id') ?: 0;
    $data['text'] = $request->post('text') ?: '';
    $data['source'] = $request->post('source') ?: '';

    $session->set('raw_source', ['value' => $data['source'], 'created_at' => time()]);

    if (validateWordForm($data)) {
        $db = getDb();

        if ($id) {
            $db->edit('raw', $data, 'WHERE id = ' . $id);
        } else {
            $db->save('raw', $data);
        }
    }
}

$app = getApp();
$app->redirect('/raw');

function validateWordForm(array $data): bool
{
    $result = true;
    if (empty($data['text'])) {
        $result = false;
    }

    return $result;
}
