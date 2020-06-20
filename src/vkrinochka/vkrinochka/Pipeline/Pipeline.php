<?php

namespace vkrinochka\vkrinochka\Pipeline;

use vkrinochka\vkrinochka\Objects\Context;

class Pipeline
{
    /**
     * @var callable[]
     */
    private $stages;

    public function pipe(callable $stage)
    {
        $this->stages[] = $stage;
        return $this;
    }

    public function __invoke(Context $context, $next = null)
    {
        $this->start($context, $next);
    }

    public function start(Context $context, $next = null)
    {
        if(!$middleware = array_shift($this->stages) and $next != null && is_callable($next))
        {
            $next($context);
        }
        return $middleware($context, function ($context) use ($next) {
            return $this->start($context, $next);
        });
    }
}
