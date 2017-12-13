<?php

/**
 * Config file
 *
 * @package Core-o-Graphy
 */
 
/** $production boolean  Defines if the application is 
                         in production */
$production = false;


/** $base_url String Defines the base_path of the application */
$base_url = '//';


/** $dsn String Database connection string */
$dsn = 'mysql:host=localhost;dbname=;charset=utf8mb4';


/** $user String Database user */
$user='';


/** $password String Database password */
$password='';


/** $email_server String The email server */
$email_server = '';


/** $email_port String The port of the email server */
$email_port = '';


/** $email_protocol String The protocol of the mail server*/
$email_protocol = 'tls';


/** $email_username String The user of the email account*/
$email_username = '';


/** $email_password String The password of the email account*/
$email_password = '';


/** $email_from String The 'from' account */
$email_from = '';


// Google Maps
define ('GOOGLE_API_KEY', '');


// Github
/** $github_endpoint String */
define ('GITHUB_END_POINT', 'https://api.github.com/graphql');


/** $github_access_token String */
define ('GITHUB_ACCESS_TOKEN', '');


/** $github_client_id String */
define ('GITHUB_CLIENT_ID', '');


/** $github_client_secret String */
define ('GITHUB_CLIENT_SECRET', '');
