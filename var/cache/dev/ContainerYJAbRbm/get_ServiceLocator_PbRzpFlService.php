<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private '.service_locator.pbRzpFl' shared service.

return $this->privates['.service_locator.pbRzpFl'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($this->getService, [
    'TransactionRepository' => ['privates', 'App\\Repository\\TransactionRepository', 'getTransactionRepositoryService.php', true],
    'serializer' => ['services', 'serializer', 'getSerializerService', false],
], [
    'TransactionRepository' => 'App\\Repository\\TransactionRepository',
    'serializer' => '?',
]);
