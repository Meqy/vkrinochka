# vkrinochka
> VkRinochka is a library for working with VKApi
>
## Download
```$xslt
composer require meqy/vkrinochka
```

## Examples
##### Simple example
```php
<?php
$access_token = "";
$confirmation_code = 1;
$vk = new \vkrinochka\vkrinochka\Vk([
    "access_token" => $access_token,
    "confirmation_code" => $confirmation_code
]);

$vk->bot->hear("/hello/iu", function ($context){
    $context->reply("Hello!");
});

$vk->bot->start();
```
##### Handle keyboard payload
```php
<?php

$access_token = "";
$confirmation_code = 1;
$vk = new \vkrinochka\vkrinochka\Vk([
    "access_token" => $access_token,
    "confirmation_code" => $confirmation_code
]);

$vk->bot->hear(function ($val, $context) {
    return $context->getData()->getPayload()->command == "start";
}, function ($context){
    $context->reply("Hello!");
});
```
##### Call Vk methods and get matched array
```php
<?php

$access_token = "";
$confirmation_code = 1;
$vk = new \vkrinochka\vkrinochka\Vk([
    "access_token" => $access_token,
    "confirmation_code" => $confirmation_code
]);

$vk->bot->hear("/getName (\d+)/iu", function ($context) use ($vk) {
    $user = $vk
                ->method("users.get", ["user_ids" => $context->matched[1]])["items"][0]["first_name"];
    $context
            ->reply("You search name of this user? - $user");
}); 
```

## Future
- [ ] Add LongPolling
- [ ] Refactoring for code
- [ ] Add static typing for all variables