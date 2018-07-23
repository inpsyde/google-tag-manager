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
    private $settingsRepo;

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
     * @param SettingsRepository $settingsRepo
     * @param SettingsPageAuthInterface $auth
     * @param Request $request
     */
    public function __construct(
        SettingsPageViewInterface $view,
        SettingsRepository $settingsRepo,
        SettingsPageAuthInterface $auth = null,
        Request $request = null
    ) {

        $this->view = $view;
        $this->settingsRepo = $settingsRepo;
        $this->auth = $auth ?? new SettingsPageAuth($this->view->slug());
        $this->request = $request ?? Request::fromGlobals();
        $this->form = new Form($this->view->name());
    }

    /**
     * @return bool
     * @throws \ChriCo\Fields\Exception\LogicException
     */
    public function register(): bool
    {
        // set init data to all elements from database.
        $this->form->withData($this->settingsRepo->options());

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

        add_action('load-'.$hook, [$this, 'update']);

        return true;
    }

    /**
     * Add a single Element.
     *
     * @param ElementInterface $element
     * @param FilterInterface[] $filters
     * @param ValidatorInterface[] $validators
     */
    public function addElement(ElementInterface $element, array $filters = [], array $validators = [])
    {
        $this->form->withElement($element);

        foreach ($filters as $filter) {
            $this->form->withFilter($element->name(), $filter);
        }

        foreach ($validators as $validator) {
            $this->form->withValidator($element->name(), $validator);
        }
    }

    /**
     * If the POST-Request is valid, then update the Settings.
     *
     * @return bool
     * @throws \ChriCo\Fields\Exception\ElementNotFoundException
     */
    public function update(): bool
    {
        if ($this->request->server()->get('REQUEST_METHOD', 'GET') !== 'POST') {
            return false;
        }

        $postData = $this->request->data()->all();
        if (! $this->auth->isAllowed($postData)) {
            return false;
        }

        $this->form->submit($postData);

        $storedData = $this->settingsRepo->options();
        $data = [];
        foreach ($this->form->elements() as $name => $element) {
            /** @var ElementInterface|ErrorAwareInterface $element */
            if ($element instanceof ErrorAwareInterface
                && $element->hasErrors()
                && isset($storedData[$name])
            ) {
                $data[$name] = $storedData[$name];

                continue;
            }
            $data[$name] = $element->value();
        }

        if (! $this->settingsRepo->update($data)) {
            do_action(
                LogEvent::ACTION,
                'error',
                'Update of settings failed.',
                [
                    'method' => __METHOD__,
                    'form' => $this->form,
                    'data' => $data,
                ]
            );

            return false;
        }

        return true;
    }
}
