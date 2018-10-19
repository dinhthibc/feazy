# Feazy Framework
A simple & lightweight PHP framework to build Web application & RESTful API

## Installation
```sh
$ composer require dinhthibc/feazy
```

## Simple app
Add simple `index.php` file:
```php
<?php
define('ROOT_PATH', __DIR__);
$loader = require ROOT_PATH . '/vendor/autoload.php';

$router = new \Feazy\Common\Router($_SERVER['REQUEST_URI']);
$router->get('/', function() {
	echo 'Welcome to Feazy';
});
?>
```
Then run server
```sh
$ php -S localhost:8000
```
Open `localhost:8000` in web browser

## Simple app with controllers
Add folder
```sh
$ mkdir src/Application/Controller
```
Add `UserController` class to controller folder
```php
<?php

namespace Application\Controller;

use Feazy\Controller\Controller;

class UserController extends Controller
{
	public function index() {
		echo 'This is users page';
	}

	public function get($id) {
		echo "Get user details with id = $id";
	}
}
?>
```

And `index.php` file
```php
<?php
define('ROOT_PATH', __DIR__);

$loader = require ROOT_PATH . '/vendor/autoload.php';
$loader->addPsr4('', ROOT_PATH . '/src');

$router = new \Feazy\Common\Router($_SERVER['REQUEST_URI']);
$router->get('/', function() {
	echo 'Welcome to Feazy';
});

$router->group('/users', function(\Feazy\Common\Router $router) {
	$router->get('/', 'UserController@index');
	$router->get('/(\d+)', 'UserController@get');
});

?>
```
Then run server
```sh
$ php -S localhost:8000
```
Open `localhost:8000/users` or `localhost:8000/users/1` in web browser

## Router
This section will help you to under stand how to configure your router

Install new router
```php
$router = new \Feazy\Common\Router($_SERVER['REQUEST_URI']);
```

You can use basic route like this:
```php
$router = new \Feazy\Common\Router($_SERVER['REQUEST_URI']);
$router->basicRoute();
```
| Route | Controller |
| ------ | ------ |
|`/`| `Application/Controller/IndexController@index` |
|`/user`| `Application/Controller/UserController@index` |
|`/user/edit/1`| `Application/Controller/UserController@edit` with first parameter is `1` |

Or define your own routes like this
```php
$router = new \Feazy\Common\Router($_SERVER['REQUEST_URI']);
$router->group('/users', function(\Feazy\Common\Router $router) {
	$router->get('/', 'UserController@index');
	$router->get('/(\d+)', 'UserController@get'); //using regex
});

$router->group('/api', function(\Feazy\Common\Router $router) {
	$router->setPrefix('\\Application\\API\\'); //set namespace for controller
	$router->get('/users', 'UserController@index');
});
```
| Route | Controller |
| ------ | ------ |
|`/users`| `Application/Controller/UserController@index` |
|`/users/get/1`| `Application/Controller/UserController@get` with first parameter is `1` |
|`/api/users`| `Application/API/UserController@index` |