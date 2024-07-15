/**
 * External dependencies
 */
import { expect, WpPage } from '@inpsyde/playwright-utils/build';

type CollectorName =
	| 'dataLayer'
	| 'userData'
	| 'siteInfo'
	| 'search'
	| 'postData'
	| string;

type UserFieldsName =
	| 'ID'
	| 'role'
	| 'nickname'
	| 'description'
	| 'first_name'
	| 'last_name'
	| 'email'
	| 'url'
	| string;

export class PluginSettingsPage extends WpPage {
	url = '/wp-admin/options-general.php?page=inpsyde-google-tag-manager';

	submitButton = () => this.page.locator( '#submit' );
	successMessage = () =>
		this.page.getByText( 'New settings successfully stored.' );
	errorMessage = () =>
		this.page.getByText(
			'New settings stored, but there are some errors. Please scroll down to have a look.'
		);

	// DataLayer/General
	gtmIdInput = () =>
		this.page.locator( '#inpsyde-google-tag-manager_dataLayer_gtm_id' );
	autoInsertNoscriptSelect = () =>
		this.page.locator(
			'#inpsyde-google-tag-manager_dataLayer_auto_insert_noscript'
		);
	dataLayerNameInput = () =>
		this.page.locator(
			'#inpsyde-google-tag-manager_dataLayer_datalayer_name'
		);
	enableCollectorCheckbox = ( collector: CollectorName ) =>
		this.page.locator(
			`#inpsyde-google-tag-manager_dataLayer_enabled_collectors_${ collector }`
		);

	// User
	visitorRoleInput = () =>
		this.page.locator(
			'#inpsyde-google-tag-manager_userData_visitor_role'
		);
	userFieldsCheckbox = ( field: UserFieldsName ) =>
		this.page.locator(
			`#inpsyde-google-tag-manager_userData_fields_user_${ field }`
		);

	// Tabs
	tabItemLink = ( tabName: CollectorName ) =>
		this.page.locator( `a[href="#tab--${ tabName }"]` );
	tabContent = ( tabName: CollectorName ) =>
		this.page.locator( `#tab--${ tabName }` );

	selectTab = async ( tabName: CollectorName ) => {
		await this.tabItemLink( tabName ).click();
		await expect( this.tabContent( tabName ) ).toBeVisible();
	};

	saveForm = async () => {
		await this.submitButton().click();
		await this.page.waitForLoadState( 'domcontentloaded' );
	};

	// DataLayer/General
	fillInGtmId = async ( gtmId: string ) => {
		await this.selectTab( 'dataLayer' );
		await this.gtmIdInput().fill( gtmId );
	};

	fillInDataLayerName = async ( name: string ) => {
		await this.selectTab( 'dataLayer' );
		await this.dataLayerNameInput().fill( name );
	};

	enableAutoInsertNoScript = async () => {
		await this.selectTab( 'dataLayer' );
		await this.autoInsertNoscriptSelect().selectOption( 'enable' );
	};

	disableAutoInsertNoScript = async () => {
		await this.selectTab( 'dataLayer' );
		await this.autoInsertNoscriptSelect().selectOption( 'disable' );
	};

	enableCollector = async ( ...collector: CollectorName[] ) => {
		await this.selectTab( 'dataLayer' );
		for ( const name of collector ) {
			await this.enableCollectorCheckbox( name ).check();
		}
	};

	disableCollector = async ( ...collector: CollectorName[] ) => {
		await this.selectTab( 'dataLayer' );
		for ( const name of collector ) {
			await this.enableCollectorCheckbox( name ).uncheck();
		}
	};

	// User
	fillVisitorRole = async ( role: string ) => {
		await this.selectTab( 'userData' );
		await this.visitorRoleInput().fill( role );
	};

	enableUserField = async ( ...field: UserFieldsName[] ) => {
		await this.selectTab( 'userData' );
		for ( const name of field ) {
			await this.enableCollectorCheckbox( name ).click();
		}
	};
}
