<?php

namespace Wexample\SymfonyDesignSystemEffects\Tests\Unit\Avatar;

use PHPUnit\Framework\TestCase;
use Wexample\SymfonyDesignSystemEffects\Avatar\FacetsAvatarEngine;

class FacetsAvatarEngineTest extends TestCase
{
    public function testTheSameSeedDrawsTheSameFacets(): void
    {
        $engine = new FacetsAvatarEngine();
        $face = $engine->render('01a1338a-e899-7498-aadd-b03d9cab1eee');

        $this->assertSame($face, $engine->render('01a1338a-e899-7498-aadd-b03d9cab1eee', 'A name changes nothing'));
        $this->assertSame(3, substr_count($face, 'mix-blend-mode'));
        $this->assertNotFalse(simplexml_load_string($face));
    }

    public function testSeedsSpreadOverTheFacets(): void
    {
        $engine = new FacetsAvatarEngine();
        $faces = array_map(fn (int $seed) => $engine->render((string) $seed), range(1, 100));

        $this->assertCount(100, array_unique($faces));
    }
}
