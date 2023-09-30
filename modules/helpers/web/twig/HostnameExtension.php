<?php

namespace modules\helpers\web\twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class HostnameExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('hostname', fn($url) => parse_url($url, PHP_URL_HOST)),
        ];
    }
}
