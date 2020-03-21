<?php

namespace Meqy/vkrinochka;

class Api
{
    private $token;
    private $v = '5.103';

    function __construct(string $token)
    {
        $this->token = $token;
    }

    function request(string $method, array $params)
    {
        $params = http_build_query($params);
        // return "https://api.vk.com/method/".$method."?$params&access_token=".$this->token."&v=".$this->v;
        return json_decode(file_get_contents("https://api.vk.com/method/".$method."?" . $params . "&access_token=".$this->token."&v=".$this->v), true)['response'];
    }
}