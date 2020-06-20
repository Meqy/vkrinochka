<?php


namespace vkrinochka\vkrinochka;

use vkrinochka\vkrinochka\Objects\Context;
use vkrinochka\vkrinochka\PipeLine\Pipeline;

class Vk
{

    public $token;
    public CommandHandler $bot;
    private $confirmation_code;
    private Context $context;
    public Pipeline $pipeline;
    private $logging;


    /**
     * @param $param
     * @return void
     */
    function __construct(array $param)
    {
        $this->token = $param['token'];

        $this->confirmation_code = $param['confirmation_code'];

        $this->logging = (isset($param["logging"])
                                                ? new VkLogging()
                                                : false);

        $this->pipeline = new Pipeline();

        $this->context = new Context($this);

        $this->bot = new CommandHandler($this);

        $this->bot->on("confirmation", function () {
            print $this->confirmation_code;
            http_response_code(200);
        });

        if(isset($this->logging))
            $this->logging->setLogger();

    }


    /**
     * @param $method
     * @param $arguments
     * @return array
     */
    function method($method, $arguments) 
    {
        $ch = curl_init();
        $arguments += ["access_token" => $this->token, "v" => "5.103"];
        $url = "https://api.vk.com/method/$method";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($arguments));
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        $data = json_decode($data, true);
        if(isset($this->logging)) {
            if (array_key_exists("error", $data)) {
                $this->logging->logError($data["error"]["error_code"] . " - " . $data["error"]["error_msg"]);
            }
        }
        return $data["response"];
    }



    /**
     * @return mixed
     */
    function getUpdatesWebhook ()
    {
        return json_decode(file_get_contents('php://input'), true);
    }


    /**
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }


    /**
     * @param $middleware
     * @return void
     */
    public function middleware($middleware)
    {
        $this
            ->pipeline
                ->pipe($middleware);
    }



}