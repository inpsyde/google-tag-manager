/**
 * WordPress dependencies
 */
import { Card, CardBody, Spinner } from '@wordpress/components';

export const LoadingCard: FunctionComponent = () => {
	return (
		<Card>
			<CardBody>
				<Spinner />
			</CardBody>
		</Card>
	);
};
