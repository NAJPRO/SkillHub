<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Définition des rôles
        $roles = [
            'super-admin',
            'admin',
            'client',
            'freelance',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Définition des permissions
        $permissions = [
            // Gestion des utilisateurs et des rôles
            'view_users', 'create_users', 'delete_users', 'update_users', 'delete_users',
            'assign_roles', 'revoke_roles', 'manage_users',

            // Gestion des services des freelances (Gigs)
            'view_gigs', 'create_gigs', 'delete_gigs', 'approve_gigs', 'update_gigs', 'delete_gigs',

            // Gestion des annonces des clients (Requests)
            'view_requests', 'create_requests', 'edit_requests', 'delete_requests',

            // Gestion des propositions (Bids)
            'view_bids', 'place_bids', 'accept_bids', 'reject_bids',

            // Gestion des commandes (Orders)
            'view_orders', 'create_orders', 'cancel_orders', 'complete_orders', 'refund_orders',

            // Gestion des paiements
            'view_payments', 'process_payments', 'refund_payments',

            // Gestion des avis et notations
            'view_reviews', 'write_reviews', 'delete_reviews',

            // Gestion des messages et notifications
            'view_messages', 'send_messages', 'delete_messages',
            'view_notifications',

            // Gestion des catégories
            'view_categories', 'create_categories', 'edit_categories', 'delete_categories',

            // Gestion du tableau de bord et exportation
            'view_dashboard', 'export_data',

            'show_menu_managePermission', 'manage_permission', 'manage_permission_for_user', 'manage_admin', 'manage_freelance',
            'show_new_users_last_30_days', 'show_userStatusBarChart', 'show_usersByTypeChart',

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Attribution des permissions aux rôles

        // Super Admin a **toutes** les permissions
        $superAdmin = Role::where('name', 'super-admin')->first();
        $superAdmin->givePermissionTo(Permission::all());

        // L'Admin peut gérer les utilisateurs, les services, et modérer
        $admin = Role::where('name', 'admin')->first();
        $admin->givePermissionTo([
            'view_users', 'update_users', 'delete_users', 'assign_roles',
            'view_gigs', 'approve_gigs',
            'view_requests',
            'view_orders',
            'view_payments', 'process_payments',
            'view_reviews', 'delete_reviews',
            'view_messages',
            'view_dashboard',
            'manage_users', 'manage_freelance',
        ]);

        // Le Client peut **poster des annonces** et **passer des commandes**
        $client = Role::where('name', 'client')->first();
        $client->givePermissionTo([
            'view_gigs', 'create_orders',
            'view_requests', 'create_requests', 'edit_requests', 'delete_requests',
            'view_bids', 'accept_bids',
            'view_messages', 'send_messages',
            'view_orders', 'cancel_orders', 'complete_orders',
            'view_notifications',
            'write_reviews',
        ]);

        // Le Freelance peut **poster des services** et **répondre aux annonces des clients**
        $freelance = Role::where('name', 'freelance')->first();
        $freelance->givePermissionTo([
            'view_requests', 'place_bids',
            'view_gigs', 'create_gigs', 'update_gigs', 'delete_gigs',
            'view_orders', 'complete_orders',
            'view_payments',
            'view_messages', 'send_messages',
            'view_notifications',
            'write_reviews',
        ]);
    }
}
