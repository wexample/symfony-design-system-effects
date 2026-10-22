<?php

namespace Wexample\SymfonyDesignSystemEffects\Twig;

use Twig\Environment;
use Twig\TwigFunction;
use Wexample\SymfonyDesignSystem\Twig\AbstractTemplateExtension;

/**
 * Effects on words, for a page that speaks loud — a landing, a hero:
 *
 *     Des solutions {{ text_highlight('sur-mesure') }} et {{ text_highlight('durables', { tone: 'grass' }) }}
 *     Le réseau de freelances {{ word_switcher(['inclusif', 'solidaire', 'généreux']) }}
 *
 * Both rest still for whoever asked for less motion.
 */
class TextEffectsExtension extends AbstractTemplateExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('text_highlight', $this->textHighlight(...), self::TEMPLATE_FUNCTION_OPTIONS),
            new TwigFunction('word_switcher', $this->wordSwitcher(...), self::TEMPLATE_FUNCTION_OPTIONS),
        ];
    }

    /**
     * Words a felt pen goes over: a pill of colour drawn behind their lower
     * half, from the middle outwards, once they come into view. Several on a
     * page are drawn one after the other, in their order.
     */
    public function textHighlight(
        Environment $twig,
        mixed $context,
        string $text,
        array $options = []
    ): string {
        return $this->componentsExtension->component(
            $twig,
            $context['render_pass'] ?? null,
            '@WexampleSymfonyDesignSystemEffectsBundle/components/text-highlight',
            [],
            [
                'text' => $text,
                // A colour of the categorical palette (`aqua`, `grass`, `ruby`…).
                'tone' => $options['tone'] ?? 'aqua',
                'class' => $options['class'] ?? null,
            ]
        );
    }

    /**
     * A word that rolls like a slot machine, then settles on the next of its
     * list, again and again. Read aloud, it is the list, once.
     */
    public function wordSwitcher(
        Environment $twig,
        mixed $context,
        array $words,
        array $options = []
    ): string {
        return $this->componentsExtension->component(
            $twig,
            $context['render_pass'] ?? null,
            '@WexampleSymfonyDesignSystemEffectsBundle/components/word-switcher',
            [
                'words' => array_values($words),
                // How long a word stays, in milliseconds.
                'interval' => $options['interval'] ?? 2000,
                // How long it rolls before the next one shows.
                'roll' => $options['roll'] ?? 500,
            ],
            [
                'words' => array_values($words),
                'class' => $options['class'] ?? null,
            ]
        );
    }
}
