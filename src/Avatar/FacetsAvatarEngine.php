<?php

namespace Wexample\SymfonyDesignSystemEffects\Avatar;

use Wexample\SymfonyDesignSystem\Helper\AvatarHelper;
use Wexample\SymfonyDesignSystem\Interface\AvatarEngineInterface;

/**
 * Three planes of colour laid across a white square, each cut by a straight
 * edge, darker where they cross, and a corner of the seed left white.
 * The colours are neighbours on the wheel, starting from a hue of the seed.
 */
class FacetsAvatarEngine implements AvatarEngineInterface
{
    private const int SIZE = 100;

    private const int PLANES = 3;

    public static function getName(): string
    {
        return 'facets';
    }

    public function render(
        string $seed,
        ?string $label = null
    ): string {
        $center = self::SIZE / 2;
        $hue = AvatarHelper::avatarPick($seed, 'hue', 360);
        // The corner left white, as a direction from the centre: the planes
        // come from the other side, each turned from the next by about a fifth
        // of a turn, so that two neighbours cross away from that corner.
        $corner = AvatarHelper::avatarPick($seed, 'corner', 360);
        $planes = '';

        for ($index = 0; $index < self::PLANES; ++$index) {
            $toward = $corner + 180 + ($index - 1) * 70 + AvatarHelper::avatarPick($seed, 'tilt'.$index, 31) - 15;
            // How far the edge stands from the centre, towards the plane: the
            // plane facing the white corner is the narrow one, the two beside
            // it wide, so that no edge runs through the centre and they cross
            // apart from each other, the corner still white.
            $reach = 1 === $index
                ? 8 + AvatarHelper::avatarPick($seed, 'reach'.$index, 18)
                : -10 + AvatarHelper::avatarPick($seed, 'reach'.$index, 16);
            $color = sprintf('hsl(%d, %d%%, %d%%)', ($hue + $index * 30) % 360, 68, 60 - $index * 4);

            // A square far larger than the avatar, its top edge the plane's
            // edge, turned around the centre.
            $planes .= sprintf(
                '<rect x="%1$d" y="%2$d" width="%3$d" height="%3$d" fill="%4$s" fill-opacity="0.85" style="mix-blend-mode:multiply" transform="rotate(%5$d %6$d %6$d)"/>',
                -self::SIZE,
                $center + $reach,
                self::SIZE * 3,
                $color,
                // The square's top edge faces down before it is turned.
                (($toward - 90) % 360 + 360) % 360,
                $center
            );
        }

        return sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %1$d %1$d">'
            .'<rect width="%1$d" height="%1$d" fill="#fff"/>'
            .'<g style="isolation:isolate">%2$s</g>'
            .'</svg>',
            self::SIZE,
            $planes
        );
    }
}
