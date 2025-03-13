SkillHub - Freelance Marketplace

**SkillHub** est une application de marketplace de freelance similaire à **Fiverr** ou **Codeur.com**. Le projet permet aux utilisateurs de créer des services (annonces), de passer des commandes, de laisser des évaluations, de gérer des transactions et bien plus encore.

## Technologies utilisées
- **Backend** : Laravel 12.x
- **Frontend** : React + Inertia.js
- **Admin Panel** : Filament 3.x
- **Base de données** : MySQL (ou PostgreSQL pour le déploiement)
- **Authentification** : Laravel Sanctum
- **API** : Laravel API pour la gestion des données
- **UI** : Tailwind CSS
- **Gestion des routes** : Filament et Laravel Route Model Binding

## Prérequis

Avant de commencer, assurez-vous d'avoir installé les outils suivants :

- [PHP 8.2+](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/)
- [Node.js et NPM](https://nodejs.org/en/download/)
- [MySQL](https://dev.mysql.com/downloads/) ou [PostgreSQL](https://www.postgresql.org/download/)
- [Git](https://git-scm.com/)
- [Laravel](https://laravel.com/docs) et [Filament](https://filamentphp.com/)

## Installation

### 1. Cloner le repository

```bash
git clone git@github.com:NAJPRO/SkillHub.git
cd SkillHub
```

### 2. Installer les dépendances Backend

Installez les dépendances PHP avec **Composer** :

```bash
composer install
```

### 3. Configurer l'environnement

Copiez le fichier `.env.example` et renommez-le en `.env`. Ensuite, modifiez les informations de connexion à la base de données et autres paramètres de configuration nécessaires.

```bash
cp .env.example .env
```

Puis, générez la clé d'application de Laravel :

```bash
php artisan key:generate
```

### 4. Créer la base de données

Créez la base de données dans MySQL (ou PostgreSQL) et configurez le fichier `.env` pour y connecter votre application. Par exemple, pour MySQL :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=skillhub
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Exécuter les migrations

Exécutez les migrations pour créer les tables dans la base de données :

```bash
php artisan migrate
```

### 6. Installer les dépendances Frontend

Installez les dépendances frontend avec **NPM** ou **Yarn** :

```bash
npm install
# ou
yarn install
```

### 7. Compiler les assets

Compilez les fichiers frontend :

```bash
npm run dev
# ou
yarn dev
```

### 8. Lancer l'application

Lancez le serveur Laravel :

```bash
php artisan serve
```

Ensuite, ouvrez [http://127.0.0.1:8000](http://127.0.0.1:8000) dans votre navigateur pour voir l'application en action.

## Structure du projet

Voici une vue d'ensemble de la structure du projet :

```bash
SkillHub/
├── app/                   # Contient les modèles et les logiques métiers
│   ├── Models/            # Modèles Eloquent
│   ├── Filament/          # Ressources et Pages pour le panneau d'administration
├── resources/
│   ├── js/                # Code React (Frontend)
│   ├── views/             # Vues Blade pour l'API ou autres templates
├── routes/                 # Routes API et web
├── database/
│   ├── migrations/        # Migrations de base de données
├── public/                # Assets publics (images, JS, CSS compilés)
└── .env                   # Fichier de configuration pour l'environnement
```

## Commandes utiles

### Artisan (Laravel)

- **Créer une ressource Filament :**

```bash
php artisan make:filament-resource Service
```

- **Exécuter les migrations :**

```bash
php artisan migrate
```

- **Créer un utilisateur :**

```bash
php artisan tinker
>>> User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password')]);
```

- **Lancer le serveur Laravel :**

```bash
php artisan serve
```

### NPM (Frontend)

- **Lancer le développement React :**

```bash
npm run dev
```

- **Compiler les assets pour la production :**

```bash
npm run build
```

## Fonctionnalités

### 1. **Gestion des services (annonces)**

- Les utilisateurs peuvent créer des services avec un titre, une description, des prix, et un slug unique.
- Les services peuvent être catégorisés et modifiés depuis le panneau d'administration Filament.

### 2. **Commandes**

- Les utilisateurs peuvent passer des commandes pour les services.
- L'administrateur peut suivre les commandes et les transactions via le panneau d'administration.

### 3. **Évaluations et commentaires**

- Les utilisateurs peuvent laisser des évaluations et des commentaires pour les services qu'ils ont utilisés.

### 4. **Gestion des utilisateurs**

- Les utilisateurs peuvent se connecter, créer un profil et gérer leurs services via le frontend.
- L'administrateur peut gérer les utilisateurs, les services et les commandes via Filament.

### 5. **Transactions et paiements**

- Les utilisateurs peuvent effectuer des paiements pour leurs commandes. Le système de transactions est intégré avec des modèles pour les suivre.

## Déploiement

Pour déployer l'application, suivez les étapes ci-dessous pour la mise en production. Vous pouvez utiliser des services comme **Heroku**, **Vercel**, ou **DigitalOcean** pour l'hébergement.

## Contribution

Les contributions sont les bienvenues ! Si vous souhaitez contribuer, veuillez forker ce dépôt, effectuer vos modifications, et soumettre une pull request.
