## About POV City Guide

This is an e-commerce app for event tickets. It allows event organizers to put up an event for sale, then end-customers to purchase tickets to that event.

## Tech Stack

This project uses the BALL stack, so it's:
- Bootstrap
- As-little-JS-as-possible
- Laravel
- Livewire

In addition:
- the admin panel for this project uses [Backpack for Laravel](https://backpackforlaravel.com/) for... everything - authentication, CRUDs;
- the front-end uses the [Tabler HTML template](https://tabler.io/);

## Installation

1. Clone the repository `git clone https://github.com/DigitallyHappy/povtickets.git povtickets`
2. Run `composer install`
3. Run `cp .env.example .env` to clone the .env file, then customize it for your local environment
4. Run `php artisan key:generate`
5. Run `php artisan migrate --seed`
6. Setup the payment integration:
    - Add the payment provider certificates (eg. Netopia to `storage/app/certs/netopia/public_key.cer` and `storage/app/certs/netopia/private_key.key`);
    - On localhost, be aware that if you want to have a complete payment flow, you need to expose your localhost to the Internet using Ngrok or Expose and set up a base URL for the IPN in the .env file - for example:  `NETOPIA_LOCAL_DEV_IPN_BASE_URL=https://3be2-bbbb-2f07-cccc-f800-dddd-807a-ffff-807a.ngrok-free.app`
8. Remember to start the queue using `php artisan queue:work` or set `QUEUE_CONNECTION=sync` in your .env file;

If you can browse the backoffice but have JS errors, that means not all assets are being loaded properly. Please check [the Basset docs](https://github.com/Laravel-Backpack/basset?tab=readme-ov-file#basset-is-not-working-what-may-be-wrong).

## Development Guides

- [File Migration Guide](docs/file-migration.md) - How to migrate files from production to your local environment

## Architecture

There are two sides to this app:
- the storefront - runs on Livewire full-page components
- the backoffice - runs on Backpack for Laravel

## Database Schema

![eer](https://github.com/user-attachments/assets/c49232a6-54fd-4a29-8c77-bcae3c612aa0)


## Branching & Server Setup

Git branches:
- `main` - auto-deployed on https://staging.povtickets.com - protected branch, holds features after code review and dev testing;
- `feature-example` - feature branches;

When merging PRs to `main`, always squash-merge. Each PR needs to become ONE single commit when merging to `main`, with the commit message being `v2.x.y - what was done`. It is IMPORTANT to stick to this rule, because the commit message is the single source of truth for our application's version number. That version is then shown in the backoffice footer, to easily identify what version each client instance is using.

## Services

To run our environments, we use the following services:
- **DNS** - [CloudFlare](https://cloudflare.com/) for `povtickets.com`; the client subdomain probably each use a different DNS provider, and point to our server IP;
- **Hosting** - [DigitalOcean](https://digitalocean.com/) - each client has his own droplet; we have an extra droplet for our staging & testing environments;
- **Server Provisioning & Management** - [Laravel Forge](https://forge.laravel.com/);
- **Transactional Email** - [Resend](https://resend.com/);
- **Payment Gateway** - [Netopia](https://admin.netopia-payments.com/);
- **Error Logging** - [Flare](https://flareapp.io/);
- **Analytics** - [Plausible](https://plausible.io/);

## Manual Testing

Manual testing can be done one https://staging.povtickets.com - as soon as a PR is merged into `main`, it will be deployed there. That instance also runs the seeders, so you can login using:
- admin email: `admin@example.com`
- admin password: `admin`

To test payments using Netopia, you can use the following cards:
- 9900004810225098 - card accepted (CVV = 111)
- 9900541631437790 - card expired
- 9900518572831942 - insufficient funds
- 9900827979991500 - CVV2/CCV incorrect
- 9900576270414197 - transaction denied (eg, card not registered)
- 9900334791085173 - gard with high degree of risk (eg, stolen card)
- 9900130597497640 - error with card bank (could not establish communication channel with bank that issued the card)

To test emails, use our mailpit instance at [https://mailpit.povtickets.com](https://mailpit.povtickets.com) with either:
- username `povtickets@digitallyhappy.com` and password `qky!mqg@GUY7jdf2nbk`
- username `pov` and password `mailpitBcool`

## Automated Testing

Testing is done using PHPUnit for unit tests and and Laravel Dusk for browser tests:

**Notes:**
- If you want to SEE the browser tests running (maybe to debug them), add `DUSK_HEADLESS_DISABLED=true` to your `.env` file;
- Local tests run on a `sqlite` db. You don't need to do anything.
- Github Action tests run on a `mysql` db, to be an accurate representation of prod.

```bash
# to run both test suites
composer test

# to only run unit and feature tests
php artisan test

# to only run dusk tests
php artisan dusk

# to run tests with coverage on localhost (requires Xdebug)
composer test-coverage
```

### Test Coverage

The project is configured to generate test coverage reports in GitHub Actions. After each test run:

1. The coverage percentage is displayed in the GitHub Actions summary
2. A detailed HTML coverage report is available as an artifact named "coverage-report"
3. Coverage is collected only for PHPUnit tests (unit and feature tests)

To view the detailed HTML report:
1. Go to the GitHub Actions run
2. Scroll to the bottom of the page
3. Under "Artifacts", download the "coverage-report" zip file
4. Extract the zip and open "tests/Output/Coverage/html/index.html" in your browser

**Note:** Coverage is intentionally not collected for Dusk browser tests due to performance considerations. Collecting coverage during browser tests can cause tests to run extremely slowly and potentially time out in CI environments.

The coverage configuration is managed through:
- **phpunit.xml** - Coverage configuration for unit and feature tests
- **GitHub Actions workflow** - Runs PHPUnit tests with Xdebug enabled to generate coverage reports

Running tests with coverage locally requires Xdebug to be installed. If you don't have Xdebug, you can still run the tests without coverage.

## License

Proprietary. Do not distribute.
