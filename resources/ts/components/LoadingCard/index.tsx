/**
 * WordPress dependencies
 */
import {
	Card,
	CardBody,
	__experimentalHStack as HStack,
	__experimentalText as Text,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { ReactComponent as LeafBeat } from '../../../images/leaf-beat.svg';

export const LoadingCard: FunctionComponent = () => {
	return (
		<Card>
			<CardBody>
				<HStack justify="start" spacing="4">
					<LeafBeat />
					<Text size="large">
						{ __( 'Loading…', 'inpsyde-google-tag-manager' ) }
					</Text>
				</HStack>
			</CardBody>
		</Card>
	);
};
