/**
 * WordPress dependencies
 */
import { Fragment } from '@wordpress/element';
import {
	BaseControl,
	Button,
	CheckboxControl,
	SelectControl,
	TextControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

interface SpecificationFieldProps {
	spec: Specification;
	currentValue: SettingsValue;
	errorMessages: string[];
	onUpdate: ( name: string, value: SettingsValue ) => void;
}

export const SpecificationField: FunctionComponent<
	SpecificationFieldProps
> = ( { spec, errorMessages, currentValue, onUpdate } ) => {
	const doUpdate = ( newValue: SettingsValue ) => {
		onUpdate( spec.name, newValue );
	};
	const styles = errorMessages
		? {
				borderLeft: '4px solid #d63638',
				paddingLeft: '10px',
		  }
		: {};

	return (
		<div style={ styles }>
			{ spec.type === 'text' && (
				<TextControl
					label={ spec.label }
					value={ currentValue }
					help={ spec.description }
					onChange={ doUpdate }
				/>
			) }
			{ spec.type === 'select' && (
				<SelectControl
					label={ spec.label }
					value={ currentValue }
					name={ spec.name }
					options={ spec.choices }
					help={ spec.description }
					onChange={ doUpdate }
				/>
			) }
			{ spec.type === 'checkbox' && (
				<BaseControl id={ spec.name } label={ spec.label }>
					{ spec.choices.length > 2 && (
						<Fragment>
							<Button
								onClick={ () => {
									const allChoices = spec.choices.map(
										( choice: SelectOption ) => choice.value
									);
									doUpdate( allChoices );
								} }
							>
								{ __(
									'Select all',
									'inpsyde-google-tag-manager'
								) }
							</Button>
							<Button onClick={ () => doUpdate( [] ) }>
								{ __(
									'Deselect all',
									'inpsyde-google-tag-manager'
								) }
							</Button>
						</Fragment>
					) }
					{ spec.choices.map( ( choice: SelectOption ) => {
						return (
							<CheckboxControl
								key={ choice.value }
								checked={
									currentValue?.includes( choice.value ) ??
									false
								}
								name={ spec.name }
								label={ choice.label }
								onChange={ ( checked: boolean ) => {
									let newSelection: string[] = [
										...currentValue,
									];
									if ( checked ) {
										newSelection.push( choice.value );
									} else {
										newSelection = newSelection.filter(
											( value: string ): boolean =>
												value !== choice.value
										);
									}
									doUpdate( newSelection );
								} }
							/>
						);
					} ) }
					<p>{ spec.description }</p>
				</BaseControl>
			) }
			{ errorMessages &&
				errorMessages.map( ( error: string, index: number ) => {
					return (
						<p key={ spec.name + index } className="error-message">
							{ error }
						</p>
					);
				} ) }
		</div>
	);
};
