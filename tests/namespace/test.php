<?php

require_once __DIR__ . '/../../lib/vendor/autoload.php';

final class Assert
{
    private static $failed = false;

    final public static function classExists(string $class): void
    {
        if (!class_exists($class)) {
            self::$failed = true;

            printf("class %s is supposed to exist\n", $class);
        }
    }

    final public static function interfaceExists(string $class): void
    {
        if (!interface_exists($class)) {
            self::$failed = true;

            printf("interface %s is supposed to exist\n", $class);
        }
    }

    final public static function exit(): void
    {
        if (self::$failed) {
            exit(1);
        }

        exit(0);
    }
}

Assert::classExists(\Twirp\Tests\Namespace\Proto\Hat::class);
Assert::classExists(\Twirp\Tests\Namespace\Proto\Size::class);
Assert::classExists(\Twirp\Tests\Namespace\Proto\TwirpError::class);
Assert::classExists(\Twirp\Tests\Namespace\Proto\HaberdasherClient::class);
Assert::classExists(\Twirp\Tests\Namespace\Proto\HaberdasherServer::class);
Assert::classExists(\Twirp\Tests\Namespace\Proto\HaberdasherAbstractClient::class);
Assert::classExists(\Twirp\Tests\Namespace\Proto\HaberdasherJsonClient::class);
Assert::interfaceExists(\Twirp\Tests\Namespace\Proto\Haberdasher::class);
