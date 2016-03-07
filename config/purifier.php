<?php
/**
 * Ok, glad you are here
 * first we get a config instance, and set the settings
 * $config = HTMLPurifier_Config::createDefault();
 * $config->set('Core.Encoding', $this->config->get('purifier.encoding'));
 * $config->set('Cache.SerializerPath', $this->config->get('purifier.cachePath'));
 * if ( ! $this->config->get('purifier.finalize')) {
 *     $config->autoFinalize = false;
 * }
 * $config->loadArray($this->getConfig());
 *
 * You must NOT delete the default settings
 * anything in settings should be compacted with params that needed to instance HTMLPurifier_Config.
 *
 * @link http://htmlpurifier.org/live/configdoc/plain.html
 */

return [

    'encoding'  => 'UTF-8',
    'finalize'  => true,
    'cachePath' => storage_path('app/purifier'),
    'settings'  => [
        'default' => [
            'HTML.Doctype'             => 'XHTML 1.0 Strict',
            'HTML.Allowed'             => 'div,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src],video[src|type|poster|controls],source[src|type]',
            'CSS.AllowedProperties'    => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty'   => true,
        ],
        'test'    => [
            'Attr.EnableID' => true
        ],
        "youtube" => [
            "HTML.SafeIframe"      => 'true',
            "URI.SafeIframeRegexp" => "%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/)%",
        ],
    ],
    // HTML5 element and attributes from Alex Kennberg (https://github.com/kennberg/php-htmlpurifier-html5)
    'addElement' => [
        // http://developers.whatwg.org/sections.html
        ['figure', 'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common'],
        ['figcaption', 'Inline', 'Flow', 'Common'],
        ['section', 'Block', 'Flow', 'Common'],
        ['nav',     'Block', 'Flow', 'Common'],
        ['article', 'Block', 'Flow', 'Common'],
        ['aside',   'Block', 'Flow', 'Common'],
        ['header',  'Block', 'Flow', 'Common'],
        ['footer',  'Block', 'Flow', 'Common'],
        // Content model actually excludes several tags, not modelled here
        ['address', 'Block', 'Flow', 'Common'],
        ['hgroup', 'Block', 'Required: h1 | h2 | h3 | h4 | h5 | h6', 'Common'],
        // http://developers.whatwg.org/grouping-content.html
        ['figure', 'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common', [ 'contenteditable' => 'Text' ]],
        ['figcaption', 'Inline', 'Flow', 'Common'],
        // http://developers.whatwg.org/the-video-element.html#the-video-element
        ['video', 'Block', 'Optional: (source, Flow) | (Flow, source) | Flow', 'Common', [
            'src' => 'URI',
            'type' => 'Text',
            'width' => 'Length',
            'height' => 'Length',
            'poster' => 'URI',
            'preload' => 'Enum#auto,metadata,none',
            'controls' => 'Bool',
        ]],
        ['source', 'Block', 'Flow', 'Common', [ 'src' => 'URI', 'type' => 'Text']],
        // http://developers.whatwg.org/text-level-semantics.html
        ['s',    'Inline', 'Inline', 'Common'],
        ['var',  'Inline', 'Inline', 'Common'],
        ['sub',  'Inline', 'Inline', 'Common'],
        ['sup',  'Inline', 'Inline', 'Common'],
        ['mark', 'Inline', 'Inline', 'Common'],
        ['wbr',  'Inline', 'Empty', 'Core'],
        // http://developers.whatwg.org/edits.html
        ['ins', 'Block', 'Flow', 'Common', ['cite' => 'URI', 'datetime' => 'CDATA']],
        ['del', 'Block', 'Flow', 'Common', ['cite' => 'URI', 'datetime' => 'CDATA']],
    ],
    'addAttribute' => [
        // TinyMCE
        ['img', 'data-mce-src', 'Text'],
        ['img', 'data-mce-json', 'Text'],
        // Others
        ['iframe', 'allowfullscreen', 'Bool'],
        ['table', 'height', 'Text'],
        ['td', 'border', 'Text'],
        ['th', 'border', 'Text'],
        ['tr', 'width', 'Text'],
        ['tr', 'height', 'Text'],
        ['tr', 'border', 'Text'],
    ]
];
