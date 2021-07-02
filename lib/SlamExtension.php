<?php

declare(strict_types=1);

namespace SlamPhpactor\Extension;

use Phpactor\Container\Container;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\Extension\WorseReflection\WorseReflectionExtension;
use Phpactor\MapResolver\Resolver;
use SlamPhpactor\Extension\FrameWalker\PsrContainerWalker;

final class SlamExtension implements Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(ContainerBuilder $container): void
    {
        $this->registerWorseReflection($container);
    }

    /**
     * {@inheritDoc}
     */
    public function configure(Resolver $schema): void
    {
    }

    private function registerWorseReflection(ContainerBuilder $container): void
    {
        $container->register('slam_extension.frame_walker.psr_container', function (Container $container) {
            return new PsrContainerWalker();
        }, [WorseReflectionExtension::TAG_FRAME_WALKER => []]);
    }
}
