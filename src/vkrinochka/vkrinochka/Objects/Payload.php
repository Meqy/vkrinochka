<?php

namespace vkrinochka\vkrinochka\Objects;

class Payload 
{
    /**
     * @param $payload
     * @param $val
     */
    function set($payload, $val)
	{
		$this->{$payload} = $val;
	}
}