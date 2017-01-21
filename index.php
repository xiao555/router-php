<?php
session_start();
include __DIR__ . '/vendor/autoload.php';
require 'Models/database.php';

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;

$router = new RouteCollector();

function render ($template, array $data) {
  extract($data);
  if(file_exists($file = __DIR__ . '/Views//' . $template )) {
    require $file;
  }
}

function redirect($template) {
  header('Location: ' . $template);
}

$router->get('/', function() use ($database){
  if( isset($_SESSION['user'])) {
    $results = current($database->select("users", [
      "user",
      "password"
    ], [
      "user" => $_SESSION['user']
    ]));
    $user = $$results['user'];
  } else {
    $user = null;
  }
  render('home.html', array(
    'title' => 'Hello world!',
    'user' => $user
  ));
});

$router->post('/', function() use ($database){
  $user = null;
  if(!empty($_POST['user'])&&!empty($_POST['password'])) {
    $results = current($database->select("users", [
      "user",
      "password"
    ], [
      "user" => $_POST['user']
    ]));
    $message = '';

    if(count($results) > 0){
      if(password_verify($_POST['password'], $results['password'])){
        $user = $results['user'];
        $_SESSION['user'] = $results['user'];
      } else {
        $message = 'Sorry,  your password is wrong!';
      }
    } else {
      $message = "Sorry, user gon't exist!";
    }
  }
  render('home.html', array(
    'title' => 'login',
    'user' => $user,
    'message' => $message
  ));
});

$router->get('register', function(){
  render('register.html', array(
    'title' => 'Register'
  ));
});

$router->post('register', function() use ($database){
  if(!empty($_POST['user'])&&!empty($_POST['password'])) {
    if($_POST['password'] != $_POST['confirm']) {
      $message = "Confirm Password Error!";
    } else {
      $result_id = $database->insert("users", [
        "user" => $_POST['user'],
        "password" => password_hash($_POST['password'], PASSWORD_DEFAULT)
      ]);
      var_dump($result_id);
      if($result_id ){
        $message = 'Successful Register!';
      } else {
        $message = "Sorry, Register Failure!";
      }
    }
  }
  render('register.html', array(
    'title' => 'Register',
    'message' => $message
  ));
});

$router->get('logout', function(){
    session_destroy();
    redirect('/');
});

$dispatcher = (new Dispatcher($router->getData()));
$response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));