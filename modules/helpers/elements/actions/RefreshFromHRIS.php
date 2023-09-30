<?php

namespace modules\helpers\elements\actions;

use craft\base\ElementAction;
use Craft\elements\db\ElementQueryInterface;

class RefreshFromHRIS extends ElementAction
{
    public static function displayName(): string
    {
        return 'Refresh From HRIS';
    }

    public function getMessage(): ?string
    {
        return 'Selected users refreshed from external HRIS';
    }

    public function performAction(ElementQueryInterface $query): bool
    {
        // External sync logic

        return true;
    }
}
