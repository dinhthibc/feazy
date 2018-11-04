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

# View
### Render layout
This section will help you to structure your view files with 1 layout file
Let's have files structure like this
```
.
├── index.php
├── src
│   └── Application
│      └── Controller
│         └── IndexController.php
├── template
│   └── layout
|      └── layout.phtml
│   └── index
|      └── index.phtml
└── vendor
```
| Name | Description |
| ------ | ------ |
| `index.php` | Main application |
| `IndexController.php` | Controller for homepage |
| `layout/layout.phtml` | Layout file |
| `index/index.phtml`| Child page file|

Put content for each file with following code:
index.php
```php
<?php
define('ROOT_PATH', __DIR__);

$loader = require ROOT_PATH . '/vendor/autoload.php';
$loader->addPsr4('', ROOT_PATH . '/src');

$router = new \Feazy\Common\Router($_SERVER['REQUEST_URI']);
\Feazy\Common\DIManager::add('view', new \Feazy\Common\View('template'));

$router->get('/', 'IndexController@index');
?>
```
IndexController.php
```php 
<?php
namespace Application\Controller;
use Feazy\Controller\Controller;
class IndexController extends Controller
{
	public function index() {
		$this->view->setTitle('Homepage');
		$this->view->render('index/index', [
			'myVariable' => 'This is a variable value'
		]);
	}
}
```
layout.phtml
```php
<html>
<head>
	<!--- css/meta -->
	<title><?php echo $this->title; ?></title>
</head>
<h3>Layout title</h3>
<div class="content">
	<?php require_once ($this->content); ?>
</div>
</html>
```
index.phtml
```php
myVariable => <?php echo $myVariable; ?>
```

When you run website on browser, you will get this result
```
Layout title
myVariable => This is a variable value
```

### Render view section
Manage your common section in multiple child pages
Now we have some extra files in directories
```
.
├── index.php
├── src
├── template
│   └── layout
│   └── index
|      └── index.phtml
│   └──  sections
│      └── component1.phtml
│      └── component2.phtml
└── vendor
```
Update index.phtml code
```php
<div>
	<?php $this->addSection('sections/component1'); ?>
</div>
<div>
	<?php $this->addSection('sections/component2', ['myVariable']); ?>
</div>
```
component1.phtml
```
This is component 1 content
```
component2.phtml
```php
myVariable => <?php echo $myVariable; ?>
```

### View variables
Help us to expose a variable from Controller to View
```php
class IndexController extends Controller
{
	public function index() {
		$this->view->setTitle('Homepage');
		$this->view->render('index/index', [
			'myVariable' => 'This is a variable value'
		]);
	}
}
```
In view child page just call block below to get `$myVariable` value
```php
<?php echo $myVariable; ?>
```

.... to be continued

