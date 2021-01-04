<?php
namespace Drupal\staff_profile_reed\Form;

use \Drupal\Core\Form\FormBase;
use \Drupal\Core\Form\FormStateInterface;
use \Drupal\taxonomy\Entity\Term;

class StaffProfileReedAddCtyEditorForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'staff_profile_reed_add_cty_editor';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['netid'] = [
      '#type' => 'textfield',
      '#autocomplete_route_name' => 'staff_profile_reed.autocomplete',
      '#autocomplete_route_parameters' => [
        'field_name' => 'field_staff_profile_netid',
        'count' => 5
      ]
    ];
    $form['cty'] = [
      '#type' => 'hidden',
      '#default_value' => "NaN"
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add County Author')
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $netid = $form_state->getValue('netid');
    $tid = $form_state->getValue('cty');
    $county = Term::load($tid)->get('name')->value;

    $staff_profiles = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['field_staff_profile_netid' => $netid]);;
    if ($staff_profile = reset($staff_profiles)) {
      $staff_profile->field_staff_profile_cty_author[] = ['target_id' => $tid];
      $staff_profile->save();

      //Send notification emails
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'staff_profile_reed';
      $params['netid'] = $staff_profile->label();
      $params['county'] = $county;
      $params['reg_director'] = \Drupal::currentUser()->getUsername();
      $send = false; //TODO: Set to true to send emails, set emails to testing email while not in production

      //Send to staff_profile
      $staff_profile_key = 'request_staff_profile_editor_training_profile';
      $staff_profile_email = 'eit_tcgerwig@iastate.edu';
      //$staff_profile_email = $staff_profile->field_staff_profile_email;
      $langcode = $staff_profile->getOwner()->getPreferredLangcode();
      $staff_profile_result = $mailManager->mail($module, $staff_profile_key, $staff_profile_email, $langcode, $params, NULL, $send);

      //Send to regional director
      $director_key = 'request_staff_profile_editor_training_reg_director';
      $reg_director_email = 'eit_tcgerwig@iastate.edu';
      //$reg_director_email = \Drupal::currentUser()->getEmail();
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $reg_dir_result = $mailManager->mail($module, $director_key, $reg_director_email, $langcode, $params, NULL, $send);

      //Send to extweb
      $extweb_key = 'request_staff_profile_editor_training_extweb';
      $extweb_email = 'eit_tcgerwig@iastate.edu';
      //$extweb_email = 'extensionweb@iastate.edu';
      $langcode = 'en';
      $ext_result = $mailManager->mail($module, $extweb_key, $extweb_email, $langcode, $params, NULL, $send);

      if ($reg_dir_result['result'] !== true || $ext_result['result'] !== true || $staff_profile_result['result'] !== true) {
        drupal_set_message(t('There was a problem sending notification emails to:'
        . ($reg_dir_result['result'] !== true ? " Regional Director" : "")
        . ($ext_result['result'] !== true ? " ExtensionWeb" : "")
        . ($staff_profile_result['result'] !== true ? " Staff Profile: " . $staff_profile->field_staff_profile_email : "") . '.'));
      } else {
        drupal_set_message(t('Notification emails sent.'));
      }
    }
  }
}
