<?php

namespace modules\helpers\behaviors;

use craft\elements\Entry;
use yii\base\Behavior;

/**
 * @property-read Entry $owner
 */
class DateRangeBehavior extends Behavior
{
    public function getDateRange()
    {
        $start = $this->owner->startDate;
        $end = $this->owner->endDate;

        // No start or end
        if (!$start && !$end) {
            return null;
        }

        // Start date with no end
        if ($start && !$end) {
            return "Starting {$start->format('M j, Y')}";
        }

        // End date with no start
        if (!$start && $end) {
            return "Until {$end->format('M j, Y')}";
        }

        // Start and end are the same
        if ($start->format('Ymd') === $end->format('Ymd')) {
            return $start->format('M j, Y');
        }

        $sameYear = $start->format('Y') === $end->format('Y');
        $sameMonth = $start->format('Ym') === $end->format('Ym');

        $startString = $sameYear ? $start->format('M j') : $start->format('M j, Y');
        $endString = $end->format('M j, Y');
        if ($sameMonth) {
            $endString = str_replace($end->format('M '), '', $endString);
        }

        return collect([$startString, $endString])->join('–');
    }
}
