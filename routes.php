<?php

// Home page
$router->map ('GET', '/', function () {

    // Load the classifier page
    require __DIR__ . '/controllers/pullrequests/Index.php';
    return new Index ();
    
});


