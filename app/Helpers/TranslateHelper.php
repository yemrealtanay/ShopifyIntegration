<?php
namespace App\Helpers;

use Google\Cloud\Translate\V2\TranslateClient;
use Illuminate\Support\Facades\Cache;

class TranslateHelper
{
    public function translate($string, array $options = [])
    {
        if (isset($options['target'])) {
            $target = $options['target'];
        } else {
            $target = config('services.google_cloud.translation_default_target');
        }

        return Cache::rememberForever(
            "translation:" . $target . ":" . $string,
            function () use ($string, $options) {
                $translater = app(TranslateClient::class);
                return $translater->translate($string, $options)['text'];
            }
        );
    }
}
