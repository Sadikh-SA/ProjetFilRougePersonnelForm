<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerSMmV3xi\srcApp_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerSMmV3xi/srcApp_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerSMmV3xi.legacy');

    return;
}

if (!\class_exists(srcApp_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerSMmV3xi\srcApp_KernelDevDebugContainer::class, srcApp_KernelDevDebugContainer::class, false);
}

return new \ContainerSMmV3xi\srcApp_KernelDevDebugContainer([
    'container.build_hash' => 'SMmV3xi',
    'container.build_id' => '2aff0f2b',
    'container.build_time' => 1565722331,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerSMmV3xi');
