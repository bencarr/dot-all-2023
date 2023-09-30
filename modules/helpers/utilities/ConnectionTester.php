<?php

namespace modules\helpers\utilities;

use Craft;
use craft\base\Utility;

class ConnectionTester extends Utility
{
    public static function displayName(): string
    {
        return 'Connection Tester';
    }

    public static function id(): string
    {
        return 'connection-tester';
    }

    public static function iconPath(): ?string
    {
        return Craft::getAlias('@appicons/buoey.svg');
    }

    public static function contentHtml(): string
    {
        return Craft::$app->getView()->renderTemplate('helpers/utilities/connection-tester.twig');
    }
}
