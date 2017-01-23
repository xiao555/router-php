<?php

// If you installed via composer, just use this code to requrie autoloader on the top of your projects.
// require 'vendor/autoload.php';

// Initialize
$database = new medoo([
    'database_type' => 'mysql',
    'database_name' => 'login',
    'server' => 'localhost',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8'
]);

// Enjoy
// $database->insert('account', [
//     'user_name' => 'foo',
//     'email' => 'foo@bar.com',
//     'age' => 25,
//     'lang' => ['en', 'fr', 'jp', 'cn']
// ]);

// $server = 'localhost';
// $username = 'root';
// $password = ''; // Yout Database root PASSWORD for localhost!
// $database = 'login';

// try{
//   $db = new PDO("mysql:host=$server;dbname=$database;", $username, $password);
// } catch(PDOException $e){
//   die("Connection failed: " .$e->getMessage());
// }