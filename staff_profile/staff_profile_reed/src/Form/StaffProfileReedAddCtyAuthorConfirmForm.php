<?php

namespace Drupal\staff_profile_reed\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityRepository;
use Drupal\Core\Entity\EntityTypeBundleInfo;
use Drupal\Component\Datetime\Time;

class StaffProfileReedAddCtyAuthorConfirmForm extends ContentEntityConfirmFormBase {

  private  $county;

  //TODO: Look for a way to add this as a controller for staff_profile to automatically pick up entity
  function __construct(EntityRepository $repo, EntityTypeBundleInfo $bundle_info, Time $time) {
    //repo's entityTypeManager is protected variable, $repo->entityTypeManager

    parent::__construct($repo);//Construction of parent first should remove need for much of this constructor and initFormLangcodes function
    // $this->setEntityTypeManager(\Drupal::entityTypeManager());
    // $this->setEntityManager(\Drupal::entityManager());
    // $this->setModuleHandler(\Drupal::moduleHandler());
    // $this->setEntity(\Drupal::routeMatch()->getParameter('node'));


    $tid = \Drupal::routeMatch()->getParameter('cty');

    $this->county = $this->entityTypeManager->getStorage('taxonomy_term')->load($tid);
  }

  // //NOTE: May not be needed when entity manager is removed in 9.x+
  // protected function initFormLangcodes(FormStateInterface $form_state) {
  //   if (!$form_state->has('entity_default_langcode')) {
  //     $form_state->set('entity_default_langcode', $this->entity->getUntranslated()->language()->getId());
  //   }
  //   if (!$form_state->has('langcode')) {
  //     $form_state->set('langcode', \Drupal::languageManager()->getCurrentLanguage()->getId());
  //   }
  // }

  public function getQuestion() {
    return $this->t('Are you sure you want to add %name to %cty county?', array('%name' => $this->entity->label(), '%cty' => $this->county->label()));
  }

  public function getCancelUrl() {
    return new Url('staff_profile_reed.regional_director_panel');
  }

  public function getConfirmText() {
    return $this->t('Add from County');
  }

  public function getDescription() {
    return $this->t('Adds staff_profile from users authorized in county Web Editors.');
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $web_editor_qual = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['name' => 'Web Editor', 'vid' => 'editor_qualifications']);
    $web_editor = reset($web_editor_qual);
    $web_editor_qual_id = $web_editor->id();

    $this->entity->field_staff_profile_cty_author[] = ['target_id' => $this->county->id()];
    $this->entity->save();

    if (!in_array($web_editor_qual_id, array_column($this->entity->field_staff_profile_quals->getValue(), 'target_id'))) {
      $needs_training = true;
    } else {
      $needs_training = false;
    }
    //Send notification emails
    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'staff_profile_reed';
    $params['netid'] = $this->entity->label();
    $params['county'] = $this->county->label();
    $params['reg_director'] = \Drupal::currentUser()->getUsername();
    $params['needstraining'] = $needs_training;
    $send = false; //TODO: Set to true to send emails, set emails to testing email while not in production

    //Send to staff_profile
    $staff_profile_key = 'request_staff_profile_editor_training_profile';
    $staff_profile_email = 'eit_tcgerwig@iastate.edu';
    //$staff_profile_email = $this->entity->field_staff_profile_email->value;
    $langcode = $this->entity->getOwner()->getPreferredLangcode();
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
      . ($reg_dir_result['result'] !== true ? " Regional Director" . ($ext_result['result'] !== true || $staff_profile_result['result'] !== true ? "," : "") : "")
      . ($ext_result['result'] !== true ? " ExtensionWeb" . ($staff_profile_result['result'] !== true ? "," : "") : "")
      . ($staff_profile_result['result'] !== true ? " Staff Profile: " . $this->entity->field_staff_profile_email->value : "") . '.'), 'error');
    } else {
      drupal_set_message(t('Notification emails sent.'));
    }
    $form_state->setRedirect('staff_profile_reed.regional_director_panel');
  }
}
