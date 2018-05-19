<?php

declare(strict_types=1);

/*
 * This file is part of the Indragunawan/sequence-bundle
 *
 * (c) Indra Gunawan <hello@indra.my.id>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indragunawan\SequenceBundle\Utils;

class PlaceholderReplacer implements PlaceholderReplacerInterface
{
    /**
     * {@inheritdoc}
     */
    public function replacePlaceholder(?string $placeholderText, int $number, array $replacementPlaceholders = []): string
    {
        $number = (string) $number;

        if ('' === $placeholderText || null === $placeholderText) {
            return $number;
        }

        return \preg_replace_callback('/\{\{ ?(\w++)(\|(lower|upper|ucwords|ucfirst|lcfirst))?(\|\d++\|[\s\w`~!@#$%^&*()_+{\\|;\':",.\/<>\?\-=]+(\|[0-2]++)?)? ?\}\}/', function ($m) use ($number, $replacementPlaceholders) {
            $replacement = $this->getReplacement($m[1], $number, $replacementPlaceholders);

            if (isset($m[4])) {
                list(, $padLength, $padString, $padType) = \array_map(function ($item) {
                    return trim($item);
                }, explode('|', $m[4].'|'));

                /**
                 * if $padType is not defined. then use 0.
                 *
                 * 0 => STR_PAD_LEFT
                 * 1 => STR_PAD_RIGHT
                 * 2 => STR_PAD_BOTH
                 */
                $replacement = str_pad($replacement, (int) $padLength, (string) $padString, '' === $padType ? 0 : (int) $padType);
            }

            return $this->transformValue($replacement, $m[3] ?? '');
        }, $placeholderText);
    }

    private function getReplacement(string $placeholder, string $number, array $replacementPlaceholders = []): string
    {
        if ('NUMBER' === $placeholder) {
            return $number;
        } elseif (in_array($placeholder, ['d', 'D', 'j', 'l', 'F', 'm', 'M', 'n', 'Y', 'y', 'g', 'G', 'h', 'H', 'i', 's', 'c', 'U'], true)) {
            return date($placeholder);
        } elseif (in_array($placeholder, ['Rj', 'Rn', 'Ry', 'RY', 'Rg'], true)) {
            return RomanNumerals::toRoman((int) date(substr($placeholder, 1)));
        } elseif (isset($replacementPlaceholders[$placeholder])) {
            return $replacementPlaceholders[$placeholder];
        }

        throw new \InvalidArgumentException(sprintf('No value provided for placeholder "%s", did you mean "%s"?', $placeholder, implode('", "', array_keys($replacementPlaceholders))));
    }

    private function transformValue(string $replacement, string $transformType)
    {
        if ('lower' === $transformType) {
            return strtolower($replacement);
        } elseif ('upper' === $transformType) {
            return strtoupper($replacement);
        } elseif ('ucwords' === $transformType) {
            return ucwords($replacement);
        } elseif ('ucfirst' === $transformType) {
            return ucfirst($replacement);
        } elseif ('lcfirst' === $transformType) {
            return lcfirst($replacement);
        }

        return $replacement;
    }
}
