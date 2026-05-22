/**
 * Playwright Test configuration for VOID E2E tests
 * Run with: npx playwright test --config=tests/E2E/playwright.config.js
 */
module.exports = {
  testDir: '.',
  timeout: 30000,
  use: {
    baseURL: process.env.VOID_BASE_URL || 'http://localhost/VOID6',
    headless: true,
    viewport: { width: 1280, height: 800 },
    actionTimeout: 10000,
  },
};
