<?php

namespace App\Helper;

use DateTime;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;

class Utm
{
    const SD_UTM_COOKIE_NAME = '__sd__utm';

    public function update(Request $request): ?Cookie
    {
        $utms = $this->get($request);
        if ($utms) {
            $utms = base64_encode(json_encode($utms));
            return new Cookie(self::SD_UTM_COOKIE_NAME, $utms, new DateTime('+30 minutes'));
        }

        return null;
    }

    public function get(Request $request): array
    {
        $source = $request->get('utm_source', '');
        $medium = $request->get('utm_medium', '');
        $campaign = $request->get('utm_campaign', '');
        $term = $request->get('utm_term', '');
        $content = $request->get('utm_content', '');
        $gclid = $request->get('gclid', '');

        if ($source !== '' or $medium !== '' or $campaign !== '' or $term !== '' or $content !== '' or $gclid !== '') {
            return [
                'utm_source' => $source,
                'utm_medium' => $medium,
                'utm_campaign' => $campaign,
                'utm_term' => $term,
                'utm_content' => $content,
                'gclid' => $gclid,
            ];
        }

        return [];
    }
}
