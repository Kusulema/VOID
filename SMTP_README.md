SMTP setup and testing
======================

This project sends mail through PHPMailer + SMTP. `inc/Mailer.php` already reads SMTP settings from environment variables or a local `.env` file.

What you need to do
-------------------

1. Install Composer on the machine where the site runs.

  Windows installer: https://getcomposer.org/Composer-Setup.exe

2. From the project root, install the PHP dependencies.

```bash
composer install
```

This will create `vendor/` and `vendor/autoload.php` from `composer.json`.

3. Create a `.env` file in the project root from `.env.example` and fill the values.

Required keys:

- `VOID_MAIL_HOST`
- `VOID_MAIL_USERNAME`
- `VOID_MAIL_PASSWORD`
- `VOID_MAIL_PORT`
- `VOID_MAIL_SECURE`
- `VOID_MAIL_FROM_EMAIL`
- `VOID_MAIL_FROM_NAME`
- `VOID_MAIL_ORDER_TO` (where order notifications should arrive)

Optional:

- `VOID_MAIL_PROVIDER` (`gmail`, `yandex`, `mailru`, or `custom`)

4. Test sending.

```bash
php tools/send_test_mail.php your-address@example.com "Test subject" "Test body"
```

Mail provider quick setup
-------------------------

Gmail:

- Sign in at https://myaccount.google.com/
- Turn on 2-Step Verification: https://myaccount.google.com/security
- Open App Passwords: https://myaccount.google.com/apppasswords
- Create an app password for Mail / Other.
- Use the app password in `VOID_MAIL_PASSWORD`.

Fill these values:

```env
VOID_MAIL_PROVIDER=gmail
VOID_MAIL_HOST=smtp.gmail.com
VOID_MAIL_PORT=587
VOID_MAIL_SECURE=tls
VOID_MAIL_USERNAME=your@gmail.com
VOID_MAIL_PASSWORD=your-app-password
VOID_MAIL_FROM_EMAIL=your@gmail.com
VOID_MAIL_FROM_NAME="VOID & IRON"
VOID_MAIL_ORDER_TO=orders@example.com
```

Yandex:

- Sign in at https://id.yandex.ru/
- Open mail security / passwords in account settings.
- Create an app password if Yandex asks for it.

```env
VOID_MAIL_PROVIDER=yandex
VOID_MAIL_HOST=smtp.yandex.com
VOID_MAIL_PORT=587
VOID_MAIL_SECURE=tls
VOID_MAIL_USERNAME=you@yandex.com
VOID_MAIL_PASSWORD=your-app-password
VOID_MAIL_FROM_EMAIL=you@yandex.com
VOID_MAIL_FROM_NAME="VOID & IRON"
VOID_MAIL_ORDER_TO=orders@example.com
```

Mail.ru:

- Sign in at https://account.mail.ru/
- In security settings create an app password if required.

```env
VOID_MAIL_PROVIDER=mailru
VOID_MAIL_HOST=smtp.mail.ru
VOID_MAIL_PORT=465
VOID_MAIL_SECURE=ssl
VOID_MAIL_USERNAME=you@mail.ru
VOID_MAIL_PASSWORD=your-app-password
VOID_MAIL_FROM_EMAIL=you@mail.ru
VOID_MAIL_FROM_NAME="VOID & IRON"
VOID_MAIL_ORDER_TO=orders@example.com
```

What I can do for you after you set it up
----------------------------------------

- Verify the SMTP settings in the code.
- Test a real mail send from the terminal.
- Adjust the order and newsletter messages if you want different subjects or wording.
- Help switch the same setup to another provider if Gmail is not available.

Important notes
---------------

- For Gmail, use an App Password, not your normal account password.
- Keep `.env` out of Git. The repo already ignores it.
- If `composer install` fails, send me the exact error and I will help fix it.
