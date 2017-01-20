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
$router->get('/', function(){
  if( isset($_SESSION['user'])) {
    require 'Models/database.php';
    $records = $db->prepare('SELECT user,password FROM users WHERE user = :user');
    $records->bindParam(':user', $_SESSION['user']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);
    $user = $results['user'];
  } else {
    $user = null;
  }
  render('home.html', array(
    'title' => 'Hello world!',
    'user' => $user
  ));
});

$router->post('/', function(){
  $user = null;
  if(!empty($_POST['user'])&&!empty($_POST['password'])) {
    require 'Models/database.php';
    $records = $db->prepare('SELECT user,password FROM users WHERE user = :user');
    $records->bindParam(':user', $_POST['user']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);
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

$router->post('register', function(){
  if(!empty($_POST['user'])&&!empty($_POST['password'])) {
    if($_POST['password'] != $_POST['confirm']) {
      $message = "Confirm Password Error!";
    } else {
      require 'Models/database.php';
      $records = $db->prepare("INSERT into  users(user,password) VALUES (:user, :password)");
      $records->bindParam(':user', $_POST['user']);
      $records->bindParam(':password', password_hash($_POST['password'], PASSWORD_DEFAULT));
      if($records->execute()){
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