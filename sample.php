<?php
/**
 * Sample bot using PHPMaxBot
 *
 * This example demonstrates the basic usage of PHPMaxBot library
 */

require_once __DIR__ . '/vendor/autoload.php';

use PHPMaxBot\Helpers\Keyboard;

// Get bot token from environment or set it directly
$token = getenv('BOT_TOKEN') ?: 'your-bot-token-here';

// Create bot instance
$bot = new PHPMaxBot($token);

// Set bot commands (optional)
try {
    Bot::setMyCommands([
        ['name' => 'start', 'description' => 'Start the bot'],
        ['name' => 'help', 'description' => 'Show help message'],
        ['name' => 'keyboard', 'description' => 'Show keyboard example'],
        ['name' => 'echo', 'description' => 'Echo your message']
    ]);
} catch (Exception $e) {
    echo "Warning: Could not set commands: " . $e->getMessage() . "\n";
}

// Handle /start command
$bot->command('start', function($param) {
    $text = "Привет! Я бот на MAX мессенджере.\n\n";
    $text .= "Доступные команды:\n";
    $text .= "/help - Помощь\n";
    $text .= "/keyboard - Показать клавиатуру\n";
    $text .= "/echo текст - Повторить текст\n";

    return Bot::sendMessage($text);
});

// Handle /help command
$bot->command('help', function() {
    return Bot::sendMessage("Это бот-пример на PHPMaxBot. Используйте /start для начала работы.");
});

// Handle /echo command with parameter
$bot->command('echo', function($text) {
    if (empty($text)) {
        return Bot::sendMessage("Использование: /echo <текст>");
    }
    return Bot::sendMessage("Вы написали: " . $text);
});

// Handle /keyboard command - show inline keyboard
$bot->command('keyboard', function() {
    $keyboard = Keyboard::inlineKeyboard([
        [
            Keyboard::callback('Кнопка 1', 'button_1'),
            Keyboard::callback('Кнопка 2', 'button_2', ['intent' => 'positive'])
        ],
        [
            Keyboard::callback('Удалить сообщение', 'delete_message', ['intent' => 'negative'])
        ],
        [
            Keyboard::link('Открыть MAX', 'https://max.ru/')
        ],
        [
            Keyboard::requestContact('Отправить контакт')
        ],
        [
            Keyboard::requestGeoLocation('Отправить геолокацию')
        ]
    ]);

    return Bot::sendMessage('Выберите действие:', [
        'attachments' => [$keyboard]
    ]);
});

// Handle callback button: button_1
$bot->action('button_1', function() {
    $update = PHPMaxBot::$currentUpdate;
    $callbackId = $update['callback']['callback_id'];

    return Bot::answerOnCallback($callbackId, [
        'notification' => 'Вы нажали на кнопку 1!'
    ]);
});

// Handle callback button: button_2
$bot->action('button_2', function() {
    $update = PHPMaxBot::$currentUpdate;
    $callbackId = $update['callback']['callback_id'];

    return Bot::answerOnCallback($callbackId, [
        'message' => [
            'text' => 'Вы нажали на кнопку 2! Сообщение изменено.',
            'attachments' => null
        ]
    ]);
});

// Handle callback button: delete_message
$bot->action('delete_message', function() {
    $update = PHPMaxBot::$currentUpdate;
    $callbackId = $update['callback']['callback_id'];
    $messageId = isset($update['message']['id']) ? $update['message']['id'] : null;

    if ($messageId) {
        try {
            Bot::deleteMessage($messageId);
            return Bot::answerOnCallback($callbackId, [
                'notification' => 'Сообщение удалено'
            ]);
        } catch (Exception $e) {
            return Bot::answerOnCallback($callbackId, [
                'notification' => 'Не удалось удалить сообщение'
            ]);
        }
    }
});

// Handle pattern matching for callbacks (regex)
$bot->action('color:(.+)', function($matches) {
    $update = PHPMaxBot::$currentUpdate;
    $callbackId = $update['callback']['callback_id'];
    $color = $matches[1];

    return Bot::answerOnCallback($callbackId, [
        'message' => [
            'text' => "Вы выбрали цвет: $color",
            'attachments' => null
        ]
    ]);
});

// Handle bot_started event (when user starts bot for the first time)
$bot->on('bot_started', function() {
    $update = PHPMaxBot::$currentUpdate;
    $userName = isset($update['user']['name']) ? $update['user']['name'] : 'пользователь';
    $payload = isset($update['payload']) ? $update['payload'] : null;

    $text = "Привет, $userName! Спасибо, что запустили бота.";
    if ($payload) {
        $text .= "\nПараметр запуска: $payload";
    }

    return Bot::sendMessage($text);
});

// Handle all message_created events
$bot->on('message_created', function() {
    $update = PHPMaxBot::$currentUpdate;

    // Check if message has location
    if (isset($update['message']['location'])) {
        $location = $update['message']['location'];
        $lat = $location['latitude'];
        $lon = $location['longitude'];
        return Bot::sendMessage("Получена геолокация: $lat, $lon");
    }

    // Check if message has contact
    if (isset($update['message']['contact_info'])) {
        $contact = $update['message']['contact_info'];
        $name = isset($contact['full_name']) ? $contact['full_name'] : 'Unknown';
        $phone = isset($contact['tel']) ? $contact['tel'] : 'Unknown';
        return Bot::sendMessage("Получен контакт:\nИмя: $name\nТелефон: $phone");
    }

    // For text messages without commands, you can add custom logic here
    // For now, we'll just ignore them (unless they match a command)
});

// Start the bot
// In CLI mode: long polling
// In web mode: webhook
$bot->start([
    'message_created',
    'message_callback',
    'bot_started',
    'message_edited',
    'message_removed'
]);
