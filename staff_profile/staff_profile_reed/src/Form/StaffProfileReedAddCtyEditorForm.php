<?php
namespace Drupal\staff_profile_reed\Form;

use \Drupal\Core\Form\FormBase;
use \Drupal\Core\Form\FormStateInterface;

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

    $staff_profiles = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['field_staff_profile_netid' => $netid]);;
    if ($staff_profile = reset($staff_profiles)) {
      $staff_profile->field_staff_profile_cty_author[] = ['target_id' => $tid];
      $staff_profile->save();
    }
  }
}
