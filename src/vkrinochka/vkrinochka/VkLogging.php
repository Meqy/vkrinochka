<?php


namespace vkrinochka\vkrinochka;


class VkLogging
{
    private $fp;
    private $loggers;
    private $current_logger;

    public function setSettings($name = "root", $path = "vk_logging.log", $message_example = "%date% [%type%] %message%")
    {
        $this->loggers[$name]["example"] = $message_example;
        $this->loggers[$name]["path"] = $path;
        return $this->loggers[$name];
    }

    public function setLogger($name = "root")
    {
        if(!isset($this->loggers[$name]))
        {
            $this->current_logger = $this->setSettings();
            $this->open();
            return;
        }
        $this->current_logger = $this->loggers[$name];
        $this->open();
        return;
    }

    private function open()
    {
        $this->fp = fopen($this->current_logger["path"], "a+");
    }

    public function logError($log)
    {
        $message = "";

        ob_start();

        print_r($log);

        $log = ob_get_clean();

        $message .= str_replace(["%date%", "%message%", "%type%"], [date('m.d.Y H:i:s',time()), $log, "ERROR"], $this->current_logger["example"]) . "\n";

        $this->write($message);
    }

    public function logWarning($log)
    {
        $message = "";

        ob_start();

        print_r($log);

        $log = ob_get_clean();

        $message .= str_replace(["%date%", "%message%", "%type%"], [date('D M d H:i:s Y',time()), $log, "WARNING"], $this->current_logger["example"]) . "\n";

        $this->write($message);
    }

    public function logInfo($log)
    {
        $message = "";

        ob_start();

        print_r($log);

        $log = ob_get_clean();

        $message .= str_replace(["%date%", "%message%", "%type%"], [date('D M d H:i:s Y',time()), $log, "INFO"], $this->current_logger["example"]) . "\n";

        $this->write($message);
    }

    private function write($string)
    {
        fwrite($this->fp, $string);
    }




}