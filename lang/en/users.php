<?php

return [

    'table' => [
        'photo' => 'Picture',
        'nom' => 'Name',
        'email' => 'Email',
        'phone_number' => 'Phone Number',
        'status' => 'Status',
        'role' => 'Role',
        'link_site_web' => 'Website',
        'link_github' => 'Github',
        'link_instagram' => 'Instagram',
        'link_linkedin' => 'Linkedin',

    ],
    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'suspended' => 'Suspended',
        'banished' => 'Banished',
        'not-verified' => 'Not Verified',
    ],
    'role' => [
        'client' => 'Client',
        'freelance' => 'Freelance',
        'admin' => 'Administrator',
        'super-admin' => 'Super Admin',
    ],
    'default' => 'Not provided',

    'section' => [
        'general' => 'General Information',
        'general_description' => "Basic user information",
        'contact' => 'Contact Information',
        'contact_description' => 'User contact details',
        'social' => 'Social Media',
        'social_description' => 'Social media profiles',
        'settings' => 'Settings',
        'settings_description' => 'User rules and permissions',
        'security' => 'Security',
        'security_description' => 'Password management'
    ],

    'password' => 'Password',
    'password_confirm' => 'Confirm Password',

    'notification' => [
        'created' => 'User successfully created!',
        'update_user_title' => 'User updated',
        'update_user_message' => 'The user has been update successfully',
        'create_user_title' => 'User created',
        'create_user_message' => 'The user was successfully created',
    ],


    'create' => 'Add User',
    'edit_title' => 'Edit - :name',
    'create_title' => 'Create User',

    // Dashboard
    'total_users' => 'Total Users',
    'new_users_last_30_days' => 'New Users (Last 30 Days)',
    'user_distribution' => 'User Distribution by Type',
    'number_user' => 'Number of users',
    'userReportByStatus' => 'Users report by status',

];
