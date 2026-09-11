# SalesBoard

SalesBoard is a deliberately simple Symfony backend for a live workshop about transforming a basic Symfony application into a modern interface with Twig, Tailwind CSS, and shadcn-style components. The first version keeps the UI plain so the visual transformation is easy to see.

## Technical stack

- Symfony 8.1 and PHP 8.4+
- Doctrine ORM and Doctrine Migrations
- MySQL or MariaDB
- Twig, Symfony Forms, Validator, Serializer, and UX Turbo
- Symfony AssetMapper
- DoctrineFixturesBundle and Faker

No React, Vue, Vite, or polished frontend framework is included.

## Requirements

- PHP 8.4 or newer with `ctype`, `iconv`, and PDO for your database
- Composer
- MySQL 8+ or MariaDB 10.8+
- Symfony CLI is recommended, but PHP's built-in server also works

## Installation

```bash
composer install
cp .env .env.local
```

Edit `.env.local` and set `DATABASE_URL` for a local MySQL or MariaDB database. For example:

```dotenv
DATABASE_URL="mysql://app:password@127.0.0.1:3306/salesboard?serverVersion=8.0.32&charset=utf8mb4"
```

Initialize the database and sample data:

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
```

The fixtures are deterministic and create 2 users, 6 categories, 30 products, 25 customers, 80 sales, multiple items per sale, and 80 transactions across approximately the last 12 months.

### Development fixture account

This account is for DEVELOPMENT/WORKSHOP use only:

```text
Email:    admin@salesboard.local
Password: password
```

The password is hashed using Symfony's password hasher before it is stored.

## Running the application

```bash
symfony server:start
```

Or use PHP's built-in server:

```bash
php -S 127.0.0.1:8000 -t public
```

## Routes

| Route | Purpose |
| --- | --- |
| `/dashboard` | Revenue, sales, customer, order-value, recent-transaction, and low-stock summaries |
| `/sales` | Sales list |
| `/customers` | Customer list |
| `/products` | Product and stock list |
| `/transactions` | Transaction list |
| `/reports` | Basic monthly and top-product statistics |
| `/settings` | Placeholder settings page |

## Entity relationships

- A `Customer` has many `Sale` and `Transaction` records.
- A `Sale` belongs to one customer and has many `SaleItem` records.
- A `SaleItem` belongs to one `Sale` and one `Product`; its total is quantity multiplied by unit price.
- A `Product` belongs to one `Category` and exposes calculated in-stock, low-stock, and out-of-stock checks.
- A `Transaction` belongs to one sale and one customer.
- `User` follows Symfony Security conventions and is loaded through Doctrine by email.

Statuses and payment methods use backed PHP enums in `src/Enum`.

## DashboardService

`App\Service\DashboardService` is intentionally small. It calculates completed revenue, sales count, customer count, average order value, monthly sales totals, recent transactions, top products, and low-stock products. The dashboard and reports controllers pass this data directly to Twig without putting business logic in templates.

## Project structure

```text
src/Entity/       Doctrine entities
src/Enum/         Backed status and payment enums
src/Service/      Dashboard calculations
src/Controller/   Thin page controllers
src/DataFixtures/ Deterministic Faker dataset
templates/        Basic Twig pages for the workshop starting point
migrations/       Doctrine database migrations
```

## Resetting the database

To recreate the schema and fixture data during development:

```bash
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
```

## Workshop purpose

This repository intentionally begins as a functional but unstyled Symfony application. The workshop can progressively introduce an AI-generated prototype, Tailwind CSS, shadcn-style components, Lucide icons, Twig components, and finally real Symfony data without first undoing a finished dashboard design.
