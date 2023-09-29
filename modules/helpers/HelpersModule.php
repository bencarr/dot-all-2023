<?php

namespace modules\helpers;

use Craft;
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
            $this->attachEventHandlers();
            // ...
        });
    }

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/4.x/extend/events.html to get started)
    }
}
