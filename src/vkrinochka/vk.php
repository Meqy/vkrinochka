<?php

namespace Meqy/vkrinochka;

use Meqy/vkrinochka/Api;

use Meqy/vkrinochka/CommandControl;

class Vk
{

    public $params;
    public $token;
    public $api;
    public $upload;
    public $bot;
    public $webhook;

    function __construct(array $param)
    {
        // global $data;
        $this->params = $param;
        $this->token = $this->params['token'];
        $this->confirmation_code = $this->params['confirmation_code'];
        $this->api = new Api($this->token);
        $this->bot = new CommandControl($this->api);
        $this->webhook = function($request, $response){
            $data = json_decode(file_get_contents('php://input'), true);
            // file_put_contents('s', json_encode($data));
            if($data['type'] == 'confirmation'){
            	echo $this->confirmation_code;
            }elseif($data['type'] != 'confirmation'){
            	echo 'ok';
            }
            $this->bot->DataGen($data);
        	
            };
        
    }

}