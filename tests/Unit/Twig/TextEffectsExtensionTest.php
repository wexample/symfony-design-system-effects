<?php

declare(strict_types=1);

namespace Wexample\SymfonyDesignSystemEffects\Tests\Unit\Twig;

use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Wexample\SymfonyDesignSystemEffects\Twig\TextEffectsExtension;
use Wexample\SymfonyLoader\Rendering\RenderPass;
use Wexample\SymfonyLoader\Twig\ComponentsExtension;

/**
 * What the two effects hand the loader: which component, what reaches the
 * browser as options and what only the twig drawing it reads, with their
 * defaults.
 */
class TextEffectsExtensionTest extends TestCase
{
    public function testBothFunctionsAreRegisteredAsHtml(): void
    {
        $extension = new TextEffectsExtension($this->createStub(ComponentsExtension::class));
        $functions = [];

        foreach ($extension->getFunctions() as $function) {
            $functions[$function->getName()] = $function;
        }

        $this->assertSame(['text_highlight', 'word_switcher'], array_keys($functions));

        foreach ($functions as $function) {
            $this->assertTrue($function->needsContext());
            $this->assertTrue($function->needsEnvironment());
        }
    }

    public function testAHighlightIsAquaUnlessToldOtherwise(): void
    {
        $renderPass = $this->createStub(RenderPass::class);
        $twig = $this->createStub(Environment::class);

        $components = $this->createMock(ComponentsExtension::class);
        $components->expects($this->exactly(2))
            ->method('component')
            ->willReturnCallback(function (Environment $passedTwig, RenderPass $passedPass, string $path, array $options, array $vars) use ($twig, $renderPass): string {
                $this->assertSame($twig, $passedTwig);
                $this->assertSame($renderPass, $passedPass);
                $this->assertSame('@WexampleSymfonyDesignSystemEffectsBundle/components/text-highlight', $path);
                // Nothing of a highlight is for the browser.
                $this->assertSame([], $options);

                return $vars['tone'].'|'.($vars['class'] ?? '').'|'.$vars['text'];
            });

        $extension = new TextEffectsExtension($components);
        $context = ['render_pass' => $renderPass];

        $this->assertSame('aqua||sur-mesure', $extension->textHighlight($twig, $context, 'sur-mesure'));
        $this->assertSame(
            'grass|loud|durables',
            $extension->textHighlight($twig, $context, 'durables', ['tone' => 'grass', 'class' => 'loud'])
        );
    }

    public function testASwitcherSendsItsWordsAndItsPaceToTheBrowser(): void
    {
        $renderPass = $this->createStub(RenderPass::class);
        $calls = [];

        $components = $this->createStub(ComponentsExtension::class);
        $components->method('component')
            ->willReturnCallback(function (Environment $twig, RenderPass $pass, string $path, array $options, array $vars) use (&$calls): string {
                $calls[] = [$path, $options, $vars];

                return '';
            });

        $extension = new TextEffectsExtension($components);
        $twig = $this->createStub(Environment::class);
        $context = ['render_pass' => $renderPass];

        // Keys of the list are dropped: the browser reads a list, in order.
        $extension->wordSwitcher($twig, $context, ['a' => 'inclusif', 'b' => 'solidaire']);
        $extension->wordSwitcher($twig, $context, ['généreux'], ['interval' => 3000, 'roll' => 200, 'class' => 'hero']);

        $this->assertSame([
            [
                '@WexampleSymfonyDesignSystemEffectsBundle/components/word-switcher',
                ['words' => ['inclusif', 'solidaire'], 'interval' => 2000, 'roll' => 500],
                ['words' => ['inclusif', 'solidaire'], 'class' => null],
            ],
            [
                '@WexampleSymfonyDesignSystemEffectsBundle/components/word-switcher',
                ['words' => ['généreux'], 'interval' => 3000, 'roll' => 200],
                ['words' => ['généreux'], 'class' => 'hero'],
            ],
        ], $calls);
    }
}
