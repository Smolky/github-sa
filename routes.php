<?php

// Home page
$router->map ('GET', '/[:owner]/[:user]', function ($owner, $user) {

    // Load the classifier page
    require __DIR__ . '/controllers/pullrequests/Index.php';
    return new Index ($owner, $user);
    
});

