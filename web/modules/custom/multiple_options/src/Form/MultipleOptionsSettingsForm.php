<?php

namespace Drupal\multiple_options\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configuration form for Multiple Options Field settings.
 */
class MultipleOptionsSettingsForm extends ConfigFormBase
{

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return ['multiple_options_field.settings'];
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'multiple_options_field_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('multiple_options_field.settings');

        $form['options_count'] = [
            '#type' => 'number',
            '#title' => $this->t('Number of Options'),
            '#default_value' => $config->get('options_count'),
            '#required' => TRUE,
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->config('multiple_options_field.settings')
            ->set('options_count', $form_state->getValue('options_count'))
            ->save();

        parent::submitForm($form, $form_state);
    }
}
