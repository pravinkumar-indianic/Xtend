<?php namespace Codengine\Awardbank\Traits;

trait OembedRenderer
{
    public static function renderOembedSingle($content) {
        //I want to share this video https://vimeo.com/123478917
        //Replace vimeo link with embed
        if (stripos($content, 'vimeo.com/video') !== false) {
            $content = preg_replace(
                '/<oembed url="\s*[a-zA-Z\/\/:\.]*player.vimeo.com\/video\/([0-9]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)\??"><\/oembed>/',
                '<iframe src="//player.vimeo.com/video/$1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',
                $content
            );

            return $content;
        }

        //Replace vimeo link with embed
        if (stripos($content, 'vimeo.com') !== false) {
            $content = preg_replace(
                '/<oembed url="https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)"><\/oembed>/',
                '<iframe src="//player.vimeo.com/video/$3" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',
                $content
            );

            return $content;
        }

        //Replace youtube link with embed
        if (stripos($content, 'be.com/watch') !== false) {
            $content = preg_replace(
                "/<oembed url=\"s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)\"><\/oembed>/i",
                "<iframe src=\"https://www.youtube.com/embed/$2\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>",
                $content
            );

            return $content;
        }

        return $content;
    }

    public static function renderOembedMulti($content) {
        if (!empty($content)) {
            $DOM = new \DOMDocument();
            @$DOM->loadHTML($content);
            $oembedList = $DOM->getElementsByTagName('oembed');
            foreach ($oembedList as $item) {
                $oembedElement = $DOM->saveHtml($item);
                if (!empty($oembedElement)) {
                    $replacement = self::renderOembedSingle($oembedElement);
                    $content = str_replace($oembedElement, $replacement, $content);
                }
            }
        }

        return $content;
    }
}
