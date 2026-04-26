# Personal Finance Tracker

Laravel-based personal finance tracker with account, transaction, budget, and reporting flows.

## Brevo Password Reset Setup

This app uses Laravel's built-in password reset flow. The repo is configured so reset emails can be delivered through Brevo SMTP once the environment variables are set.

### 1. Configure Brevo

In your Brevo dashboard:

1. Create a transactional sender.
2. Authenticate your sending domain with the DNS records Brevo provides.
3. Generate an SMTP key.
4. Note the SMTP login and sender address you want the app to use.

Use the SMTP key, not a general API key, for Laravel mail.

### 2. Configure the App

Copy `.env.example` to `.env` and set the mail values:

```env
APP_URL=https://your-domain.example

MAIL_MAILER=smtp
MAIL_SCHEME=tls
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your-brevo-smtp-login
MAIL_PASSWORD=your-brevo-smtp-key
MAIL_FROM_ADDRESS=noreply@your-domain.example
MAIL_FROM_NAME="${APP_NAME}"
```

Then clear cached config:

```bash
php artisan config:clear
```

### 3. Queue Worker

Password reset notifications are queued. In production you need a queue worker running so emails are actually sent:

```bash
php artisan queue:work
```

The app already defaults to the `database` queue connection, so make sure your deployed environment is running migrations and the worker process.

### 4. Verify the Flow

1. Open the forgot-password screen.
2. Submit a real email address for an existing user.
3. Confirm the email arrives in a real inbox.
4. Open the reset link and set a new password.
5. Log in with the updated password.

## Local Development

Install dependencies and run the application:

```bash
composer install
npm install
php artisan key:generate
php artisan migrate
npm run dev
php artisan serve
```

If you want to test the full email flow locally with Brevo, use real SMTP values in `.env`. If you only want to verify the app behavior without sending real mail, switch `MAIL_MAILER` back to `log` temporarily and inspect the application logs.
