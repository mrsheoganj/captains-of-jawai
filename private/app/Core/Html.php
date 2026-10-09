<?php
declare(strict_types=1);

namespace App\Core;

/** Whitelist-based HTML sanitiser for rich text written in the admin editor. */
final class Html
{
    private const TAGS = [
        'p' => [], 'br' => [], 'h2' => ['id'], 'h3' => ['id'], 'h4' => [], 'strong' => [], 'b' => [], 'em' => [], 'i' => [], 'u' => [],
        's' => [], 'blockquote' => [], 'ul' => [], 'ol' => [], 'li' => [], 'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'width', 'height', 'loading'], 'figure' => [], 'figcaption' => [], 'hr' => [],
        'table' => [], 'thead' => [], 'tbody' => [], 'tr' => [], 'th' => [], 'td' => [], 'span' => [], 'div' => [],
        'iframe' => ['src', 'width', 'height', 'allow', 'allowfullscreen', 'title', 'loading'], 'sup' => [], 'sub' => [], 'small' => [],
    ];
    private const IFRAME_HOSTS = ['www.youtube.com', 'youtube.com', 'www.youtube-nocookie.com', 'player.vimeo.com', 'www.google.com', 'maps.google.com'];

    public static function clean(?string $html): string
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8"><div id="__root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        $root = $doc->getElementById('__root');
        if (!$root) {
            return '';
        }
        self::walk($root);
        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }
        return trim($out);
    }

    private static function walk(\DOMNode $node): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child instanceof \DOMComment) {
                $node->removeChild($child);
                continue;
            }
            if (!$child instanceof \DOMElement) {
                continue;
            }
            $tag = strtolower($child->tagName);
            if (in_array($tag, ['script', 'style', 'object', 'embed', 'form', 'input', 'button', 'textarea', 'select', 'link', 'meta', 'base', 'svg', 'math'], true)) {
                $node->removeChild($child);
                continue;
            }
            if (!isset(self::TAGS[$tag])) {
                // unwrap unknown element, keep its children
                self::walk($child);
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);
                continue;
            }
            foreach (iterator_to_array($child->attributes) as $attr) {
                $name = strtolower($attr->name);
                if (!in_array($name, self::TAGS[$tag], true) && $name !== 'class') {
                    $child->removeAttribute($attr->name);
                    continue;
                }
                if (in_array($name, ['href', 'src'], true)) {
                    $v = trim($attr->value);
                    if (!preg_match('~^(https?://|/|#|mailto:|tel:)~i', $v)) {
                        $child->removeAttribute($attr->name);
                    }
                }
            }
            if ($tag === 'b' || $tag === 'i') {
                // Normalise presentational tags to semantic ones.
                $new = $child->ownerDocument->createElement($tag === 'b' ? 'strong' : 'em');
                while ($child->firstChild) {
                    $new->appendChild($child->firstChild);
                }
                $node->replaceChild($new, $child);
                $child = $new;
            }
            if ($tag === 'img' && !$child->hasAttribute('src')) {
                $node->removeChild($child);
                continue;
            }
            if ($tag === 'iframe') {
                $host = parse_url($child->getAttribute('src'), PHP_URL_HOST);
                if (!in_array($host, self::IFRAME_HOSTS, true)) {
                    $node->removeChild($child);
                    continue;
                }
            }
            if ($tag === 'a' && $child->getAttribute('target') === '_blank') {
                $child->setAttribute('rel', 'noopener');
            }
            self::walk($child);
        }
    }
}
