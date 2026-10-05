<?php

return [
    'titre'       => env('FORMATION_TITRE', 'Formation pluridisciplinaire en informatique et multimédia'),
    'description' => env('FORMATION_DESCRIPTION', ' A la fin de la formation, les participants seront capables de : créer des sites web, gérer des bases de données, concevoir des affiches publicitaires, et utiliser l’intelligence artificielle avec les meilleurs prompts pour améliorer leur productivité. une attestation sera délivrée à chaque participant ayant suivi la formation et un suivi sera assuré pendant un moi.'),
    'dates'       => env('FORMATION_DATES', 'Debut novembre'),
    'lieu'        => env('FORMATION_LIEU', 'secteur 22 ,bobodioulasso'),
    'contact'     => env('FORMATION_CONTACT', '+226 05-90-61-75 ,podag808@gmail.com'),   // WhatsApp / email affiché en cas de problème

    'devise'      => 'XOF',

    // Packs de formation : le participant en choisit un ou plusieurs. Le total est calculé côté serveur.
    'packs' => [
        'bureautique_affiches' => [
            'libelle' => 'Bureautique + Affiches publicitaires',
            'inclus'  => 'Bureautique · Affiches publicitaires',
            'icone'   => '📊',
            'prix'    => 15000,
        ],
        'web_maintenance_ia' => [
            'libelle' => 'Web + Maintenance + IA',
            'inclus'  => 'Web · Maintenance · Intelligence artificielle',
            'icone'   => '💻',
            'prix'    => 20000,
        ],
    ],

    // Laisser vide = places illimitées
    'places'      => env('FORMATION_PLACES') ? (int) env('FORMATION_PLACES') : null,

    // Ce que vous écrivez ici est ce que le participant voit : mettez les numéros sur lesquels il doit payer
    'paiement' => [
        'orange'    => env('PAIEMENT_ORANGE_NUMERO'),
        'moov'      => env('PAIEMENT_MOOV_NUMERO'),
        'titulaire' => env('PAIEMENT_TITULAIRE'),   // nom affiché par l'opérateur au moment du transfert
    ],

    'delai_verification' => env('FORMATION_DELAI_VERIFICATION', 'dans les meilleurs délais'),

    // Message WhatsApp préparé pour chaque inscrit validé. Variables : :prenom :titre :dates :lieu :contact
    'message_whatsapp' => env(
        'FORMATION_MESSAGE_WHATSAPP',
        "Bonjour :prenom, votre inscription à « :titre » est confirmée. Rappel : :dates, :lieu. À très bientôt !"
    ),

    // Accès à la page d'administration (mot de passe stocké sous forme de hash bcrypt)
    'admin' => [
        'user'          => env('ADMIN_USER'),
        'password_hash' => env('ADMIN_PASSWORD_HASH'),
    ],
];