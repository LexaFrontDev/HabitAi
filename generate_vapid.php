<?php
require 'vendor/autoload.php';

use Minishlink\WebPush\VAPID;

try {
    $keys = VAPID::createVapidKeys();

    if (isset($keys['publicKey']) && isset($keys['privateKey'])) {
        echo "Public Key: " . $keys['publicKey'] . PHP_EOL;
        echo "Private Key: " . $keys['privateKey'] . PHP_EOL;
    } else {
        echo "Ошибка: ключи не сгенерированы." . PHP_EOL;
    }
} catch (\Exception $e) {
    echo "Ошибка при генерации VAPID ключей: " . $e->getMessage() . PHP_EOL;
}
