/**
 * WordPress dependencies
 */
import { Card, CardBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export const ErrorCard: FunctionComponent = () => {
	return (
		<Card>
			<CardBody>
				<p>
					{ __( 'An error happened.', 'inpsyde-google-tag-manager' ) }
				</p>
			</CardBody>
		</Card>
	);
};
