<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private '.service_locator.wThH0TN' shared service.

return $this->privates['.service_locator.wThH0TN'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($this->getService, [
    'partenaireRepository' => ['privates', 'App\\Repository\\PartenaireRepository', 'getPartenaireRepositoryService.php', true],
    'validator' => ['services', 'validator', 'getValidatorService', false],
], [
    'partenaireRepository' => 'App\\Repository\\PartenaireRepository',
    'validator' => '?',
]);
