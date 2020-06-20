<?php


namespace vkrinochka\vkrinochka;


class KeyboardMarkup
{

    /**
     * @param $text
     * @param string $action
     * @param array $payload
     * @param array $params
     * @param string $color
     * @return array|string
     */
    static function button($text, $action="text", $payload=["command"=>"start"], $params=[], $color="primary"){
        $params += (isset($text) ? ["label" => $text] : []);
        $color = (isset($color) ? ["color" => $color] : []);
        return [
            "action" => [
                "type" => $action,
                "payload" => json_encode($payload, JSON_UNESCAPED_UNICODE)
            ] + $params
        ] + $color;
    }

}