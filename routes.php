<?php

// Home page
$router->map ('GET', '/[:owner]/[:user]/[i:how_many]?/[download:action]?', function ($owner, $user, $how_many=null, $action=false) {

    // Load the classifier page
    require __DIR__ . '/controllers/pullrequests/Index.php';
    return new Index ($owner, $user, $how_many, $action);
    
});

