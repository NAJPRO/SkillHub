<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\info;
use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class CreateUser extends Command
{
    protected $signature = 'user:create';

    protected $description = 'Créer un nouvel utilisateur avec un email validé';

    public function handle()
    {
        // Demander le nom avec un champ de texte
        $name = text(
            label: 'Quel est votre nom ?',
            placeholder: 'Ex. Taylor Otwell',
            hint: 'Ce nom sera affiché sur votre profil.',
            required: true
        );

        // Demander l'email avec un champ de texte
        $email = text(
            label: 'Entrez l\'email de l\'utilisateur',
            placeholder: 'Ex. utilisateur@example.com',
            required: true
        );

        // Demander le mot de passe avec un champ masqué
        $password = password(
            label: 'Entrez le mot de passe de l\'utilisateur',
            placeholder: 'Mot de passe',
            required: true
        );

        // Demander le type d'utilisateur avec un choix
        $choix = select(
            label: 'Quel type d\'utilisateur voulez-vous créer ?',
            options: [
                'Client',
                'Freelance',
                'Administrateur',
                'Super Administrateur',

            ],
            required: true
        );

        $role = null;
        switch ($choix) {
            case 'Client':
                $role = 'client';
                break;
            case 'Administrateur':
                $role = 'admin';
                break;
            case 'Freelance':
                $role = 'freelance';
                break;
            case 'Super Administrateur':
                $role = 'super-admin';
                break;
            default:
                $this->error('Option invalide, l\'utilisateur n\'a pas été créé.');

                return; // Quitter la fonction
        }

        // Créer l'utilisateur
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(), // Marquer l'email comme vérifié
            'role' => $role,
        ]);
        $user->save();
        $user->assignRole($role);

        // Afficher un message de succès
        info('Utilisateur créé avec succès : ');
        info("Email : {$user->email}");
        info("Mot de passe : {$password}");

    }
}
