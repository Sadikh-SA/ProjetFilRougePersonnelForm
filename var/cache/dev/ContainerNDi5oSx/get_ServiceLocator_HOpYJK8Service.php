<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private '.service_locator.HOpYJK8' shared service.

return $this->privates['.service_locator.HOpYJK8'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($this->getService, [
    'serializer' => ['services', 'serializer', 'getSerializerService', false],
    'utilisateurRepository' => ['privates', 'App\\Repository\\UtilisateurRepository', 'getUtilisateurRepositoryService.php', true],
], [
    'serializer' => '?',
    'utilisateurRepository' => 'App\\Repository\\UtilisateurRepository',
]);
