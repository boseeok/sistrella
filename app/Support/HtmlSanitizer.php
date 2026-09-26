<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Allow-list sanitizer for admin rich-text (the page-content editor).
 *
 * Keeps basic formatting tags and a few safe inline styles (colour, font,
 * size, alignment); unwraps any other element, drops script-like elements
 * entirely and strips every attribute not explicitly allowed. Built on
 * ext-dom so no extra package is needed.
 */
class HtmlSanitizer
{
    private const TAGS = ['p', 'br', 'h2', 'h3', 'h4', 'strong', 'b', 'em', 'i', 'u', 's', 'ol', 'ul', 'li', 'blockquote', 'a', 'span'];

    /** Removed together with their content. */
    private const DROP = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'textarea', 'select', 'svg', 'math', 'template', 'noscript', 'link', 'meta'];

    /** Allowed inline CSS: property => value pattern. */
    private const STYLES = [
        'color'            => '/^(#[0-9a-f]{3,8}|rgba?\(\s*[\d.\s,%]+\)|[a-z]{3,20})$/i',
        'background-color' => '/^(#[0-9a-f]{3,8}|rgba?\(\s*[\d.\s,%]+\)|[a-z]{3,20})$/i',
        'font-family'      => '/^[\w\s,\'"-]{1,80}$/',
        'font-size'        => '/^\d{1,2}(\.\d{1,2})?(px|em|rem)$/',
        'text-align'       => '/^(left|right|center|justify)$/',
    ];

    public static function clean(?string $html): string
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }

        $doc = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8"?><div id="__root">'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $doc->getElementById('__root');
        if (! $root) {
            return e(strip_tags($html));
        }

        self::walk($root);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }

        return trim($out);
    }

    private static function walk(DOMNode $node): void
    {
        // Iterate over a static copy: children may be removed or replaced.
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child->nodeType === XML_COMMENT_NODE || $child->nodeType === XML_PI_NODE) {
                $node->removeChild($child);
                continue;
            }

            if (! $child instanceof DOMElement) {
                continue; // text nodes are escaped on output
            }

            $tag = strtolower($child->tagName);

            if (in_array($tag, self::DROP, true)) {
                $node->removeChild($child);
                continue;
            }

            self::walk($child);

            if (! in_array($tag, self::TAGS, true)) {
                // Unknown element: keep its (already cleaned) content, drop the tag.
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);
                continue;
            }

            self::cleanAttributes($child, $tag);
        }
    }

    private static function cleanAttributes(DOMElement $el, string $tag): void
    {
        $href  = $tag === 'a' ? trim($el->getAttribute('href')) : null;
        $style = self::cleanStyle($el->getAttribute('style'));

        foreach (iterator_to_array($el->attributes) as $attr) {
            $el->removeAttribute($attr->nodeName);
        }

        if ($style !== '') {
            $el->setAttribute('style', $style);
        }

        if ($tag === 'a' && $href !== null && preg_match('#^(https?://|mailto:|tel:|/(?!/)|\#)#i', $href)) {
            $el->setAttribute('href', $href);
            if (preg_match('#^https?://#i', $href)) {
                $el->setAttribute('target', '_blank');
                $el->setAttribute('rel', 'noopener noreferrer');
            }
        }
    }

    private static function cleanStyle(string $style): string
    {
        $kept = [];
        foreach (explode(';', $style) as $declaration) {
            [$prop, $value] = array_pad(array_map('trim', explode(':', $declaration, 2)), 2, '');
            $prop = strtolower($prop);
            if (isset(self::STYLES[$prop]) && preg_match(self::STYLES[$prop], $value)) {
                $kept[] = "{$prop}: {$value}";
            }
        }

        return implode('; ', $kept);
    }
}
