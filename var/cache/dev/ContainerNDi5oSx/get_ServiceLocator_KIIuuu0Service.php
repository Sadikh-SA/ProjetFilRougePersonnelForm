<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private '.service_locator.KIIuuu0' shared service.

return $this->privates['.service_locator.KIIuuu0'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($this->getService, [
    'UtilisateurRepository' => ['privates', 'App\\Repository\\UtilisateurRepository', 'getUtilisateurRepositoryService.php', true],
    'entityManager' => ['services', 'doctrine.orm.default_entity_manager', 'getDoctrine_Orm_DefaultEntityManagerService', false],
], [
    'UtilisateurRepository' => 'App\\Repository\\UtilisateurRepository',
    'entityManager' => '?',
]);
