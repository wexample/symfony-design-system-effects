<?php

namespace Wexample\SymfonyDesignSystemEffects\Avatar;

use Wexample\SymfonyDesignSystem\Helper\AvatarHelper;
use Wexample\SymfonyDesignSystem\Interface\AvatarEngineInterface;
use Wexample\SymfonyHelpers\Attribute\WorkerSafe;

/**
 * The faces network gave its members from 2019 on: a disc of the seed's
 * colour under a white sheen, and one of 26 pairs of white eyes drawn by hand
 * — happy arcs, crosses, hearts, a cyclops, a monocle, sleepy lids —, each
 * set somewhere in the face so it seems to look somewhere.
 *
 * Unlike the original, the face follows a seed and nothing else: neither the
 * name, which changes, nor the random generator, which made the colour change
 * at every request from 2021 on.
 */
class Wexies2020AvatarEngine implements AvatarEngineInterface
{
    // The square the eyes were drawn in.
    private const float SIZE = 212.598;

    private const string EYES_DIR = __DIR__.'/../Resources/avatar/wexies-2020/';

    /**
     * @var array<string, string>|null what each pair of eyes draws, by letter
     */
    #[WorkerSafe('The eye shapes, read once from the bundled files.')]
    private ?array $eyes = null;

    public static function getName(): string
    {
        return 'wexies-2020';
    }

    public function render(
        string $seed,
        ?string $label = null
    ): string {
        $eyes = $this->getEyes();
        $letters = array_keys($eyes);
        $half = self::SIZE / 2;

        return sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %1$s %1$s">'
            .'<defs><linearGradient id="sheen" x1="74%%" y1="100%%" x2="26%%" y2="0%%">'
            .'<stop offset="0%%" stop-color="#fff" stop-opacity="0"/><stop offset="100%%" stop-color="#fff" stop-opacity="0.4"/>'
            .'</linearGradient></defs>'
            .'<circle cx="%2$s" cy="%2$s" r="%2$s" fill="%3$s"/>'
            .'<circle cx="%2$s" cy="%2$s" r="%2$s" fill="url(#sheen)"/>'
            .'%4$s</svg>',
            self::SIZE,
            $half,
            AvatarHelper::avatarColor($seed, 60, 50),
            $eyes[$letters[AvatarHelper::avatarPick($seed, 'eyes', count($letters))]]
        );
    }

    /**
     * The 26 pairs, `a` to `z`, each the inside of its file.
     *
     * @return array<string, string>
     */
    public function getEyes(): array
    {
        if (null === $this->eyes) {
            $this->eyes = [];

            foreach (glob(self::EYES_DIR.'*.svg') as $file) {
                preg_match('/<svg[^>]*>(.*)<\/svg>/s', (string) file_get_contents($file), $match);
                $this->eyes[basename($file, '.svg')] = $match[1];
            }

            ksort($this->eyes);
        }

        return $this->eyes;
    }
}
