# Ressources du workshop SalesBoard

Cette page accompagne le guide du présentateur et le script de live coding de 45 minutes. Elle rassemble les références utilisées pour faire évoluer SalesBoard d'une application Symfony/Twig fonctionnelle vers une interface moderne, tout en conservant le backend, les routes Symfony et les données Doctrine.

## Fil conducteur

SalesBoard suit une progression volontairement visible :

```text
Backend Symfony fonctionnel
 ↓
Pages Twig simples
 ↓
Tailwind CSS et tokens de design
 ↓
Composants de style shadcn
 ↓
Layout Twig réutilisable
 ↓
Données Doctrine réelles
 ↓
Interface métier complète
```

## Symfony et intégration UI

### Symfony UX TailwindBundle

Le bundle documente l'intégration de Tailwind CSS dans un projet Symfony. Il sert de référence pour la compilation des styles, les commandes Symfony et les conventions d'intégration.

- [Documentation Symfony UX TailwindBundle](https://symfony.com/bundles/TailwindBundle/current/index.html)

### Symfony UX Shadcn UI Kit

Cette ressource présente l'intégration de composants inspirés de shadcn dans l'écosystème Symfony UX. Elle est utile pour comparer les composants disponibles avec les composants Twig légers du workshop.

- [Symfony UX Shadcn UI Kit](https://ux.symfony.com/toolkit/kits/shadcn)

### Symfony AssetMapper et Importmap

SalesBoard utilise AssetMapper et Importmap plutôt qu'un frontend Vite ou une SPA. Les ressources JavaScript et CSS sont déclarées dans le projet Symfony et restent servies par l'application.

- [Symfony AssetMapper](https://symfony.com/doc/current/frontend/asset_mapper.html)
- [Symfony Importmap](https://symfony.com/doc/current/frontend/asset_mapper.html#using-importmaps)

## Tailwind et composants visuels

### Tailwind CSS

Tailwind fournit les utilitaires utilisés pour les grilles responsive, les espacements, les couleurs, les états hover/focus et les tableaux. Les tokens du thème sont définis dans `assets/styles/app.css` afin de conserver une palette cohérente.

- [Documentation Tailwind CSS](https://tailwindcss.com/docs)

### shadcn/ui

shadcn/ui sert de référence de design system : cartes, boutons, badges, champs, sélecteurs, tableaux et états interactifs. Dans SalesBoard, ces patterns sont adaptés en composants Twig plutôt que copiés depuis React.

- [Documentation shadcn/ui](https://ui.shadcn.com/docs)
- [Installation shadcn/ui](https://ui.shadcn.com/docs/installation)
- [Installation manuelle](https://ui.shadcn.com/docs/installation/manual)
- [Fichier `components.json`](https://ui.shadcn.com/docs/components-json)
- [Catalogue des composants](https://ui.shadcn.com/docs/components)

### Icônes Lucide

Lucide fournit le vocabulaire d'icônes utilisé par le prototype : navigation, dashboard, produits, transactions, recherche et notifications. Dans Twig, les icônes peuvent être rendues avec des SVG légers ou un composant d'icône partagé.

- [Lucide Icons](https://lucide.dev/)

## Architecture Symfony du projet

Les ressources techniques doivent être lues avec les responsabilités suivantes en tête :

- `src/Entity/` : modèle métier Doctrine.
- `src/Repository/` : recherches, filtres, limites et agrégations SQL/Doctrine.
- `src/Service/DashboardService.php` : composition des statistiques du dashboard.
- `src/Controller/` : préparation de la réponse et transmission des données à Twig.
- `templates/components/` : composants visuels réutilisables.
- `templates/dashboard/`, `sales/`, `customers/`, `products/`, `transactions/`, `reports/` : pages métier.
- `assets/styles/app.css` : tokens visuels Tailwind et styles de base.
- `migrations/` et `src/DataFixtures/` : schéma reproductible et données de démonstration.

La règle essentielle du workshop est de modifier l'interface sans déplacer la logique métier dans Twig et sans remplacer Symfony par une architecture frontend parallèle.

## Données et Doctrine

Le dashboard utilise notamment :

- les ventes terminées pour le chiffre d'affaires et le panier moyen ;
- les ventes regroupées par mois pour l'évolution ;
- les lignes de vente regroupées par produit pour le classement ;
- les transactions triées par date pour l'activité récente ;
- les produits comparés à leur seuil minimum pour les alertes de stock.

Les pages de listes acceptent aussi des paramètres de recherche et de statut, par exemple :

```text
/sales?q=SAL-00001&status=completed
/customers?q=Danial
/products?q=SB-0001
/transactions?q=TXN-00001&status=completed
```

Ces filtres doivent rester côté serveur. Les templates affichent le résultat préparé par les repositories et ne doivent pas effectuer de requêtes Doctrine.

## IA et prompting

Ces ressources servent à préparer les prompts, cadrer le contexte et demander des modifications contrôlables. Elles sont particulièrement utiles pour décrire les contraintes : Symfony/Twig uniquement, conservation des routes, données Doctrine réelles et absence de SPA.

- [Anthropic — Prompting Best Practices](https://docs.anthropic.com/en/docs/build-with-claude/prompt-engineering/prompt-templates-and-variables)
- [OpenAI — Model guidance and prompting](https://developers.openai.com/api/docs/guides/latest-model)

Un bon prompt de workshop doit préciser le contexte, le résultat attendu, les fichiers concernés, les contraintes d'architecture et la validation demandée. Il faut demander une analyse avant une modification lorsque plusieurs chemins techniques sont possibles.

## Qualité, revue et sécurité

### Revue de code

Ces articles aident à transformer une sortie générée ou une modification rapide en code vérifiable et maintenable :

- [Martin Fowler — Refinement Code Review](https://martinfowler.com/bliki/RefinementCodeReview.html)
- [Martin Fowler — Encoding Team Standards](https://martinfowler.com/articles/reduce-friction-ai/encoding-team-standards.html)

Pendant le workshop, les validations minimales sont :

```bash
php bin/console lint:twig templates
php bin/console lint:container
php bin/console doctrine:schema:validate --skip-sync
git diff --check
```

### Sécurité applicative

L'interface ne constitue jamais une autorisation. Les contrôleurs et les mécanismes Symfony doivent vérifier les droits côté serveur. Les formulaires d'écriture doivent utiliser Symfony Forms, Validator et CSRF lorsque ces actions seront ajoutées.

- [OWASP — Secure Coding Practices Quick Reference](https://owasp.org/www-project-secure-coding-practices-quick-reference-guide/stable/en/)
- [OWASP Developer Guide — Secure Development](https://devguide.owasp.org/en/02-foundations/02-secure-development/)

## Ressources du dépôt

- [README anglais](../README.md)
- [README français](../README.fr.md)
- [Capture du dashboard initial](images/dashboard.png)
- [Capture de l'étape Tailwind](images/dashboard-tailwind-step-2.png)
- [Capture de l'étape données réelles](images/dashboard-real-data-step-3.png)

## À retenir

Le workshop ne consiste pas seulement à rendre une page plus jolie. Il montre comment faire évoluer une interface avec l'aide de l'IA tout en conservant une architecture compréhensible, des données réelles, des requêtes contrôlées, des validations reproductibles et des responsabilités clairement séparées.

# Resources

- **Symfony UX TailwindBundle :** [https://symfony.com/bundles/TailwindBundle/current/index.html](https://symfony.com/bundles/TailwindBundle/current/index.html)
- **Symfony UX Shadcn UI Kit :** [https://ux.symfony.com/toolkit/kits/shadcn](https://ux.symfony.com/toolkit/kits/shadcn)
- **shadcn/ui — Documentation :** [https://ui.shadcn.com/docs](https://ui.shadcn.com/docs)
- **shadcn/ui — Installation :** [https://ui.shadcn.com/docs/installation](https://ui.shadcn.com/docs/installation)
- **shadcn/ui — Manual Installation :** [https://ui.shadcn.com/docs/installation/manual](https://ui.shadcn.com/docs/installation/manual)
- **shadcn/ui — components.json :** [https://ui.shadcn.com/docs/components-json](https://ui.shadcn.com/docs/components-json)
- **shadcn/ui — Components :** [https://ui.shadcn.com/docs/components](https://ui.shadcn.com/docs/components)
- **Lucide Icons :** [https://lucide.dev/](https://lucide.dev/)
- **Anthropic — Prompting Best Practices :** [https://docs.anthropic.com/en/docs/build-with-claude/prompt-engineering/prompt-templates-and-variables](https://docs.anthropic.com/en/docs/build-with-claude/prompt-engineering/prompt-templates-and-variables)
- **OpenAI — Model guidance & prompting :** [https://developers.openai.com/api/docs/guides/latest-model](https://developers.openai.com/api/docs/guides/latest-model)
- **Martin Fowler — Refinement Code Review :** [https://martinfowler.com/bliki/RefinementCodeReview.html](https://martinfowler.com/bliki/RefinementCodeReview.html)
- **Martin Fowler — Encoding Team Standards :** [https://martinfowler.com/articles/reduce-friction-ai/encoding-team-standards.html](https://martinfowler.com/articles/reduce-friction-ai/encoding-team-standards.html)
- **OWASP — Secure Coding Practices Quick Reference :** [https://owasp.org/www-project-secure-coding-practices-quick-reference-guide/stable-en/](https://owasp.org/www-project-secure-coding-practices-quick-reference-guide/stable-en/)
- **OWASP Developer Guide — Secure Development :** [https://devguide.owasp.org/en/02-foundations/02-secure-development/](https://devguide.owasp.org/en/02-foundations/02-secure-development/)
