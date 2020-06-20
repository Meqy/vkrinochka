<?php

namespace vkrinochka\vkrinochka;

use Exception;
use vkrinochka\vkrinochka\Objects\Context;

class CommandHandler
{

    private $class;


    /**
     * CommandHandler constructor.
     * @param $class
     */
    function __construct(Vk $class){
        $this->class = $class;
    }

    /**
     * @param $hearConditions
     * @param $handler
     * @throws Exception
     */
    function hear($hearConditions, $handler){
        $rawConditions = !is_array($hearConditions) ? [$hearConditions] : $hearConditions;

        if(count($rawConditions) <= 0){
            throw new Exception("Condition shoud be not empty");
        }

        if(!is_callable($handler)){
            throw new Exception("Handler must be a function");
        } 

        $textCondition = false;
        $functionCondition = false;

        $conditions = array_map(function ($el) use (&$textCondition, &$functionCondition) {

            if(is_callable($el)){
                $functionCondition = true;

                return function ($val, $context) use ($el){
                    return $el($val, $context);
                };
            }

            $textCondition = true;

            if(is_string($el)){
                return function (Context $context, string $text="") use ($el){
                    $passed = preg_match($el, $text, $matched);

                    if ($passed){
                        $context->addMethod("matched", $matched);
                    }

                    return $passed;
                };
            }

            $stringCondition = $el;

            return function ($text) use ($stringCondition) { return $text == $stringCondition; };
        }, $rawConditions);

        $needText = $textCondition && $functionCondition == false;

        $this->class->middleware(function (Context $ctx, $next) use ($handler, $conditions, $needText) {
            if($needText and $ctx->getData()->getText() === null){
                $next($ctx, $next);
            }
            $hasSome = array_filter($conditions, function ($el) use ($ctx){
                return $el($ctx->getData()->getText(), $ctx);
            });
            http_response_code(200);
            // print "ok";
            ($hasSome ? $handler($ctx, $next) : $next($ctx, $next));
        });
    }


    /**
     * @param $title
     * @param callable $handler
     * @return void
     */
    function on($title, callable $handler){
        $this->class->middleware(function(Context $context, $next) use ($title, $handler)
        {
            // http_response_code(200);
            // echo "ok";
            ($context->is($title) ? $handler($context) : $next($context, $next));
        });
    }


    /**
     * @return void
     */
    function start()
    {
        $this->class->pipeline->start($this->class->getContext(), function () {
            // print "ok";
            http_response_code(200);
        });
    }

}