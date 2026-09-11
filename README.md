# SalesBoard

SalesBoard is an educational business-management application for a live workshop. It starts from a functional Symfony backend and progressively transforms its server-rendered Twig interface into a modern SaaS-style operations console inspired by a Lovable prototype.

The [Lovable project](https://github.com/Asmitta-01/salesboard-ui-demo) is used as a visual and UX reference only. It is not copied as a frontend architecture: SalesBoard remains a classic Symfony MVC application with Doctrine, controllers, services, Twig templates, and AssetMapper.

![SalesBoard dashboard](docs/images/dashboard-real-data-step-3.png)

### Workshop progress

| Stage | Description | Screenshot |
| --- | --- | --- |
| 1. Symfony foundation | Basic Twig pages, Doctrine entities, migrations, and fixtures | [Initial dashboard](docs/images/dashboard.png) |
| 2. UI foundation | Tailwind CSS, shadcn-style tokens, reusable Twig components, responsive layout | [Tailwind step](docs/images/dashboard-tailwind-step-2.png) |
| 3. Real application data | Dashboard and list pages connected to Doctrine repositories and services | [Real-data dashboard](docs/images/dashboard-real-data-step-3.png) |

## Technical stack

- PHP 8.4+ (the project currently runs with PHP 8.5 in development)
- Symfony 8.1.x (the current local runtime is Symfony 8.1.6)
- Doctrine ORM and Doctrine Migrations
- MySQL 8+ or MariaDB 10.8+
- Twig, Symfony Forms, Validator, Serializer, and UX Turbo
- Symfony AssetMapper and Importmap
- Tailwind CSS 4 through SymfonyCasts Tailwind Bundle
- shadcn-style Tailwind tokens and `tw-animate-css`
- DoctrineFixturesBundle and Faker

The current importmap pins `shadcn/dist/tailwind.css` to 4.21.0 and `tw-animate-css/dist/tw-animate.css` to 1.4.0. The Google Fonts stylesheet loads Inter and JetBrains Mono for the prototype-inspired visual language.

No React, Vue, Next.js, Vite, Angular, or SPA architecture is included.

## Project context

SalesBoard is fictional and intended for educational use. It gives workshop participants a realistic backend before they redesign the interface. The application domain covers sales, customers, products, transactions, inventory levels, and business reporting.

The project deliberately separates responsibilities:

- Doctrine entities model the business domain.
- Repositories own filtered lists and aggregate queries.
- `DashboardService` assembles dashboard and report statistics.
- Controllers select the page and pass prepared data to Twig.
- Twig components provide the shared visual language without introducing a frontend framework.
- AssetMapper loads the CSS and JavaScript entrypoints without a bundler or Vite.

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

## Real data flow

The dashboard and list pages now use the database populated by Doctrine fixtures. Search and status filters are handled by Symfony request query parameters and translated into repository queries, for example:

```text
/sales?q=SAL-00001&status=completed
/customers?q=Danial
/products?q=SB-0001
/transactions?q=TXN-00001&status=completed
```

The dashboard aggregates completed revenue, monthly revenue, top products, low-stock products, and recent transactions through Doctrine queries. Twig does not issue database queries or calculate business totals.

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
docs/images/      Workshop dashboard screenshots
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

This repository intentionally begins as a functional Symfony application and progresses through a visible transformation:

```text
Symfony backend
 -> basic Twig pages
 -> Lovable-inspired Tailwind layout
 -> reusable Twig components
 -> real Doctrine data in the interface
```

The goal is to show how a developer can adopt a modern visual system while keeping Symfony responsible for routing, security, validation, persistence, and server-side business logic.

## Current limitations

- The Settings page is still a workshop placeholder.
- The New sale, Add customer, and Add product buttons are visual actions only; no CRUD Forms have been added yet.
- List queries currently cap results at 100 records. Full pagination can be added when the dataset grows beyond the workshop scale.
- The development fixture credentials are not suitable for production.
