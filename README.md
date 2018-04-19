## PhoxEngine - Small, extendable php template engine.

**PhoxEngine** is a small, lightweight and easy to use php template engine. This small template engine can be used with any php project. All you have to do is install it or require it in your project

### Requirements:

**i.** PHP 7.0.6 or higher
**ii.** Apache/Nginx Server. 


### Installing PhoxEngine
If you are going to use this as a standalone library, simply run the composer command below:

~~~
    composer create-project phoxphp/phoxengine
~~~

And if you are going to use it in a project, simply require it in your composer.json file: 
~~~
	"require": {
		"phoxphp/phoxengine": "^1.0.0"
	}
~~~

### Available template functions
1. Extend
2. If
3. ElseIf
4. Raw
5. Include
6. Each
7. Getter
8. Setter
9. Cookie

### Basic Usage

To render a view, you need to use the \Kit\PhoxEngine\Renderer. The constructor of this object requires an instance of \Kit\PhoxEngine\Repository as it's argument.

```php
    include 'vendor/autoload.php';
    
    $repository = new \Kit\PhoxEngine\Repository();
    $renderer = new \Kit\PhoxEngine\Renderer($repository);
    echo $renderer->render();
```

### Kit\PhoxEngine\Repository methods

    1. setView(String $view) - Sets the view to be rendered.
    2. setPath(String $path) - Sets the templates path.
    3. setExtension(String $extension) - Sets the views extension.
    4. setVariable(String $variable, $value=null) - Sets a view variable.
    5. getView() - Returns the view to be rendered.
    6. getPath() - Returns templates path.
    7. getExtension() - Returns view extension.
    8. getVariable(String $variable) - Returns a variable value.
    9. getVariables() - Returns an array of variables.
    10. getOpt(String $option=null) - Returns a configuration option if it exists or returns null if it does not.
    11. getViewWithExtension() - Returns a template's full path with extension.
    12. getViewPath() - Returns a template's full path without extension.



