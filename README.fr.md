# SalesBoard

SalesBoard est le backend volontairement simple d'un atelier consacré à la transformation d'une application Symfony de base en interface moderne avec Twig, Tailwind CSS et des composants de style shadcn. La première version conserve une interface sobre afin de rendre visible l'évolution graphique pendant l'atelier.

![Tableau de bord SalesBoard](docs/images/dashboard.png)

## Stack technique

- Symfony 8.1 et PHP 8.4+
- Doctrine ORM et Doctrine Migrations
- MySQL ou MariaDB
- Twig, Symfony Forms, Validator, Serializer et UX Turbo
- Symfony AssetMapper
- DoctrineFixturesBundle et Faker

React, Vue, Vite et aucun framework frontend superflu ne sont utilisés.

## Prérequis

- PHP 8.4 ou une version plus récente avec `ctype`, `iconv` et PDO pour votre base de données
- Composer
- MySQL 8+ ou MariaDB 10.8+
- Symfony CLI recommandé, ou le serveur intégré de PHP

## Installation

```bash
composer install
cp .env .env.local
```

Modifiez `.env.local` et configurez `DATABASE_URL` pour votre base MySQL ou MariaDB locale. Exemple :

```dotenv
DATABASE_URL="mysql://app:password@127.0.0.1:3306/salesboard?serverVersion=8.0.32&charset=utf8mb4"
```

Initialisez la base et les données de démonstration :

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
```

Les fixtures sont déterministes et créent 2 utilisateurs, 6 catégories, 30 produits, 25 clients, 80 ventes, plusieurs lignes par vente et 80 transactions réparties sur environ les douze derniers mois.

### Compte de fixture pour le développement

Ce compte est réservé au DÉVELOPPEMENT et à l'ATELIER :

```text
Email :    admin@salesboard.local
Mot de passe : password
```

Le mot de passe est haché avec le password hasher de Symfony avant d'être enregistré.

## Lancer l'application

```bash
symfony server:start # symfony serve
```

Ou avec le serveur intégré de PHP :

```bash
php -S 127.0.0.1:8000 -t public
```

## Routes disponibles

| Route | Fonction |
| --- | --- |
| `/dashboard` | Chiffre d'affaires, ventes, clients, panier moyen, transactions récentes et stock faible |
| `/sales` | Liste des ventes |
| `/customers` | Liste des clients |
| `/products` | Liste des produits et des stocks |
| `/transactions` | Liste des transactions |
| `/reports` | Statistiques mensuelles et produits les plus vendus |
| `/settings` | Page de paramètres temporaire |

## Relations entre les entités

- Un `Customer` possède plusieurs `Sale` et `Transaction`.
- Une `Sale` appartient à un client et possède plusieurs `SaleItem`.
- Un `SaleItem` appartient à une `Sale` et à un `Product`. Son total correspond à la quantité multipliée par le prix unitaire.
- Un `Product` appartient à une `Category` et expose les états en stock, stock faible et rupture de stock sous forme de calculs.
- Une `Transaction` appartient à une vente et à un client.
- `User` suit les conventions de Symfony Security et est chargé par Doctrine via son adresse email.

Les statuts et moyens de paiement utilisent des enums PHP backed dans `src/Enum`.

## DashboardService

`App\Service\DashboardService` reste volontairement petit et lisible. Il calcule le chiffre d'affaires des ventes terminées, le nombre de ventes, le nombre de clients, le panier moyen, les ventes par mois, les transactions récentes, les produits les plus vendus et les produits avec un stock faible. Les contrôleurs transmettent ces données à Twig sans placer de logique métier dans les templates.

## Structure du projet

```text
src/Entity/       Entités Doctrine
src/Enum/         Enums de statuts et de moyens de paiement
src/Service/      Calculs du tableau de bord
src/Controller/   Contrôleurs de pages fins
src/DataFixtures/ Jeu de données Faker déterministe
templates/        Pages Twig simples, point de départ de l'atelier
migrations/       Migrations de la base Doctrine
```

## Réinitialiser la base

Pour recréer le schéma et les données de fixture pendant le développement :

```bash
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
```

## Objectif de l'atelier

Ce dépôt commence volontairement comme une application Symfony fonctionnelle mais peu stylisée. L'atelier peut ensuite introduire progressivement un prototype généré par IA, Tailwind CSS, des composants de style shadcn, les icônes Lucide, des composants Twig et enfin les données réelles de Symfony, sans devoir démanteler un dashboard déjà finalisé.
