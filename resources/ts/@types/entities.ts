interface SelectOption
	extends Readonly< {
		label: string;
		value: string;
		id?: string;
	} > {
	disabled?: boolean;
}

interface Specification
	extends Readonly< {
		label: string;
		name: string;
		description?: string;
		type: string;
		choices?: SelectOption[];
	} > {}

interface Collector
	extends Readonly< {
		id: string;
		name: string;
		description: string;
		specification: Specification[];
	} > {}

interface DataLayerSettings {
	auto_insert_noscript: 'enable' | 'disable';
	datalayer_name: string;
	enabled_collectors: string[];
	gtm_id: string;
}

interface PostDataSettings {
	author_fields: string[];
	post_fields: string[];
}

interface SearchSettings {
	fields: string[];
}

interface SiteInfoSettings {
	blog_info: string[];
	multisite_fields: string[];
}

interface UserDataSettings {
	fields: string[];
	visitor_role: string;
}

type SettingsValue = string | string[] | number | number[];

interface CustomSettings {
	[ key: string ]: SettingsValue;
}

interface Settings {
	dataLayer: DataLayerSettings;
	postData: PostDataSettings;
	search: SearchSettings;
	siteInfo: SiteInfoSettings;
	userData: UserDataSettings;

	// Custom settings
	[ key: string ]: CustomSettings;
}

interface Errors {
	[ key: string ]: {
		[ key: string ]: string[];
	};
}

interface DataLayer
	extends Readonly< {
		id: string;
		specification: Specification[];
	} > {}

interface DataLayerResponse
	extends Readonly< {
		dataLayer: DataLayer;
		collectors: Collector[];
		settings: Settings;
		errors: Errors;
	} > {}
