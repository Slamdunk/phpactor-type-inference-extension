<?php

namespace SlamPhpactor\Extension\Tests\Unit\FrameWalker;

use Closure;
use Generator;
use PHPUnit\Framework\TestCase;
use SlamPhpactor\Extension\FrameWalker\PsrContainerWalker;
use Phpactor\TestUtils\ExtractOffset;
use Phpactor\WorseReflection\Core\Inference\Frame;
use Phpactor\WorseReflection\Reflector;
use Phpactor\WorseReflection\ReflectorBuilder;

final class PsrContainerWalkerTest extends TestCase
{
    /**
     * @dataProvider provideWalk
     */
    public function testWalk(string $source, Closure $assertion): void
    {
        list($source, $offset) = ExtractOffset::fromSource($source);
        $reflector = $this->createReflector($source);
        $reflectionOffset = $reflector->reflectOffset($source, $offset);
        $assertion($reflectionOffset->frame(), $offset);
    }

    public function provideWalk(): Generator
    {
        yield 'no op' => [
            <<<'EOT'
<?php

<>
EOT
            , static function (Frame $frame): void {
                self::assertCount(0, $frame->locals());
            }
        ];

        yield 'base functionality' => [
            <<<'EOT'
<?php

$container = new class implements \Psr\Container\ContainerInterface {};

$foo = $container->get(stdClass::class);
<>
EOT
            , static function (Frame $frame): void {
                $variable = $frame->locals()->byName('foo')->last();
                self::assertEquals('stdClass', $variable->symbolContext()->type()->__toString());
            }
        ];
    }

    private function createReflector($source): Reflector
    {
        return ReflectorBuilder::create()
            ->addSource($source)
            ->addFrameWalker(new PsrContainerWalker())
            ->build();
    }
}
