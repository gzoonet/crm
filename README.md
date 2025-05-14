# Filament CRM Package Installation

This document provides instructions on how to install and configure the Filament CRM Package in your Laravel application.

## Requirements

- PHP ^8.1
- Laravel Framework ^10.0 || ^11.0
- Filament ^3.0

## Installation Steps

1.  **Require the package via Composer:**

    Open your terminal and navigate to your Laravel project directory. Then run the following command:

    ```bash
    composer require your-vendor/crm-package
    ```

    Replace `your-vendor/crm-package` with the actual vendor and package name once it is published (e.g., on Packagist or a private repository).

2.  **Publish Migrations (Optional but Recommended for initial setup):
**
    The package service provider is configured to load migrations automatically. However, if you prefer to publish them to your application's `database/migrations` directory, you can run:

    ```bash
    php artisan vendor:publish --tag="crm-package-migrations"
    ```

    *Note: The tag `crm-package-migrations` is based on the package name defined in the service provider (`crm-package`). If you changed the name in `CrmPackageServiceProvider.php`, use that name here (e.g., `your-package-name-migrations`).*

3.  **Run Database Migrations:**

    To create the necessary CRM tables in your database, run the migration command:

    ```bash
    php artisan migrate
    ```

4.  **Register the Service Provider (Usually Auto-discovered):**

    Laravel's package auto-discovery should automatically register the `YourVendor\CrmPackage\CrmPackageServiceProvider`. If for some reason it is not, or you have auto-discovery disabled for this package, you can manually add the service provider to the `providers` array in your `config/app.php` file:

    ```php
    'providers' => [
        // Other Service Providers
        YourVendor\CrmPackage\CrmPackageServiceProvider::class,
    ],
    ```

5.  **Accessing the CRM:**

    Once installed and migrations are run, the CRM resources (Customers, Contacts, Leads, Tasks, Tags) and the Dashboard should be available within your Filament admin panel. The navigation group will be labeled "CRM".

## Configuration (Optional)

If the package includes a configuration file (e.g., `config/crm-package.php`), you can publish it to your application's `config` directory using:

```bash
php artisan vendor:publish --tag="crm-package-config"
```

This will allow you to customize default settings provided by the package. (Currently, this package is set up to potentially have a config file, but one hasn't been explicitly created in this development process. If specific configurations are needed, this step would become more relevant).

## Usage

After installation, navigate to your Filament admin panel. You should see a new "CRM" section in the navigation sidebar containing links to manage:

-   Customers
-   Contacts
-   Leads
-   Tasks
-   Tags

The main dashboard should also display the new CRM widgets:

-   Active Leads by Stage
-   Tasks Due This Week
-   Recent Activity
-   New Customers This Month
-   Conversion Rate

## User Roles and Permissions (Future Step)

This package is designed to be compatible with user roles and permissions. The specific implementation (e.g., using Spatie Permissions or Filament Shield) is planned for a future development phase. Once implemented, you will need to configure roles and assign permissions to users to control access to different CRM modules and actions.

## Custom Workflows and Integrations (Future Steps)

Features like Lead Scoring, Approval Workflows, Task Reminders, and third-party integrations (Email Marketing, Google Calendar) are planned for future development. Configuration and usage instructions for these will be provided as they are implemented.

## Troubleshooting

-   **Class not found errors:** Ensure Composer's autoloader is up-to-date (`composer dump-autoload`). Verify the namespace in your `composer.json` and service provider matches the actual directory structure.
-   **Filament resources not showing:** Double-check that the service provider is registered and that Filament's auto-discovery can locate your resources, pages, and widgets. Ensure your package's PSR-4 autoloading is correctly configured in `composer.json`.
-   **Migration issues:** If you encounter errors during migration, ensure your database connection is correctly configured in your `.env` file. If you published migrations, check for any conflicts with existing migration filenames.

For further assistance, please refer to the package documentation or contact the developers.

