<?php

namespace modules\helpers\widgets;

use Craft;
use craft\base\Widget;

class RandomEntryWidget extends Widget
{
    public static function displayName(): string
    {
        return 'Random Entry';
    }

    public function getBodyHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('helpers/widgets/random/body.twig');
    }
}
