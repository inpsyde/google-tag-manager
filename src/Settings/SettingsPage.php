<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Settings;

use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\Form;
use ChriCo\Fields\Element\FormInterface;
use ChriCo\Fields\ErrorAwareInterface;
use Inpsyde\Filter\FilterInterface;
use Inpsyde\GoogleTagManager\Event\LogEvent;
use Inpsyde\GoogleTagManager\Http\Request;
use Inpsyde\GoogleTagManager\Settings\Auth\SettingsPageAuth;
use Inpsyde\GoogleTagManager\Settings\Auth\SettingsPageAuthInterface;
use Inpsyde\GoogleTagManager\Settings\View\SettingsPageViewInterface;
use Inpsyde\Validator\ValidatorInterface;

/**
 * @package Inpsyde\GoogleTagManager\Options
 */
class SettingsPage
{

    /**
     * @var SettingsRepository
     */
    private $settings_repository;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var SettingsPageViewInterface
     */
    private $view;

    /**
     * @var SettingsPageAuth
     */
    private $auth;

    /**
     * @var Request
     */
    private $request;

    /**
     * View constructor.
     *
     * @param SettingsPageViewInterface $view
     * @param SettingsRepository        $settings_repository
     * @param SettingsPageAuthInterface $auth
     * @param Request                   $request
     */
    public function __construct(
        SettingsPageViewInterface $view,
        SettingsRepository $settings_repository,
        SettingsPageAuthInterface $auth = null,
        Request $request = null
    ) {

        $this->view                = $view;
        $this->settings_repository = $settings_repository;
        $this->auth                = $auth ?? new SettingsPageAuth($this->view->slug());
        $this->request             = $request ?? Request::fromGlobals();
        $this->form                = new Form($this->view->name());
    }

    /**
     * Adding menu item to admin-page.
     *
     * @return    bool
     */
    public function register(): bool
    {

        // set init data to all elements from database.
        $this->form->set_data($this->settings_repository->getOptions());

        $hook = add_options_page(
            $this->view->name(),
            $this->view->name(),
            $this->auth->cap(),
            $this->view->slug(),
            function () {

                $this->view->render(
                    $this->form,
                    $this->auth->nonce()
                );
            }
        );

        add_action('load-' . $hook, [$this, 'update']);

        return true;
    }

    /**
     * Add a single Element.
     *
     * @param ElementInterface     $element
     * @param FilterInterface[]    $filters
     * @param ValidatorInterface[] $validators
     */
    public function addElement(ElementInterface $element, array $filters = [], array $validators = [])
    {

        $this->form->add_element($element);

        foreach ($filters as $filter) {
            $this->form->add_filter($element->get_name(), $filter);
        }

        foreach ($validators as $validator) {
            $this->form->add_validator($element->get_name(), $validator);
        }
    }

    /**
     * If the POST-Request is valid, then update the Settings.
     *
     * @wp-hook load-{$hook}
     *
     * @return bool
     */
    public function update(): bool
    {

        if ($this->request->server()->get('REQUEST_METHOD', 'GET') !== 'POST') {
            return false;
        }

        $post_data = $this->request->data()->all();
        if (!$this->auth->isAllowed($post_data)) {
            return false;
        }

        $this->form->submit($post_data);

        $stored_data = $this->settings_repository->getOptions();
        $data        = [];
        foreach ($this->form->get_elements() as $name => $element) {
            /** @var ElementInterface|ErrorAwareInterface $element */
            if ($element instanceof ErrorAwareInterface && $element->has_errors() && isset($stored_data[ $name ])) {
                $data[ $name ] = $stored_data[ $name ];

                continue;
            }
            $data[ $name ] = $element->get_value();
        }

        if (!$this->settings_repository->updateOptions($data)) {
            do_action(
                LogEvent::ACTION,
                'error',
                'Update of settings failed.',
                [
                    'method' => __METHOD__,
                    'form'   => $this->form,
                    'data'   => $data,
                ]
            );

            return false;
        }

        return true;
    }
}
