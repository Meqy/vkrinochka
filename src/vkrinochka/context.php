<?php

namespace Meqy/vkrinochka;

class Context
{

    // private $id;
    private $api;
    public $data;
    public $args;
    public $upload;

    function __construct(array $data, $api)
    {
        $this->api = $api;
        $this->args = implode(' ', array_slice(explode(' ', $data['object']['message']['text']), 1));
    	$this->data = $data;
    }

    public function upload(string $url, string $message = ''){
            
            $ServerUrl = $this->api->request('photos.getMessagesUploadServer', ['peer_id' => $this->data['object']['message']['peer_id']])['upload_url'];

            $ch = curl_init();

                $parameters = [
                    'photo' => new CURLFile($url)
                ];

            curl_setopt($ch, CURLOPT_URL, $ServerUrl);

            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);

            curl_close($ch);

            $result = json_decode($result, true);

            $photo = $result['photo'];

            $server = $result['server'];

            $hash = $result['hash'];

            $resp = $this->api->request('photos.saveMessagesPhoto', ['photo' => $photo, 'server' => $server, 'hash' => $hash])[0];

            $this->reply($message, ['attachment' => 'photo'.$resp['owner_id'].'_'.$resp['id']]);

            return true;
    }

    public function botIsAdmin(){
        $resp = $this->api->request('messages.getConversationsById', ['peer_ids' => $this->data['object']['message']['peer_id']]);
        if(in_array(-190911224, $resp['items'][0]['chat_settings']['admin_ids'])) return true;
        return false;
    }

    public function isMember(){
    	$chat_id = $this->data['object']['message']['peer_id'] - 2000000000;
    	if($chat_id > 0) return false;
    	return true;
    }

    public function isChat(){
    	$chat_id = $this->data['object']['message']['peer_id'] - 2000000000;
    	if($chat_id < 0) return false;
    	return true;
    }

    public function reply(string $message, array $params = array())
    {
        return $this->api->request('messages.send', ['peer_id'=>$this->data['object']['message']['peer_id'], 'message' => $message, 'random_id'=>0, 'forward_messages' => [$this->data['object']['message']['id']]] + $params);
    }

    private function PlaceHolder(string $text, int $id)
    {
    $resp = $this->api->request('users.get', ['user_ids' => $id])[0];
    return str_replace(["%fn%", "%ln%", "%fnln%", "%id%"], [$resp['first_name'], $resp['last_name'], $resp['first_name'].' '.$resp['last_name']], $text);
    }
    
    public function send(string $message, array $params = array())
    {
        return $this->api->request('messages.send', ['peer_id'=>$this->data['object']['message']['peer_id'], 'message' => $message, 'random_id'=>0] + $params);
    }

    public function answer(string $message){
        return $this->api->request('messages.send', ['peer_id'=>$this->data['object']['message']['peer_id'], 'message' => $this->PlaceHolder($message, $this->data['object']['message']['from_id']), 'random_id'=>0]);
    }
}