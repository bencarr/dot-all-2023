<?php

namespace modules\helpers\console\controllers;

use Craft;
use craft\console\Controller;
use craft\db\Query;
use craft\db\Table;
use craft\elements\Entry;
use craft\elements\User;
use DateTime;
use modules\helpers\widgets\NotesWidget;
use modules\helpers\widgets\RandomEntryWidget;
use yii\console\ExitCode;

class GenerateContentController extends Controller
{
    public function actionIndex(): int
    {
        $this->actionWidgets();
        $this->actionEntries();

        return ExitCode::OK;
    }

    public function actionEntries(): int
    {
        $this->generateEntries('homepage', [
            ['title' => 'Dot All 2023'],
        ]);
        $this->generateEntries('pages', [
            ['title' => 'About'],
        ]);
        $this->generateEntries('events', [
            ['title' => 'Birthday Extravaganza', 'fields' => ['startDate' => new DateTime('2023-02-07'), 'endDate' => new DateTime('2023-02-07')]],
            ['title' => 'Dot All Conference', 'fields' => ['startDate' => new DateTime('2023-10-03'), 'endDate' => new DateTime('2023-10-05')]],
            ['title' => 'Spring Festival', 'fields' => ['startDate' => new DateTime('2023-03-20'), 'endDate' => new DateTime('2023-04-07')]],
            ['title' => 'Winter Break', 'fields' => ['startDate' => new DateTime('2023-12-24'), 'endDate' => new DateTime('2024-01-02')]],
        ]);
        $this->generateEntries('articles', [
            ['title' => 'Craft Console Updates for Organizations', 'postDate' => new DateTime('2023-06-27'), 'fields' => ['externalUrl' => 'https://craftcms.com/blog/craft-console-updates-for-organizations']],
            ['title' => 'Craft CMS: A 10 Year Timeline', 'postDate' => new DateTime('2023-05-28'), 'fields' => ['externalUrl' => 'https://putyourlightson.com/articles/craft-cms-a-10-year-timeline']],
            ['title' => 'Accessibility is Everyone’s Job', 'postDate' => new DateTime('2021-11-30'), 'fields' => ['externalUrl' => 'https://cognition.happycog.com/article/accessibility-is-everyones-job']],
        ]);

        return ExitCode::OK;
    }

    protected function actionWidgets(): int
    {
        $dashboard = Craft::$app->getDashboard();

        // Clear out all the existing widgets
        (new Query())->createCommand()->delete(Table::WIDGETS)->execute();

        $users = User::find()->all();
        foreach ($users as $user) {
            Craft::$app->getUser()->setIdentity($user);
            $dashboard->saveWidget($dashboard->createWidget(NotesWidget::class));
            $dashboard->saveWidget($dashboard->createWidget(RandomEntryWidget::class));
        }

        return ExitCode::OK;
    }

    protected function generateEntries(string $sectionHandle, array $pages): void
    {
        $author = User::find()->one();
        $section = Craft::$app->getSections()->getSectionByHandle($sectionHandle);
        $type = $section->getEntryTypes()[0];

        foreach ($pages as $content) {
            $attributes = collect($content)->except(['fields'])->all();

            $entry = Entry::find()
                ->section($sectionHandle)
                ->status(null)
                ->title($attributes['title'])
                ->one();

            if (!$entry) {
                $entry = new Entry(['sectionId' => $section->id, 'typeId' => $type->id]);
            }

            $entry->setAuthor($author);
            $entry->setAttributes($attributes);
            $entry->setFieldValues($content['fields'] ?? []);

            Craft::$app->getElements()->saveElement($entry);
        }
    }
}
