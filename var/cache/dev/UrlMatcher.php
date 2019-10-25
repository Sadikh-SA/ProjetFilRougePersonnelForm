<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/filrouge/compte/ajouter' => [[['_route' => 'compte_ajout', '_controller' => 'App\\Controller\\CompteDepotController::creerCompte'], null, ['POST' => 0], null, false, false, null]],
        '/filrouge/partenaire/ajouter' => [[['_route' => 'compte_ajouter', '_controller' => 'App\\Controller\\CompteDepotController::creerPartenaire'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
        '/filrouge/fairedepot' => [[['_route' => 'faire_depot', '_controller' => 'App\\Controller\\CompteDepotController::faireDepot'], null, ['POST' => 0], null, false, false, null]],
        '/filrouge/bloquer/users' => [[['_route' => 'bloquer_user_1', '_controller' => 'App\\Controller\\CompteDepotController::bloquerUsers'], null, ['POST' => 0, 'PUT' => 1], null, false, false, null]],
        '/filrouge/bloquer/partenaire' => [[['_route' => 'bloquer_unpartenaire', '_controller' => 'App\\Controller\\CompteDepotController::BloquerParte'], null, ['PUT' => 0, 'POST' => 1], null, false, false, null]],
        '/filrouge/lister/compte' => [[['_route' => 'lister_compte', '_controller' => 'App\\Controller\\CompteDepotController::listercompte'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
        '/filrouge/lister/depot' => [[['_route' => 'lister_depot', '_controller' => 'App\\Controller\\CompteDepotController::listerdepot'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
        '/filrouge/select/user' => [[['_route' => 'lister_un_utilisateur_quelconque', '_controller' => 'App\\Controller\\CompteDepotController::selectUser'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
        '/filrouge/select/compte' => [[['_route' => 'lister_un_compte_quelconque', '_controller' => 'App\\Controller\\CompteDepotController::selectCompte'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
        '/filrouge/select/partenaire' => [[['_route' => 'lister_un_partenaire_quelconque', '_controller' => 'App\\Controller\\CompteDepotController::selectPartenaire'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
        '/filrouge/select/profil' => [[['_route' => 'lister_un_profil_quelconque', '_controller' => 'App\\Controller\\CompteDepotController::selectProfil'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
        '/filrouge/lister/profil' => [[['_route' => 'lister_tous_profil', '_controller' => 'App\\Controller\\CompteDepotController::listerProfil'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
        '/filrouge/attribuer/compte' => [[['_route' => 'attribuer_compte', '_controller' => 'App\\Controller\\CompteDepotController::attribuerCompte'], null, ['POST' => 0, 'PUT' => 1], null, false, false, null]],
        '/filrouge/lister/compte/un/partenaire' => [[['_route' => 'listuer_compte', '_controller' => 'App\\Controller\\CompteDepotController::FunctionName'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
        '/filrouge/ajouter/partenaire' => [[['_route' => 'ajouter_les_3', '_controller' => 'App\\Controller\\FilRougeController::ajout'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
        '/filrouge/ajoutprofil' => [[['_route' => 'profil', '_controller' => 'App\\Controller\\FilRougeController::NewProfil'], null, ['POST' => 0], null, false, false, null]],
        '/filrouge/lister/user' => [[['_route' => 'lister_user', '_controller' => 'App\\Controller\\FilRougeController::listeruser'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
        '/filrouge/lister/partenaire' => [[['_route' => 'lister_partenaire', '_controller' => 'App\\Controller\\FilRougeController::listerpartenaire'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
        '/filrouge/lister/user/partenaire' => [[['_route' => 'Liseter', '_controller' => 'App\\Controller\\FilRougeController::listerUserPartenaire'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
        '/filrouge/login' => [[['_route' => 'login', '_controller' => 'App\\Controller\\FilRougeController::login'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
        '/generer/pdf' => [[['_route' => 'generer_pdf', '_controller' => 'App\\Controller\\GenererPDFController::index'], null, null, null, false, false, null]],
        '/filrouge/faire/envoie' => [[['_route' => 'envoyer_argent', '_controller' => 'App\\Controller\\TransactionController::envoyerArgent'], null, ['POST' => 0], null, false, false, null]],
        '/filrouge/faire/retrait' => [[['_route' => 'retrait_argent', '_controller' => 'App\\Controller\\TransactionController::retraitArgent'], null, ['PUT' => 0, 'POST' => 1], null, false, false, null]],
        '/filrouge/lister/transaction' => [[['_route' => 'lister_transaction', '_controller' => 'App\\Controller\\TransactionController::listertransaction'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
        '/filrouge/lister/transaction/date' => [[['_route' => 'transaction_date', '_controller' => 'App\\Controller\\TransactionController::transaction'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
        '/filrouge/select/transaction' => [[['_route' => 'lister_un_transaction_quelconque', '_controller' => 'App\\Controller\\TransactionController::selectTransaction'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
        '/filrouge/select/transaction/partenaire' => [[['_route' => 'lister_les_transactions_d_un_partenaire_quelconque', '_controller' => 'App\\Controller\\TransactionController::selectTransactionPartenaire'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
        '/filrouge/select/transaction/user' => [[['_route' => 'lister_les transactions_d_un_partenaire1_quelconque', '_controller' => 'App\\Controller\\TransactionController::selectTransacPartenaire'], null, ['POST' => 0, 'GET' => 1], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/filrouge/update/(?'
                    .'|user/([^/]++)(*:75)'
                    .'|compte/([^/]++)(*:97)'
                    .'|depot/([^/]++)(*:118)'
                    .'|partenaire/([^/]++)(*:145)'
                .')'
                .'|/api(?'
                    .'|(?:/(index)(?:\\.([^/]++))?)?(*:189)'
                    .'|/(?'
                        .'|d(?'
                            .'|ocs(?:\\.([^/]++))?(*:223)'
                            .'|epots(?'
                                .'|(?:\\.([^/]++))?(?'
                                    .'|(*:257)'
                                .')'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                    .'|(*:295)'
                                .')'
                            .')'
                        .')'
                        .'|co(?'
                            .'|ntexts/(.+)(?:\\.([^/]++))?(*:337)'
                            .'|m(?'
                                .'|ptes(?'
                                    .'|(?:\\.([^/]++))?(?'
                                        .'|(*:374)'
                                    .')'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                        .'|(*:412)'
                                    .')'
                                .')'
                                .'|missions(?'
                                    .'|(?:\\.([^/]++))?(?'
                                        .'|(*:451)'
                                    .')'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                        .'|(*:489)'
                                    .')'
                                .')'
                            .')'
                        .')'
                        .'|transactions(?'
                            .'|(?:\\.([^/]++))?(?'
                                .'|(*:534)'
                            .')'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                .'|(*:572)'
                            .')'
                        .')'
                        .'|p(?'
                            .'|artenaires(?'
                                .'|(?:\\.([^/]++))?(?'
                                    .'|(*:617)'
                                .')'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                    .'|(*:655)'
                                .')'
                            .')'
                            .'|rofils(?'
                                .'|(?:\\.([^/]++))?(?'
                                    .'|(*:692)'
                                .')'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                    .'|(*:730)'
                                .')'
                            .')'
                        .')'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_twig_error_test', '_controller' => 'twig.controller.preview_error::previewErrorPageAction', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        75 => [[['_route' => 'modifier_user', '_controller' => 'App\\Controller\\CompteDepotController::Update'], ['id'], ['PUT' => 0, 'POST' => 1], null, false, true, null]],
        97 => [[['_route' => 'modifier_compte', '_controller' => 'App\\Controller\\CompteDepotController::UpdateCompte'], ['id'], ['PUT' => 0, 'POST' => 1], null, false, true, null]],
        118 => [[['_route' => 'modifier_depot', '_controller' => 'App\\Controller\\CompteDepotController::UpdateDepot'], ['id'], ['PUT' => 0, 'POST' => 1], null, false, true, null]],
        145 => [[['_route' => 'modifier_partenaire', '_controller' => 'App\\Controller\\CompteDepotController::UpdatePartenaire'], ['id'], ['PUT' => 0, 'POST' => 1], null, false, true, null]],
        189 => [[['_route' => 'api_entrypoint', '_controller' => 'api_platform.action.entrypoint', '_format' => '', '_api_respond' => 'true', 'index' => 'index'], ['index', '_format'], null, null, false, true, null]],
        223 => [[['_route' => 'api_doc', '_controller' => 'api_platform.action.documentation', '_format' => '', '_api_respond' => 'true'], ['_format'], null, null, false, true, null]],
        257 => [
            [['_route' => 'api_depots_get_collection', '_controller' => 'api_platform.action.get_collection', '_format' => null, '_api_resource_class' => 'App\\Entity\\Depot', '_api_collection_operation_name' => 'get'], ['_format'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_depots_post_collection', '_controller' => 'api_platform.action.post_collection', '_format' => null, '_api_resource_class' => 'App\\Entity\\Depot', '_api_collection_operation_name' => 'post'], ['_format'], ['POST' => 0], null, false, true, null],
        ],
        295 => [
            [['_route' => 'api_depots_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_api_resource_class' => 'App\\Entity\\Depot', '_api_item_operation_name' => 'get'], ['id', '_format'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_depots_delete_item', '_controller' => 'api_platform.action.delete_item', '_format' => null, '_api_resource_class' => 'App\\Entity\\Depot', '_api_item_operation_name' => 'delete'], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
            [['_route' => 'api_depots_put_item', '_controller' => 'api_platform.action.put_item', '_format' => null, '_api_resource_class' => 'App\\Entity\\Depot', '_api_item_operation_name' => 'put'], ['id', '_format'], ['PUT' => 0], null, false, true, null],
        ],
        337 => [[['_route' => 'api_jsonld_context', '_controller' => 'api_platform.jsonld.action.context', '_format' => 'jsonld', '_api_respond' => 'true'], ['shortName', '_format'], null, null, false, true, null]],
        374 => [
            [['_route' => 'api_comptes_get_collection', '_controller' => 'api_platform.action.get_collection', '_format' => null, '_api_resource_class' => 'App\\Entity\\Compte', '_api_collection_operation_name' => 'get'], ['_format'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_comptes_post_collection', '_controller' => 'api_platform.action.post_collection', '_format' => null, '_api_resource_class' => 'App\\Entity\\Compte', '_api_collection_operation_name' => 'post'], ['_format'], ['POST' => 0], null, false, true, null],
        ],
        412 => [
            [['_route' => 'api_comptes_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_api_resource_class' => 'App\\Entity\\Compte', '_api_item_operation_name' => 'get'], ['id', '_format'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_comptes_delete_item', '_controller' => 'api_platform.action.delete_item', '_format' => null, '_api_resource_class' => 'App\\Entity\\Compte', '_api_item_operation_name' => 'delete'], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
            [['_route' => 'api_comptes_put_item', '_controller' => 'api_platform.action.put_item', '_format' => null, '_api_resource_class' => 'App\\Entity\\Compte', '_api_item_operation_name' => 'put'], ['id', '_format'], ['PUT' => 0], null, false, true, null],
        ],
        451 => [
            [['_route' => 'api_commissions_get_collection', '_controller' => 'api_platform.action.get_collection', '_format' => null, '_api_resource_class' => 'App\\Entity\\Commission', '_api_collection_operation_name' => 'get'], ['_format'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_commissions_post_collection', '_controller' => 'api_platform.action.post_collection', '_format' => null, '_api_resource_class' => 'App\\Entity\\Commission', '_api_collection_operation_name' => 'post'], ['_format'], ['POST' => 0], null, false, true, null],
        ],
        489 => [
            [['_route' => 'api_commissions_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_api_resource_class' => 'App\\Entity\\Commission', '_api_item_operation_name' => 'get'], ['id', '_format'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_commissions_delete_item', '_controller' => 'api_platform.action.delete_item', '_format' => null, '_api_resource_class' => 'App\\Entity\\Commission', '_api_item_operation_name' => 'delete'], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
            [['_route' => 'api_commissions_put_item', '_controller' => 'api_platform.action.put_item', '_format' => null, '_api_resource_class' => 'App\\Entity\\Commission', '_api_item_operation_name' => 'put'], ['id', '_format'], ['PUT' => 0], null, false, true, null],
        ],
        534 => [
            [['_route' => 'api_transactions_get_collection', '_controller' => 'api_platform.action.get_collection', '_format' => null, '_api_resource_class' => 'App\\Entity\\Transaction', '_api_collection_operation_name' => 'get'], ['_format'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_transactions_post_collection', '_controller' => 'api_platform.action.post_collection', '_format' => null, '_api_resource_class' => 'App\\Entity\\Transaction', '_api_collection_operation_name' => 'post'], ['_format'], ['POST' => 0], null, false, true, null],
        ],
        572 => [
            [['_route' => 'api_transactions_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_api_resource_class' => 'App\\Entity\\Transaction', '_api_item_operation_name' => 'get'], ['id', '_format'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_transactions_delete_item', '_controller' => 'api_platform.action.delete_item', '_format' => null, '_api_resource_class' => 'App\\Entity\\Transaction', '_api_item_operation_name' => 'delete'], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
            [['_route' => 'api_transactions_put_item', '_controller' => 'api_platform.action.put_item', '_format' => null, '_api_resource_class' => 'App\\Entity\\Transaction', '_api_item_operation_name' => 'put'], ['id', '_format'], ['PUT' => 0], null, false, true, null],
        ],
        617 => [
            [['_route' => 'api_partenaires_get_collection', '_controller' => 'api_platform.action.get_collection', '_format' => null, '_api_resource_class' => 'App\\Entity\\Partenaire', '_api_collection_operation_name' => 'get'], ['_format'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_partenaires_post_collection', '_controller' => 'api_platform.action.post_collection', '_format' => null, '_api_resource_class' => 'App\\Entity\\Partenaire', '_api_collection_operation_name' => 'post'], ['_format'], ['POST' => 0], null, false, true, null],
        ],
        655 => [
            [['_route' => 'api_partenaires_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_api_resource_class' => 'App\\Entity\\Partenaire', '_api_item_operation_name' => 'get'], ['id', '_format'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_partenaires_delete_item', '_controller' => 'api_platform.action.delete_item', '_format' => null, '_api_resource_class' => 'App\\Entity\\Partenaire', '_api_item_operation_name' => 'delete'], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
            [['_route' => 'api_partenaires_put_item', '_controller' => 'api_platform.action.put_item', '_format' => null, '_api_resource_class' => 'App\\Entity\\Partenaire', '_api_item_operation_name' => 'put'], ['id', '_format'], ['PUT' => 0], null, false, true, null],
        ],
        692 => [
            [['_route' => 'api_profils_get_collection', '_controller' => 'api_platform.action.get_collection', '_format' => null, '_api_resource_class' => 'App\\Entity\\Profil', '_api_collection_operation_name' => 'get'], ['_format'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_profils_post_collection', '_controller' => 'api_platform.action.post_collection', '_format' => null, '_api_resource_class' => 'App\\Entity\\Profil', '_api_collection_operation_name' => 'post'], ['_format'], ['POST' => 0], null, false, true, null],
        ],
        730 => [
            [['_route' => 'api_profils_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_api_resource_class' => 'App\\Entity\\Profil', '_api_item_operation_name' => 'get'], ['id', '_format'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_profils_delete_item', '_controller' => 'api_platform.action.delete_item', '_format' => null, '_api_resource_class' => 'App\\Entity\\Profil', '_api_item_operation_name' => 'delete'], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
            [['_route' => 'api_profils_put_item', '_controller' => 'api_platform.action.put_item', '_format' => null, '_api_resource_class' => 'App\\Entity\\Profil', '_api_item_operation_name' => 'put'], ['id', '_format'], ['PUT' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
