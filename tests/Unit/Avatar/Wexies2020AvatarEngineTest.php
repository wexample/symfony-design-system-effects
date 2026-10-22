<?php

namespace Wexample\SymfonyDesignSystemEffects\Tests\Unit\Avatar;

use PHPUnit\Framework\TestCase;
use Wexample\SymfonyDesignSystemEffects\Avatar\Wexies2020AvatarEngine;

class Wexies2020AvatarEngineTest extends TestCase
{
    public function testTheTwentySixPairsOfEyes(): void
    {
        $eyes = (new Wexies2020AvatarEngine())->getEyes();

        $this->assertSame(range('a', 'z'), array_keys($eyes));
        $this->assertNotContains('', $eyes);
    }

    public function testTheSameSeedDrawsTheSameFace(): void
    {
        $engine = new Wexies2020AvatarEngine();
        $face = $engine->render('01a1338a-e899-7498-aadd-b03d9cab1eee');

        $this->assertSame($face, $engine->render('01a1338a-e899-7498-aadd-b03d9cab1eee', 'A name changes nothing'));
        $this->assertNotFalse(simplexml_load_string($face));
    }

    public function testSeedsSpreadOverTheFaces(): void
    {
        $engine = new Wexies2020AvatarEngine();
        $faces = array_map(fn (int $seed) => $engine->render((string) $seed), range(1, 100));

        $this->assertGreaterThan(90, count(array_unique($faces)));
    }
}
