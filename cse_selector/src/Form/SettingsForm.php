<?php

namespace Drupal\cse_selector\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\cse_selector\Routing\RouteSubscriber;
use Drupal\Core\EventSubscriber\RouterRebuildSubscriber;

/*
 * Class SettingsForm
 */
class SettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
   protected function getEditableConfigNames() {
     return [
       'cse_selector.settings',
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
       $config = $this->config('cse_selector.settings');
       $site_vars = \Drupal::config('system.site');
       $form['cse_selector_id_key'] = [
         '#type' => 'textfield',
         '#title' => t('Google Custom Search Engine ID'),
         '#default_value' => $config->get('cse_selector_id_key'),
         '#description' => t('This is the Search Engine ID for the Google Custom Search Engine.'),
       ];
       $form['cse_selector_narrow_search_text'] = [
         '#type' => 'textfield',
         '#title' => t('Narrow Search Text'),
         '#default_value' => $config->get('cse_selector_narrow_search_text'),
         '#description' => t('This is the string that will be displayed with the radio to select narrow search. The sitename was automatically added to the end when the module was installed. Current site name: \'') . $site_vars->get('name') . t('\''),
       ];
      $form['cse_selector_narrow_search_query'] = [
        '#type' => 'textfield',
        '#title' => t('Query String for Narrow Search'),
        '#default_value' => $config->get('cse_selector_narrow_search_query'),
        '#description' => t('This is the string that will be used for the \'as_sitesearch\' parameter for Google Custom Search. The current base path was automatically added to the end when the module was installed. Current site path: \'') . base_path() . t('\''),
      ];
      $form['cse_selector_wide_search_text'] = [
        '#type' => 'textfield',
        '#title' => t('Wide Search Text'),
        '#default_value' => $config->get('cse_selector_wide_search_text'),
        '#description' => t('This is the string that will be displayed with the radio to select wide search.'),
      ];
      $form['cse_selector_default_search_type'] = [
        '#type' => 'radios',
        '#title' => t('Default Search Behavior'),
        '#options' => [
          'narrow' => t('Narrow'),
          'wide' => t('Wide'),
        ],
        '#default_value' => $config->get('cse_selector_default_search_type'),
        '#description' => t('This selects the default behavior of the custom search, either narrow or wide.'),
      ];
      $form['cse_selector_url_text'] = [
        '#type' => 'textfield',
        '#title' => t('Query Url Text'),
        '#default_value' => $config->get('cse_selector_url_text'),
        '#description' => t('This is the parameter that will be used in the URL as the query for Google Custom Search'),
      ];
      $form['cse_selector_results_page_name'] = [
        '#type' => 'textfield',
        '#title' => t('Search Results Page Name'),
        '#default_value' => $config->get('cse_selector_results_page_name'),
        '#description' => t('This will be the name of the page that the results will be displayed on.')
      ];

      return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
      parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      parent::submitForm($form, $form_state);
      $this->config('cse_selector.settings')
        ->set('cse_selector_id_key', $form_state->getValue('cse_selector_id_key'))
        ->set('cse_selector_narrow_search_text', $form_state->getValue('cse_selector_narrow_search_text'))
        ->set('cse_selector_narrow_search_query', $form_state->getValue('cse_selector_narrow_search_query'))
        ->set('cse_selector_wide_search_text', $form_state->getValue('cse_selector_wide_search_text'))
        ->set('cse_selector_default_search_type', $form_state->getValue('cse_selector_default_search_type'))
        ->set('cse_selector_url_text', $form_state->getValue('cse_selector_url_text'))
        ->set('cse_selector_results_page_name', $form_state->getValue('cse_selector_results_page_name'))
        ->save();
    \Drupal::service('router.builder')->rebuild();
  }
}
