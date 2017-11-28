<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Mink\Exception\ElementNotFoundException;
use PaulGibbs\WordpressBehatExtension\Context\RawWordpressContext;

/**
 * Class FeatureContext
 *
 * @package Inpsyde\GoogleTagManager\Tests\Behat
 */
class FeatureContext extends RawWordpressContext implements Context {

	/**
	 * @Then I click the :arg1 element
	 */
	public function iClickElement( $element ) {

		$page = $this->getSession()
			->getPage();

		$findName = $page->find( "css", $element );
		if ( ! $findName ) {
			throw new Exception( $element . " could not be found" );
		} else {
			$findName->click();
		}
	}

	/**
	 * @Then I should see the :selector tab is visible.
	 */
	public function iShouldSeeTheTabIsVisible( $selector ) {

		$page = $this->getSession()
			->getPage();

		$element = $page->find( "css", $selector );
		if ( ! $element ) {
			throw new ElementNotFoundException( $this->getSession(), 'li', 'href', $selector );
		}

		$attribute = $element->getAttribute( 'aria-hidden' );
		if ( $attribute === 'true' ) {
			throw new Exception(
				$selector . " has not attribute aria-hidden='true'. Instead it is '" . $attribute . "'"
			);
		}
	}

}
