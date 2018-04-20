## PhoxEngine - Small, extendable php template engine.

**PhoxEngine** is a small, lightweight and easy to use php template engine. This small template engine can be used with any php project. All you have to do is install it or require it in your project. This template engine also provides you with simple caching methodology that reduces your page load time by loading already cached views instead of processing them all over again when it is requested.

### Requirements:

**i.** PHP 7.0.6 or higher  
**ii.** Apache/Nginx Server                                                     
**iii.** md5 function  


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

#### Working with inheritance in templates.

##### In child view:
<!--  parent view declaration  -->
#parent<"layout">
<!--  parent view declaration  -->

<!--  blocks -->
#viewAs<"block_name">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;content goes in here..
#stopView
<!--  blocks -->

##### In parent view:

#contain<"block_name">

Note that when using template inheritance, only the data that is defined in the blocks will be returned. That is, any data outside the blocks will be ignored.

#### The #if, #else and #stopIf tags

```
    #if<$results>
        do this.....
    #else
        do that.....
    #stopIf
```

### The #set tag
The set tag helps you to set variables in your view. It is just like writing: $var = 'value;

```
    #set<results, []>
```

### The output tag: #{data}.
The output tag is a bit different to other tags in available in this engine. It does not make use of arrow brackets but uses curly brackets instead. This tag is mainly used to echo out data.

```
    #{phpinfo()}
```

This tag also allows the use of modifiers. Modifiers are used to modify the output that is being generated. To use a modifier, you simply need to separate the data and the modifier name with a semi-colon as shown below:

```
    #{phpinfo() : true}
```

The modifier above simply tells the engine to output the data using var_dump instead of echo.

### The #each tags
The each tag is used to loop over an array just like the language's foreach loop. But in this case, you can pass only the array that you need in that tag's bracket and you can access the $index and value dynamically.

```
    #each<$results>
        Index is #{$index} and value is #{$value}.
    #stopEach
```

You can also assign index and value variables yourself by simply separating them by semi-colons.

```
    #each<$results : $myIndex : $myValue>
        Index is #{myIndex} and value is #{myValue}.
    #stopEach
```

### The #php tag
This tag allows you to write raw php in your templates.

```
    #php<echo (1 == 1) ? "yes" : "no">
```

### The #include tag
The #include tag is used to include template files just like php's include function does.

```
    #include<"partial_template">
```

### The #cookie tag
This tag is used to echo cookies.
```
    #cookie<"cookie_name">
```