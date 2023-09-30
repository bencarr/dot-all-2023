<?php

namespace modules\helpers;

use Craft;
use craft\elements\Entry;
use craft\events\DefineBehaviorsEvent;
use craft\events\RegisterElementActionsEvent;
use modules\helpers\behaviors\DateRangeBehavior;
use modules\helpers\elements\actions\RequestContentUpdate;
use modules\helpers\web\twig\IconExtension;
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
        });

        Craft::$app->view->registerTwigExtension(new IconExtension());
    }

    protected function addCustomDateRangeDisplay(): void
    {
        Event::on(Entry::class, Entry::EVENT_DEFINE_BEHAVIORS, function(DefineBehaviorsEvent $event) {
            $event->behaviors[] = DateRangeBehavior::class;
        });
    }

    protected function addActionForRequestingContentUpdate(): void
    {
        Event::on(Entry::class, Entry::EVENT_REGISTER_ACTIONS, function(RegisterElementActionsEvent $event) {
            $event->actions[] = RequestContentUpdate::class;
        });
    }
}
