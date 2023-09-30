<?php

namespace modules\helpers\widgets;

use Craft;
use craft\base\Widget;

class NotesWidget extends Widget
{
    public string $notes = 'Be kind to one another';

    public static function displayName(): string
    {
        return 'Note to Self';
    }

    public static function icon(): ?string
    {
        return Craft::getAlias('@appicons/tip.svg');
    }

    public function getSettingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('helpers/widgets/notes/settings.twig', [
            'widget' => $this,
        ]);
    }

    public function getBodyHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('helpers/widgets/notes/body.twig', [
            'widget' => $this,
        ]);
    }
}
