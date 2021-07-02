<?php

declare(strict_types=1);

namespace Phpactor\Extension\PHPUnit\Tests\Unit;

use Phpactor\Container\PhpactorContainer;
use Phpactor\Extension\ClassToFile\ClassToFileExtension;
use Phpactor\Extension\ComposerAutoloader\ComposerAutoloaderExtension;
use Phpactor\Extension\Logger\LoggingExtension;
use Phpactor\Extension\WorseReflection\WorseReflectionExtension;
use Phpactor\FilePathResolverExtension\FilePathResolverExtension;
use Phpactor\WorseReflection\Reflector;
use PHPUnit\Framework\TestCase;
use SlamPhpactor\Extension\SlamExtension;

final class SlamExtensionTest extends TestCase
{
    public function testLoad(): void
    {
        $container = PhpactorContainer::fromExtensions([
            WorseReflectionExtension::class,
            SlamExtension::class,
            FilePathResolverExtension::class,
            ClassToFileExtension::class,
            ComposerAutoloaderExtension::class,
            LoggingExtension::class,
        ], [
            FilePathResolverExtension::PARAM_APPLICATION_ROOT => __DIR__,
        ]);

        $reflector = $container->get(WorseReflectionExtension::SERVICE_REFLECTOR);
        self::assertInstanceOf(Reflector::class, $reflector);
    }
}
