# SalesBoard

SalesBoard est une application éducative de gestion commerciale conçue pour un atelier. Elle part d'un backend Symfony fonctionnel et transforme progressivement son interface Twig rendue côté serveur en console SaaS moderne, inspirée d'un prototype réalisé avec Lovable.

Le [projet Lovable](https://github.com/Asmitta-01/salesboard-ui-demo) sert uniquement de référence visuelle et UX. Son architecture frontend n'est pas copiée : SalesBoard reste une application Symfony MVC classique avec Doctrine, contrôleurs, services, templates Twig et AssetMapper.

![Tableau de bord SalesBoard](docs/images/dashboard-real-data-step-3.png)

### Progression de l'atelier

| Étape | Description | Capture |
| --- | --- | --- |
| 1. Fondation Symfony | Pages Twig simples, entités Doctrine, migrations et fixtures | [Dashboard initial](docs/images/dashboard.png) |
| 2. Fondation UI | Tailwind CSS, tokens de style shadcn, composants Twig réutilisables et layout responsive | [Étape Tailwind](docs/images/dashboard-tailwind-step-2.png) |
| 3. Données réelles | Dashboard et listes connectés aux repositories et services Doctrine | [Dashboard avec données réelles](docs/images/dashboard-real-data-step-3.png) |

## Stack technique

- PHP 8.4+ (le projet fonctionne actuellement avec PHP 8.5 en développement)
- Symfony 8.1.x (le runtime local actuel est Symfony 8.1.6)
- Doctrine ORM et Doctrine Migrations
- MySQL 8+ ou MariaDB 10.8+
- Twig, Symfony Forms, Validator, Serializer et UX Turbo
- Symfony AssetMapper et Importmap
- Tailwind CSS 4 via SymfonyCasts Tailwind Bundle
- Tokens Tailwind de style shadcn et `tw-animate-css`
- DoctrineFixturesBundle et Faker

L'importmap utilise actuellement `shadcn/dist/tailwind.css` en version 4.21.0 et `tw-animate-css/dist/tw-animate.css` en version 1.4.0. La feuille Google Fonts charge Inter et JetBrains Mono pour reprendre le langage visuel du prototype.

React, Vue, Next.js, Vite, Angular et aucune architecture SPA ne sont utilisés.

## Contexte du projet

SalesBoard est un projet fictif destiné à l'apprentissage. Il fournit aux participants un backend réaliste avant la refonte de l'interface. Le domaine couvre les ventes, les clients, les produits, les transactions, les niveaux de stock et les rapports commerciaux.

La séparation des responsabilités est volontairement claire :

- Les entités Doctrine représentent le domaine métier.
- Les repositories prennent en charge les listes filtrées et les agrégations.
- `DashboardService` assemble les statistiques du dashboard et des rapports.
- Les contrôleurs sélectionnent la page et transmettent les données préparées à Twig.
- Les composants Twig partagent le langage visuel sans introduire de framework frontend.
- AssetMapper charge les entrées CSS et JavaScript sans bundler ni Vite.

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

## Flux des données réelles

Le dashboard et les pages de listes utilisent maintenant la base alimentée par les fixtures Doctrine. Les recherches et filtres de statut sont gérés par les paramètres de requête Symfony et transmis aux repositories, par exemple :

```text
/sales?q=SAL-00001&status=completed
/customers?q=Danial
/products?q=SB-0001
/transactions?q=TXN-00001&status=completed
```

Le dashboard agrège côté Doctrine le chiffre d'affaires terminé, les revenus mensuels, les produits les plus vendus, les produits avec un stock faible et les transactions récentes. Twig ne lance aucune requête et ne calcule aucun total métier.

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
docs/images/      Captures du dashboard pour les étapes de l'atelier
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

Le parcours pédagogique suit cette progression :

```text
Backend Symfony
 -> pages Twig simples
 -> layout Tailwind inspiré de Lovable
 -> composants Twig réutilisables
 -> données Doctrine réelles dans l'interface
```

L'objectif est de montrer comment adopter un système visuel moderne tout en conservant Symfony pour le routage, la sécurité, la validation, la persistance et la logique métier côté serveur.

## Limites actuelles

- La page Settings reste un placeholder pour l'atelier.
- Les boutons New sale, Add customer et Add product sont actuellement visuels ; aucun formulaire CRUD n'a encore été ajouté.
- Les listes sont limitées à 100 résultats. Une pagination complète pourra être ajoutée lorsque le volume dépassera l'échelle de l'atelier.
- Les identifiants de fixture sont réservés au développement et ne conviennent pas à la production.
