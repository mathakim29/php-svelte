<?php

$user = R::dispense('user');

Flight::route('GET /api/user', function () {
    Flight::json(['success' => true, 'users' => R::exportAll(R::findAll('user', 'ORDER BY id DESC'))]);
});

Flight::route('POST /api/user', function () {
    global $user;
    $request = Flight::request();
    $data = json_decode($request->getBody(), true);
    if (!is_array($data)) $data = ['name' => $request->data->name ?? null, 'email' => $request->data->email ?? null];
    $record = clone $user;
    $record->name = $data['name'] ?? '';
    $record->email = $data['email'] ?? '';
    Flight::json(['success' => true, 'id' => R::store($record)]);
});
