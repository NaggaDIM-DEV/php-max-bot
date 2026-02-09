<?php
/**
 * MaxBotException.php
 *
 * @author NaggaDIM-DEV <naggadim-dev@naggadim>
 * @link https://github.com/naggadim-dev/php-max-bot
 * @license GPL-3.0
 */

namespace PHPMaxBot\Exceptions;

use Exception;

/**
 * Base exception class for PHPMaxBot
 */
class MaxBotException extends Exception
{
    /**
     * Additional context data
     *
     * @var array
     */
    protected $context = [];

    /**
     * MaxBotException constructor
     *
     * @param string $message
     * @param int $code
     * @param array $context
     */
    public function __construct($message = "", $code = 0, $context = [])
    {
        parent::__construct($message, $code);
        $this->context = $context;
    }

    /**
     * Get context data
     *
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }
}
