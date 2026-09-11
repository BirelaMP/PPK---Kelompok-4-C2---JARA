<?php

test('the application redirects root to tasks', function () {
    $response = $this->get('/');

    $response->assertRedirect('/tasks');
});
