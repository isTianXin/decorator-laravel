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

Except for middleware, Decorator accepts two other parameters, which are also the first two parameters of  ```app()->call()```.
Callback is a callable, which can be invoked by ```app()->call()``` and ```call_user_func_array```, such as: ```DummyClass@method```, a closure, ```[Dummyclass::class,staticMethod]```, ```[new DummyClass(), method]```, ```DummyClass::staticMethod```.

Parameters is an array of parameters of the callbale.

### Example

see [TestCase](./tests/DecoratorTest.php).


