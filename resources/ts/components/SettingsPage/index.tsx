/**
 * WordPress dependencies
 */
import { Fragment, useEffect, useState } from '@wordpress/element';
import {
	Button,
	Spinner,
	__experimentalSpacer as Spacer,
	Snackbar,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
/**
 * Internal dependencies
 */
import { SettingsCard } from '../SettingsCard';
import { LoadingCard } from '../LoadingCard';
import { ErrorCard } from '../ErrorCard';
import { getSettingsPage, updateSettingsPage } from '../../api';

type SavingStatus = 'idle' | 'saving' | 'errored' | 'succeeded';
type LoadingState = 'idle' | 'loading' | 'errored' | 'succeeded';

export const SettingsPage: FunctionComponent = () => {
	const [ dataLayer, setDataLayer ] = useState< DataLayer >();
	const [ collectors, setCollectors ] = useState< Collector[] >();
	const [ settings, setSettings ] = useState< Settings >();
	const [ errors, setErrors ] = useState< Errors >();
	const [ savingStatus, setSavingStatus ] =
		useState< SavingStatus >( 'idle' );
	const [ loadingState, setLoadingState ] =
		useState< LoadingState >( 'loading' );

	useEffect(
		(): void => {
			getSettingsPage()
				.then( ( response: DataLayerResponse ) => {
					setDataLayer( { ...response.dataLayer } );
					setCollectors( [ ...response.collectors ] );
					setSettings( { ...response.settings } );
					setErrors( { ...response.errors } );
					setLoadingState( 'succeeded' );
				} )
				.catch( ( error ) => {
					setLoadingState( 'errored' );
				} );
		},
		// eslint-disable-next-line react-hooks/exhaustive-deps
		[]
	);

	const onUpdateSettings = (
		id: string,
		name: string,
		value: SettingsValue
	): void => {
		const newSettings = { ...settings };
		newSettings[ id ] = {
			...( newSettings[ id ] ?? {} ),
			...{ [ name ]: value },
		};
		setSettings( newSettings );
	};

	const isCollectorEnabled = ( id: string ): boolean => {
		const enabledCollectors =
			settings[ dataLayer.id as 'dataLayer' ]?.enabled_collectors ?? [];
		return settings && enabledCollectors.includes( id );
	};

	return (
		<div className={ `settings settings--${ savingStatus }` }>
			{ [ 'loading', 'idle' ].includes( loadingState ) && (
				<LoadingCard />
			) }
			{ loadingState === 'errored' && <ErrorCard /> }
			{ loadingState === 'succeeded' && (
				<Fragment>
					<SettingsCard
						key={ dataLayer.id }
						name={ __(
							'DataLayer - General settings',
							'inpsyde-google-tag-manager'
						) }
						specification={ dataLayer.specification }
						settings={ settings[ dataLayer.id ] ?? {} }
						errors={ errors[ dataLayer.id ] ?? {} }
						onUpdate={ ( name: string, value: SettingsValue ) => {
							onUpdateSettings( dataLayer.id, name, value );
						} }
					/>
					{ collectors.map( ( collector: Collector ) => {
						return (
							isCollectorEnabled( collector.id ) && (
								<SettingsCard
									key={ collector.id }
									name={ collector.name }
									description={ collector.description }
									specification={ collector.specification }
									settings={ settings[ collector.id ] ?? {} }
									errors={ errors[ collector.id ] ?? {} }
									onUpdate={ (
										name: string,
										value: SettingsValue
									) => {
										onUpdateSettings(
											collector.id,
											name,
											value
										);
									} }
								/>
							)
						);
					} ) }
					<Spacer margin={ 5 } />
					<Button
						variant="primary"
						onClick={ () => {
							setSavingStatus( 'saving' );
							updateSettingsPage( settings ).then(
								( response: DataLayerResponse ) => {
									setErrors( response.errors );
									setSavingStatus(
										Object.keys( response.errors )
											.length === 0
											? 'succeeded'
											: 'errored'
									);
									setSettings( response.settings );
								}
							);
						} }
						disabled={ savingStatus === 'saving' }
					>
						{ savingStatus === 'saving' ? (
							<div>
								<Spinner />
								{ __(
									'Saving…',
									'inpsyde-google-tag-manager'
								) }
							</div>
						) : (
							__( 'Save', 'inpsyde-google-tag-manager' )
						) }
					</Button>
					{ savingStatus === 'errored' && (
						<Snackbar status="error">
							{ __(
								'An error happened. Please check your settings for error messages.',
								'inpsyde-google-tag-manager'
							) }
						</Snackbar>
					) }
					{ savingStatus === 'succeeded' && (
						<Snackbar status="success">
							{ __(
								'New settings successfully stored.',
								'inpsyde-google-tag-manager'
							) }
						</Snackbar>
					) }
				</Fragment>
			) }
		</div>
	);
};
