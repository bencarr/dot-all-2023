<?php

namespace modules\helpers;

use Craft;
use craft\elements\Entry;
use craft\elements\User;
use craft\events\DefineBehaviorsEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterElementActionsEvent;
use craft\events\RegisterTemplateRootsEvent;
use craft\services\Dashboard;
use craft\services\Utilities;
use craft\web\View;
use modules\helpers\behaviors\DateRangeBehavior;
use modules\helpers\elements\actions\RefreshFromHRIS;
use modules\helpers\elements\actions\RequestContentUpdate;
use modules\helpers\utilities\ConnectionTester;
use modules\helpers\web\twig\HostnameExtension;
use modules\helpers\web\twig\IconExtension;
use modules\helpers\widgets\NotesWidget;
use modules\helpers\widgets\RandomEntryWidget;
use yii\base\Event;
use yii\base\Module as BaseModule;

/**
 * @method static HelpersModule getInstance()
 */
class HelpersModule extends BaseModule
{
    public function init(): void
    {
        Craft::setAlias('@modules/helpers', __DIR__);

        // Set the controllerNamespace based on whether this is a console or web request
        if (Craft::$app->request->isConsoleRequest) {
            $this->controllerNamespace = 'modules\\helpers\\console\\controllers';
        } else {
            $this->controllerNamespace = 'modules\\helpers\\controllers';
        }

        parent::init();

        // Defer most setup tasks until Craft is fully initialized
        Craft::$app->onInit(function() {
            $this->addCustomDateRangeDisplay();
            $this->addActionForRequestingContentUpdate();
            $this->addActionForRefreshingUserFromHRIS();
            $this->addDashboardWidgets();
            $this->addConnectionTesterUtility();
        });

        Craft::$app->view->registerTwigExtension(new IconExtension());
        Craft::$app->view->registerTwigExtension(new HostnameExtension());
    }

    protected function addCustomDateRangeDisplay(): void
    {
        Event::on(Entry::class, Entry::EVENT_DEFINE_BEHAVIORS, function(DefineBehaviorsEvent $event) {
            if ($event->sender->sectionId && $event->sender->section->handle === 'events') {
                $event->behaviors[] = DateRangeBehavior::class;
            }
        });
    }

    protected function addActionForRequestingContentUpdate(): void
    {
        Event::on(Entry::class, Entry::EVENT_REGISTER_ACTIONS, function(RegisterElementActionsEvent $event) {
            $event->actions[] = RequestContentUpdate::class;
        });
    }

    protected function addActionForRefreshingUserFromHRIS(): void
    {
        Event::on(User::class, User::EVENT_REGISTER_ACTIONS, function(RegisterElementActionsEvent $event) {
            $event->actions[] = RefreshFromHRIS::class;
        });
    }

    protected function addDashboardWidgets(): void
    {
        // Register a template root for the `templates` directory inside our module
        Event::on(View::class, View::EVENT_REGISTER_CP_TEMPLATE_ROOTS, function(RegisterTemplateRootsEvent $event) {
            $event->roots['helpers'] = __DIR__ . '/templates';
        });

        // Register our two dashboard widgets
        Event::on(Dashboard::class, Dashboard::EVENT_REGISTER_WIDGET_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = RandomEntryWidget::class;
            $event->types[] = NotesWidget::class;
        });
    }

    protected function addConnectionTesterUtility()
    {
        Event::on(Utilities::class, Utilities::EVENT_REGISTER_UTILITY_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = ConnectionTester::class;
        });
    }
}
