<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'master' => [
            'stocks' => 'c,r,u,d',
            'users' => 'c,r,u,d',
            'roles' => 'c,r,u,d',
            'categories' => 'c,r,u,d',
            'sous_categories' => 'c,r,u,d',
            'type_entites' => 'c,r,u,d',
            'marques' => 'c,r,u,d',
            'devises' => 'c,r,u,d',
            'unite_reglementaires' => 'c,r,u,d',
            'groupes' => 'c,r,u,d',
            'produits' => 'c,r,u,d',
            'pays' => 'c,r,u,d',
            'villes' => 'c,r,u,d',
            'fournisseurs' => 'c,r,u,d',
            'magasins' => 'c,r,u,d',
            'entites' => 'c,r,u,d',
            'commandes' => 'c,r,u,d',
            'conventions' => 'c,r,u,d',
            'marches' => 'c,r,u,d',
            'bl' => 'c,r,u,d',
            'inventaires' => 'c,r,u,d,v,f,p',
            'demandes' => 'c,r,u,d',
            'demandes_extern' => 'c,r,u,d',
            'bs' => 'c,r,u,d,a',
            'roles' => 'c,r,u,d',
        ],

    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
        'v' => 'validation',
        'f' => 'verification',
        'p' => 'preparation',
        'a' => 'annulation',
    ]
];
