<?php

namespace modules\helpers\jobs;

use Craft;
use craft\elements\Entry;
use craft\errors\ElementNotFoundException;
use craft\mail\Message;
use craft\queue\BaseJob;

class SendEntryUpdateEmail extends BaseJob
{
    public int $entryId;

    public function execute($queue): void
    {
        $entry = Entry::findOne($this->entryId);
        if (!$entry) {
            throw new ElementNotFoundException("No entry found with ID #{$this->entryId}");
        }

        $message = (new Message())
            ->setTo($entry->author->email)
            ->setSubject("Update Request: $entry->title")
            ->setHtmlBody(Craft::$app->getView()->renderString('
				<p>Content update requested for “{{ entry.title }}”.</p>
				<p><a href="{{ entry.url }}">Open {{ entry.type.name }}</a></p>
				<p><a href="{{ entry.cpEditUrl }}">Edit Entry in the Control Panel</a></p>
			', [ 'entry' => $entry ]));

        Craft::$app->getMailer()->send($message);
    }

    protected function defaultDescription(): ?string
    {
        return "Requesting update for entry #$this->entryId";
    }
}
