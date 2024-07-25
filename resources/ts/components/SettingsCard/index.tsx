/**
 * WordPress dependencies
 */
import {
	Card,
	CardBody,
	CardHeader,
	__experimentalSpacer as Spacer,
	CardFooter,
} from '@wordpress/components';
import { Fragment } from '@wordpress/element';
/**
 * Internal dependencies
 */
import { SpecificationField } from '../SpecificationField';

interface SettingsCardProps {
	name: string;
	description: string;
	specification: Specification[];
	errors: { [ key: string ]: string[] };
	settings:
		| DataLayerSettings
		| UserDataSettings
		| SiteInfoSettings
		| PostDataSettings
		| SearchSettings
		| CustomSettings;
	onUpdate: ( name: string, value: SettingsValue ) => void;
}

export const SettingsCard: FunctionComponent< SettingsCardProps > = ( {
	name,
	description,
	specification,
	settings,
	errors,
	onUpdate,
} ) => {
	return (
		<Fragment>
			<Spacer margin="5" />
			<Card key={ name }>
				<CardHeader>{ name }</CardHeader>
				{ description && <CardFooter>{ description }</CardFooter> }
				<CardBody>
					{ specification.map( ( spec: Specification ) => {
						return (
							<SpecificationField
								key={ spec.name }
								spec={ spec }
								currentValue={ settings[ spec.name ] ?? '' }
								errorMessages={ errors[ spec.name ] ?? '' }
								onUpdate={ onUpdate }
							/>
						);
					} ) }
				</CardBody>
			</Card>
		</Fragment>
	);
};
