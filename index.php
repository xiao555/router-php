<?php
session_start();
include __DIR__ . '/vendor/autoload.php';
define('ROOTPATH', __DIR__ . '/web/build/');

$database = new medoo([
    'database_type' => 'mysql',
    'database_name' => 'login',
    'server' => 'localhost',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8'
]);

Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./web/');
$twig = new Twig_Environment($loader, array(
    'cache' => false
));

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;

$router = new RouteCollector();

function render ($template, array $data){
  global $twig;
  if(file_exists($file = __DIR__ . '/web/views/' . $template )) {
    // require $file;
    echo $twig->render($template, $data);
  }
}

function redirect($template) {
  header('Location: ' . $template);
}

$router->get('/', function() use ($database){
  $user = null;
  if( !empty($_COOKIE['xiao555name'])) {
    $results = current($database->select("users", [
      "user",
      "password"
    ], [
      "user" => $_COOKIE['xiao555name']
    ]));
    if( password_verify($_COOKIE['xiao555pw'], $results['password']) ) {
      $user = $results['user'];
      return render('home.html', array(
        'title' => 'Welcome!',
        'user' => $user
      ));
    };
  }
  render('login.html', array(
    'title' => 'Login in',
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
        // Remember Password
        if(isset($_POST['remember'])) {
          setcookie('xiao555name',$results['user'],time()+3600*24);
          setcookie('xiao555pw',$_POST['password'],time()+3600*24);
        }
      } else {
        $message = 'Sorry,  your password is wrong!';
      }
    } else {
      $message = "Sorry, user don't exist!";
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
    setcookie('xiao555name',null,time()-3600*24);
    setcookie('xiao555pw',null,time()-3600*24);
    redirect('/');
});

$dispatcher = (new Dispatcher($router->getData()));
$url = explode('/', $_SERVER['REQUEST_URI']);
$request = '/' . $url[count($url) - 1];
$response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $request);