<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerNDi5oSx\srcApp_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerNDi5oSx/srcApp_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerNDi5oSx.legacy');

    return;
}

if (!\class_exists(srcApp_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerNDi5oSx\srcApp_KernelDevDebugContainer::class, srcApp_KernelDevDebugContainer::class, false);
}

return new \ContainerNDi5oSx\srcApp_KernelDevDebugContainer([
    'container.build_hash' => 'NDi5oSx',
    'container.build_id' => '8bcf1b41',
    'container.build_time' => 1572001407,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerNDi5oSx');
