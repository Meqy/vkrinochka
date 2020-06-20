<?php


namespace vkrinochka\vkrinochka\Objects;


class Data
{
    private int $peer_id;
    private int $message_id;
    private string $text;
    private array $message;
    private int $date;
    private int $from_id;
    private array $attachments;
    private Payload $payload;
    private string $type;
    private array $keyboard;
    private array $action;
    private array $reply_message;

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getReplyMessage()
    {
        return $this->reply_message;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getMessageId()
    {
        return $this->message_id;
    }

    /**
     * @return mixed
     */
    public function getPeerId()
    {
        return $this->peer_id;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return mixed
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getFromId()
    {
        return $this->from_id;
    }

    /**
     * @return mixed
     */
    public function getKeyboard()
    {
        return $this->keyboard;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param mixed $message
     * @return Data
     */
    public function setMessage($message)
    {
        if(isset($message["payload"])){
            $payload = new Payload;
            foreach (json_decode($message["payload"], true) as $key => $value) {
                $payload->set($key, $value);
            }
        }else $payload = null;
        $action = (isset($message["action"]) ? $message["action"] : null);
        $this->message = $message;
        $this->setText($message["text"])
            ->setMessageId($message["id"])
            ->setPeerId($message["peer_id"])
            ->setAttachments($message["attachments"])
            ->setDate($message["date"])
            ->setAction($action)
            ->setFromId($message["from_id"])
            ->setPayload($payload)
            ->setReplyMessage((isset($message["reply_message"]) ? $message["reply_message"]["from_id"] : null));
        return $this;
    }

    /**
     * @param mixed $text
     * @return Data
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param mixed $message_id
     * @return Data
     */
    public function setMessageId($message_id)
    {
        $this->message_id = $message_id;
        return $this;

    }


    /**
     * @param mixed $peer_id
     * @return Data
     */
    public function setPeerId($peer_id)
    {
        $this->peer_id = $peer_id;
        return $this;

    }

    /**
     * @param mixed $action
     * @return Data
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;

    }


    /**
     * @param mixed $attachments
     * @return Data
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
        return $this;

    }

    /**
     * @param mixed $date
     * @return Data
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;

    }

    /**
     * @param $reply_message
     * @return Data;
     */
    public function setReplyMessage($reply_message)
    {
        $this->reply_message = $reply_message;
        return $this;

    }

    /**
     * @param mixed $from_id
     * @return Data
     */
    public function setFromId($from_id)
    {
        $this->from_id = $from_id;
        return $this;

    }

    /**
     * @param mixed $keyboard
     * @return Data
     */
    public function setKeyboard($keyboard)
    {
        $this->keyboard = $keyboard;
        return $this;

    }

    /**
     * @param mixed $payload
     * @return Data
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
        return $this;

    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
}