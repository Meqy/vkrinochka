<?php

namespace vkrinochka\vkrinochka\Objects;

use CURLFile;
use vkrinochka\vkrinochka\Vk;

class Context
{

    private Vk $class;
    public Data $data;
    private array $params;
    public $group_id;

    /**
     * Context constructor.
     * @param Vk $class
     */
    function __construct(Vk $class)
    {
        $this->class = $class;
        $this->data = new Data();
        $this->params = [];
        $data = $class->getUpdatesWebhook();
        if (isset($data["object"])){
        $this->getData()
                ->setMessage($data['object']["message"]);
        }
        $this->getData()
                ->setType($data["type"]);
        $this->group_id = $data["group_id"];
    }

    public function is($event)
    {
        return $this->getData()->getType() == $event;
    }

    public function upload($url, $message = '') {

        $ServerUrl = $this->class->method('photos.getMessagesUploadServer', ['peer_id' => $this->data['object']['message']['peer_id']])['upload_url'];

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

        $resp = $this->class->method('photos.saveMessagesPhoto', ['photo' => $photo, 'server' => $server, 'hash' => $hash])[0];

        $this
            ->addParameter(['attachment' => 'photo'.$resp['owner_id'].'_'.$resp['id']])
            ->reply($message);

        return true;
    }

    /**
     * @return Data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return Bool
     */
    public function isMember(){
        $chat_id = $this->data['object']['message']['peer_id'] - 2000000000;
        if($chat_id > 0) return false;
        return true;
    }

    /**
     * @param $name
     * @param $method
     * @return Bool
     */
    public function addMethod($name, $method)
    {
        if(!isset($this->{$name}))
        {
            $this->{$name} = $method;
        }
        return true;
    }

    public function addParameter($param)
    {
        $this->params += $param;
        return $this;
    }

    public function __call($name, $arguments)
    {
        return call_user_func($this->{$name}, $arguments);
    }

    public function isChat(){
        $chat_id = $this->getData()->getPeerId() - 2000000000;
        if($chat_id < 0) return false;
        return true;
    }

    public function reply($message)
    {
        $data = $this->class->method('messages.send', ['peer_id'=>$this->getData()->getPeerId(), 'message' => $message, 'random_id'=>0, 'forward_messages' => [$this->getData()->getMessageId()]] + $this->params);
        if($this->params)
            $this->params = [];
        return $data;
    }

    public function send($message, array $params = null)
    {
        $data = $this->class->method('messages.send', ['peer_id'=>$this->getData()->getPeerId(), 'message' => $message, 'random_id'=>0] + $this->params);
        if($this->params)
            $this->params = [];
        return $data;
    }
}