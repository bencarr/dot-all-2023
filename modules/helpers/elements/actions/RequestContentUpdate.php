<?php

namespace modules\helpers\elements\actions;

use craft\base\ElementAction;
use Craft\elements\db\ElementQueryInterface;
use craft\helpers\Queue;
use craft\queue\BaseJob;
use modules\helpers\jobs\SendEntryUpdateEmail;

class RequestContentUpdate extends ElementAction
{
    public static function displayName(): string
    {
        return 'Request Content Update';
    }

    public function getConfirmationMessage(): ?string
    {
        return 'Request content update for the selected entries? Authors will be notified via email immediately.';
    }

    public function getMessage(): ?string
    {
        return 'Content update emails sent!';
    }

    public function performAction(ElementQueryInterface $query): bool
    {
        collect($query->ids())
            ->map(fn($id) => new SendEntryUpdateEmail(['entryId' => $id]))
            ->each(fn(BaseJob $job) => Queue::push($job));

        return true;
    }
}
