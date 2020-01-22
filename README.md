# Decorator Laravel

![测试覆盖率](./coverage.png)

Decorate your function/method by using middlewares in Laravel.

## Installation via Composer

> composer require istianxin/decorator-laravel

## Quickstart

### Define Middleware

You can define a middleware similar to [Laravel Middleware](https://laravel.com/docs/6.x/middleware). It should be noticed that the first parameter of th handle method is not ``` \Illuminate\Http\Request```, but an array the parameters of the method/function to be decorated. Except for this, everything is the same as Laravel Middleware.

Here is an example:
```
class MultiplicationMiddleware
{
    public function handle($data, $next, $factor = 1)
    {
        return $next($data) * $factor;
    }
}
```

### Callback And Parameters

Except for middleware, decorator accepts two other parameters, callback and parameters, which are also the first two parameters of  ```app()->call()```.

Callback is a callable, which can be invoked by ```app()->call()``` and ```call_user_func_array()```, such as: ```DummyClass@method```, a closure, ```[Dummyclass::class,staticMethod]```, ```[new DummyClass(), method]```, ```DummyClass::staticMethod```.

Parameters is an array of parameters of the callbale.

### Example

#### Single Middleware
```
$class = new class {
    public function add($a, $b)
    {
        return $a + $b;
    }
};
$a = 1;
$b = 2;
$factor = 3;

$decorator = new Decorator();
// classname with parameter
$middleware = MultiplicationMiddleware::class . ':' . $factor;
$result = $decorator->setCallback([$class, 'add'])
    ->setMiddleware($middleware)
    ->setParameters([$a, $b])
    ->decorate();
echo $result; // 9
```
#### Multiple Middleware

```
$decorator->setMiddleware([
    $object_middleware1,
    middleware2::class,
    middleware3:class:param1,param2,
    $closure_middleware4
]);
```
or
```
$decorator->setMiddleware([
    $object_middleware1,
    middleware2::class
])->appendMiddleware([
    middleware3:class:param1,param2,
    $closure_middleware4
])
```

See more [TestCase](./tests/DecoratorTest.php).


