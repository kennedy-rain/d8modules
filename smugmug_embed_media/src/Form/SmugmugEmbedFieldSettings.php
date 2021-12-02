<?php

namespace Drupal\smugmug_embed_field\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SmugmugEmbedFieldSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'smugmug_embed_field_settings';
  }
  
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'smugmug_embed_field.settings',
    ];
  }
  
  /**
  * {@inheritdoc}
  */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('smugmug_embed_field.settings');
    $form['smugmug_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Smugmug API Key:'),
      '#default_value' => "",
      '#description' => $this->t('Smugmug Api Key, This will appear empty even when a key is saved'),
    ];
    return $form;
  }
  
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }
  
  /**
  * {@inheritdoc}
  */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('smugmug_embed_field.settings');
    $config->set('smugmug_api_key', $form_state->getValue('smugmug_api_key'));
    $config->save();
    return parent::submitForm($form, $form_state);
  }
}
