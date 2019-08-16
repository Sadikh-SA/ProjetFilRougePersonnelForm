<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private '.service_locator.Y88JtWp' shared service.

return $this->privates['.service_locator.Y88JtWp'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($this->getService, [
    'commissionRepository' => ['privates', 'App\\Repository\\CommissionRepository', 'getCommissionRepositoryService.php', true],
    'compteRepository' => ['privates', 'App\\Repository\\CompteRepository', 'getCompteRepositoryService.php', true],
], [
    'commissionRepository' => 'App\\Repository\\CommissionRepository',
    'compteRepository' => 'App\\Repository\\CompteRepository',
]);