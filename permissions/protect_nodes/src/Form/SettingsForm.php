<?php

namespace Drupal\protect_nodes\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/*
 * Class SettingsForm
 */
class SettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'protect_nodes.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
   public function getFormID() {
     return 'settings_form';
   }

   /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state) {
      $config = $this->config('protect_nodes.settings');
      $form['protected_urls'] = array(
        '#type' => 'textarea',
        '#title' => $this->t('URLs of protected pages'),
        '#description' => $this->t('Example: <br/>&nbsp;&nbsp;/homepage<br/>&nbsp;&nbsp;/events<br/>Separate each url with a new line'),
        '#size' => 64,
        '#default_value' => !empty($config->get('protected_urls')) ? preg_replace('/,/',"\r\n",$config->get('protected_urls')) : '',
        '#required' => TRUE,
      );

      return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      parent::submitForm($form, $form_state);
      //If checked, run sync
      $this->config('protect_nodes.settings')
        //->set('protected_urls', trim($form_state->getValue('protected_urls')))
        ->set('protected_urls', preg_replace('/\s+(?=[hw])/',',',trim($form_state->getValue('protected_urls'))))
        ->save();
  }
}
