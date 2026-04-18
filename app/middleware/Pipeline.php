<?php

class Pipeline {

    private $middlewares = [];

    public function __construct($middlewares = []) {
        $this->middlewares = $middlewares;
    }

    public function handle($request, $destination) {

        $pipeline = array_reduce(
            array_reverse($this->middlewares),
            function ($next, $middleware) {
                return function ($request) use ($middleware, $next) {
                    return (new $middleware)->handle($request, $next);
                };
            },
            $destination
        );

        return $pipeline($request);
    }
}