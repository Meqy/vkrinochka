<?php

namespace Meqy/vkrinochka;

use Meqy/vkrinochka/Context;

class CommandControl
{
	public $commands;
	public $data;

	function __construct($api){
		$this->commands['CommandNull'] = function ($data) {};
        $this->api = $api;
	}

	function hear($title, callable $handler){
		if(is_array($title)){
			foreach ($title as $_ => $names) {
				$this->commands[$names] = $handler;
			}

			return true;
		}elseif(is_string($title)){
			$this->commands[$title] = $handler;
		}
	}

	function RunCommand($title, $data, $context){
		if(isset($this->commands[$title]))
			return $this->commands[$title]($data, $context);

		return -1;
	}

	function DataGen(array $data){
		if(count($data) != 0){
			if($data['type'] == 'message_new'){
				if(isset($this->data['message_new']) && $this->RunData('message_new', $data) === false) return;


				$context = new Context($data, $this->api);

				if(isset($this->data['message'])) $this->data['message']($data, $context);

				$text = explode(' ', mb_strtolower($data['object']['message']['text']));

				if(isset($this->commands[$text[0]])){
					$this->RunCommand($text[0], $data, $context);
				}
			}elseif(isset($this->data[$data['type']])){
                $this->data[$data['type']]($data);
            }	
        }
	}

	function on(string $title, callable $handler){
		$this->data[$title] = $handler;
	}

	function RunData(string $title, array $data){
		if(isset($this->data[$title]))
			return $this->data[$title]($data);

		return -1;
	}
}