<?php

return [
    'table' => [
        'photo' => 'Photo',
        'nom' => 'Nom',
        'email' => 'Email',
        'phone_number' => 'Téléphone',
        'status' => 'Status',
        'role' => 'Rôle',
        'link_site_web' => 'Site Web',
        'link_github' => 'Github',
        'link_instagram' => 'Instagram',
        'link_linkedin' => 'Linkedin',
    ],
    'status' => [
        'active' => 'Actif',
        'inactive' => 'Inactif',
        'suspended' => 'Suspendu',
        'banished' => 'Banni',
        'not-verified' => 'Non vérifié',
    ],
    'role' => [
        'client' => 'Client',
        'freelance' => 'Freelance',
        'admin' => 'Administrateur',
        'super-admin' => 'Super Admin',
    ],
    'default' => 'Non fourni',


    'section' => [
        'general' => 'Informations Générales',
        'general_description' => "Informations de base de l'utilisateur",
        'contact' => 'Coordonnées',
        'contact_description' => 'Informations de contact',
        'social' => 'Réseaux sociaux',
        'social_description' => 'Profils de réseaux sociaux',
        'settings' => 'Paramètres',
        'settings_description' => 'Règles et permissions utilisateur',
        'security' => 'Sécurité',
        'security_description' => 'Gestion du mot de passe'
    ],

    'password' => 'Mot de passe',
    'password_confirm' => 'Confirmer le mot de passe',

    'notification' => [
        'created' => 'Utilisateur créé avec succès !',
        'update_user_title' => 'Utilisateur mis à jour',
        'update_user_message' => 'L\'utilisateur a été mis à jour avec succès',
        'create_user_title' => 'Utilisateur créé',
        'create_user_message' => 'L\'utilisateur à été créé avec succès',
    ],



    'create' => 'Ajouter un utilisateur',
    'edit_title' => 'Modifier - :name',
    'create_title' => 'Créer un utilisateur',


    // Dashboard



    'total_users' => 'Total des utilisateurs',
    'new_users_last_30_days' => 'Nouveaux utilisateurs (30 derniers jours)',
    'user_distribution' => 'Répartition des utilisateurs par type',
    'inscription' => 'Inscriptions',
    'number_user' => 'Nombre d\'utilisateurs',
    'userReportByStatus' => 'Rapport des utilisateurs par statut',
   // 'new_user' => 'Nouveaux utilisateurs (30 derniers jours)'
];
