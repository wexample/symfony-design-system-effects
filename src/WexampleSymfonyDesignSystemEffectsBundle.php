<?php

namespace Wexample\SymfonyDesignSystemEffects;

use Wexample\SymfonyHelpers\Class\AbstractBundle;
use Wexample\SymfonyHelpers\Helper\BundleHelper;
use Wexample\SymfonyHelpers\Interface\LoaderBundleInterface;

/**
 * Effects of the wexample brand the design system stays free of: generated
 * avatars, text effects. Each piece plugs into a seam the design system opens
 * — an avatar engine, a component drawn by the loader.
 */
class WexampleSymfonyDesignSystemEffectsBundle extends AbstractBundle implements LoaderBundleInterface
{
    public static function getLoaderFrontPaths(): array
    {
        return [
            BundleHelper::getBundleCssAlias(static::class) => __DIR__.'/../assets/',
        ];
    }
}
