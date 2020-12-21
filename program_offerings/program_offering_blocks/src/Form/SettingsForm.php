<?php

namespace Drupal\program_offering_blocks\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SettingsForm.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'program_offering_blocks.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('program_offering_blocks.settings');
    $form['number_of_blocks'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Number of Blocks to Create'),
      '#description' => $this->t('Number of Blocks to Create, each block can be placed independantly of each other.'),
      '#maxlength' => 5,
      '#size' => 5,
      '#default_value' => $config->get('number_of_blocks'),
    ];

    $form['url'] = [
      '#type' => 'textfield',
      '#title' => t('URL of Program Offerings Feeds'),
      '#description' => t('URL of JSON feed containing Program Offerings from MyData'),
      '#size' => 175,
      '#maxlength' => 300,
      '#default_value' => $config->get('url'),
    ];



    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    parent::submitForm($form, $form_state);

    $this->config('program_offering_blocks.settings')
      ->set('number_of_blocks', $form_state->getValue('number_of_blocks'))
      ->set('url', $form_state->getValue('url'))
      ->save();
  }
}
