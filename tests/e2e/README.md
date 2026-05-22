E2E tests (Playwright)
=======================

Files in this folder implement Playwright Test scenarios for the VOID project.

Prerequisites
 - Node.js (>=14)
 - Install Playwright and dependencies in the project or globally:

```bash
cd c:\xampp\htdocs\VOID6
npx playwright install --with-deps
npm init -y
npm i -D @playwright/test
```

Running tests
 - Run all E2E tests from repository root:

```bash
npx playwright test --config=tests/E2E/playwright.config.js
```

Notes
 - Tests assume the app is served at `http://localhost/VOID6`. Override with `VOID_BASE_URL` env var.
 - Tests perform real registration/login flows which will create users in the site's database. Run against a test database or take a DB snapshot before running.
 - Server-side email sending (Mailer::send) is executed inside PHP in the `PlaceOrder` endpoint and cannot be observed directly from browser-only tests. The tests mock the `/order` response and assert that the browser made the correct request and displayed the success toast. To fully assert `Mailer::send` calls you need a server-side test hook (for example a test-only endpoint or logging target) that records sent emails.
 - This repository includes a small server-side test hook: if the file `tests/E2E/test_mail_log.jsonl` exists and is writable, `inc/Mailer.php` will append every outgoing email as a JSON line into that file. A simple endpoint `test_mail_log.php` is provided to read and clear the log from tests.
	 - The file `tests/E2E/test_mail_log.jsonl` is already created in the repo (empty). Ensure your web server user can write to it.
	 - The Playwright checkout test will clear the log, perform an order, then fetch `/test_mail_log.php` and assert that an email to the test user was recorded.
