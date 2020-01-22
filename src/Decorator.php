<?php

namespace IsTianXin\Decorator;

use Illuminate\Pipeline\Pipeline;

/**
 * Decorate function/methods by Pipeline
 * Class Decorator
 * @package IsTianXin\Decorator
 */
class Decorator
{
    /**
     * @var callable | string
     */
    protected $callback;

    /**
     * @var array
     */
    protected $middleware;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * Decorator constructor.
     * @param callable|string $callback
     * @param array $middleware
     * @param array $parameters
     */
    public function __construct($callback = null, $middleware = [], array $parameters = [])
    {
        $this->setCallback($callback)
            ->setMiddleware($middleware)
            ->setParameters($parameters);
    }

    /**
     * @return callable|string
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param callable|string $callback
     * @return Decorator
     */
    public function setCallback($callback): Decorator
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * @return array
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    /**
     * @param array | mixed $middleware
     * @return Decorator
     */
    public function setMiddleware($middleware): Decorator
    {
        $this->middleware = array_values(is_array($middleware) ? $middleware : func_get_args());
        return $this;
    }

    /**
     * append middleware to current
     * @param $middleware
     * @return Decorator
     */
    public function appendMiddleware($middleware): Decorator
    {
        $middleware = is_array($middleware) ? $middleware : func_get_args();
        return $this->setMiddleware(array_merge($this->getMiddleware(), $middleware));
    }


    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array | mixed $parameters
     * @return Decorator
     */
    public function setParameters($parameters): Decorator
    {
        $this->parameters = is_array($parameters) ? $parameters : func_get_args();
        return $this;
    }

    /**
     * Get Normalized Callback Name
     * @return string
     */
    public function getNormalizedCallbackName(): string
    {
        $callback = $this->getCallback();
        if (is_string($callback)) {
            return $callback;
        }
        if (is_object($callback)) {
            return get_class($callback);
        }
        if (is_array($callback) && count($callback) === 2) {
            return (is_object($callback[0]) ? get_class($callback[0]) : $callback[0])
                . '@'
                . ($callback[1]);
        }
        return 'unknown';
    }

    /**
     * action before decorate
     * @return void
     */
    protected function beforeDecorate()
    {
    }

    /**
     * @return mixed
     */
    protected function decorateByPipeline()
    {
        return (new Pipeline(app()))
            ->send($this->getParameters())
            ->through($this->getMiddleware())
            ->then(function ($parameters) {
                return app()->call($this->getCallback(), $parameters);
            });
    }

    /**
     * @return mixed
     */
    public function decorate()
    {
        $this->beforeDecorate();
        return $this->decorateByPipeline();
    }
}
