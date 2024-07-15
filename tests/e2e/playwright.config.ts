/**
 * External dependencies
 */
import { defineConfig, devices } from '@playwright/test';
const testsRootPath = __dirname;

export default defineConfig( {
	reporter: process.env.CI
		? [ [ 'github' ] ]
		: 'list',

	testDir: `${ testsRootPath }/specs`,
	outputDir: `${ testsRootPath }/../../artifacts`,

	/* Run tests in files in parallel */
	fullyParallel: false,

	use: {
		/* Collect trace when retrying the failed test. See https://playwright.dev/docs/trace-viewer */
		trace: 'on-first-retry',
	},

	/* Configure projects for major browsers */
	projects: [
		{
			name: 'chromium',
			testMatch: /.*\.ts/,
			use: {
				baseURL: 'http://localhost:8889/',
				...devices[ 'Desktop Chrome' ],
			},
		},
	],
} );
