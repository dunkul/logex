<?php

namespace Dunkul\LogEx;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Str;

class LogExClass
{
  protected array $levels = [
    'debug'     => Logger::DEBUG,
    'info'      => Logger::INFO,
    'notice'    => Logger::NOTICE,
    'warning'   => Logger::WARNING,
    'error'     => Logger::ERROR,
    'critical'  => Logger::CRITICAL,
    'alert'     => Logger::ALERT,
    'emergency' => Logger::EMERGENCY,
    'query'     => Logger::NOTICE,
  ];

  public function __construct()
  {
  }

  protected function writeLog(string $_channel, string $_level, string $_message, array $_context = [])
  {
    $formatter = new LineFormatter("[%datetime%] %level_name%: %message%\n", 'H:i:s', false, true);

    if (Str::lower($_level) === 'query') {
      $path = sprintf('logs/%s/query.log', now()->format('Y-m-d'));
      $_level = 'notice';
    } else {
      $path = sprintf('logs/%s/%s.log', now()->format('Y-m-d'), $_channel);
    }

    $handler = new StreamHandler(storage_path($path));

    $handler->setFormatter($formatter);

    $orderLog = new Logger('dunkul');
    $orderLog->pushHandler($handler);
    $orderLog->{$_level}($_message, $_context);
  }

  public function __call(string $_level, array $_params)
  {
    if (in_array($_level, array_keys($this->levels)) && count($_params) > 1) {

      if (is_array($_params[1])) {
        $_params[1] = json_encode($_params[1], JSON_UNESCAPED_UNICODE);
      }

      $this->writeLog($_params[0], $_level, $_params[1]);
    }
  }
}
