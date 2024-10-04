/**
 * External dependencies
 */
import { expect, WpPage } from '@inpsyde/playwright-utils/build';

type CollectorName = 'User' | 'Site info' | 'Post data' | 'Search' | string;

type UserFieldsName =
	| 'ID'
	| 'Role'
	| 'Nickname'
	| 'Description'
	| 'First name'
	| 'Last name'
	| 'E-Mail'
	| 'Url'
	| string;

export class PluginSettingsPage extends WpPage {
	url = '/wp-admin/options-general.php?page=google-tag-manager';

	submitButton = () => this.page.getByRole( 'button', { name: 'Save' } );
	submitButtonIsSaving = () =>
		this.page.getByRole( 'button', { name: 'Saving...' } );
	successMessage = () =>
		this.page.locator(
			'.components-snackbar__content:has-text("New settings successfully stored.")'
		);
	errorMessage = () =>
		this.page.locator(
			'.components-snackbar__content:has-text("An error happened.")'
		);
	settingsContainer = () => this.page.locator( '.settings' );

	// DataLayer/General
	gtmIdInput = () => this.page.getByLabel( 'Google Tag Manager ID' );
	autoInsertNoscriptSelect = () =>
		this.page.getByLabel( 'Auto insert noscript in body' );
	dataLayerNameInput = () => this.page.getByLabel( 'dataLayer name' );
	enableCollectorCheckbox = ( collector: CollectorName ) =>
		this.page.locator(
			'.components-checkbox-control__label:text-is("' + collector + '")'
		);

	// User
	visitorRoleInput = () => this.page.getByLabel( 'Visitor role' );
	userFieldsCheckbox = ( field: UserFieldsName ) =>
		this.page.getByLabel( field );

	submitForm = async () => {
		await this.submitButton().click();
	};

	statusSuccess = async () =>
		await expect( this.settingsContainer() ).toHaveClass(
			'settings settings--succeeded'
		);
	statusIdle = async () =>
		await expect( this.settingsContainer() ).toHaveClass(
			'settings settings--idle'
		);
	statusSaving = async () =>
		await expect( this.settingsContainer() ).toHaveClass(
			'settings settings--saving'
		);
	statusErrored = async () =>
		await expect( this.settingsContainer() ).toHaveClass(
			'settings settings--errored'
		);

	successfullySaved = async () => {
		await this.statusSuccess();
		await expect( this.successMessage() ).toBeVisible();
	};

	erroneousSaved = async () => {
		await this.statusErrored();
		await expect( this.errorMessage() ).toBeVisible();
	};

	// DataLayer/General
	fillInGtmId = async ( gtmId: string ) => {
		await this.gtmIdInput().fill( gtmId );
	};

	fillInDataLayerName = async ( name: string ) => {
		await this.dataLayerNameInput().fill( name );
	};

	enableAutoInsertNoScript = async () => {
		await this.autoInsertNoscriptSelect().selectOption( 'enable' );
	};

	disableAutoInsertNoScript = async () => {
		await this.autoInsertNoscriptSelect().selectOption( 'disable' );
	};

	enableCollector = async ( ...collector: CollectorName[] ) => {
		for ( const name of collector ) {
			await this.enableCollectorCheckbox( name ).check();
		}
	};

	disableCollector = async ( ...collector: CollectorName[] ) => {
		for ( const name of collector ) {
			await this.enableCollectorCheckbox( name ).uncheck();
		}
	};

	// User
	fillVisitorRole = async ( role: string ) => {
		await this.visitorRoleInput().fill( role );
	};

	enableUserField = async ( ...field: UserFieldsName[] ) => {
		for ( const name of field ) {
			await this.enableCollectorCheckbox( name ).click();
		}
	};
}
