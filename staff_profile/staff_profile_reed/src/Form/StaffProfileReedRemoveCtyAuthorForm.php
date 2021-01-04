<?php

namespace Drupal\staff_profile_reed\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;

/**
 * @ingroup staff_profile_reed
 */
class StaffProfileReedRemoveCtyAuthorForm extends ContentEntityConfirmFormBase {
  //County to be removed
  private  $county;

  //TODO: Look for a way to add this as a controller for staff_profile to automatically pick up entity
  function __construct() {
    $this->setEntityTypeManager(\Drupal::entityTypeManager());
    $this->setEntityManager(\Drupal::entityManager());
    $this->setModuleHandler(\Drupal::moduleHandler());
    $path = explode('/', \Drupal::service('path.current')->getPath());
    $nid = $path[2];
    $tid = $path[4];
    $entity = $this->entityTypeManager->getStorage('node')->load($nid);
    $this->setEntity($entity);
    $this->county = $this->entityTypeManager->getStorage('taxonomy_term')->load($tid);
  }

  //NOTE: May not be needed when entity manager is removed in 9.x+
  protected function initFormLangcodes(FormStateInterface $form_state) {
    if (!$form_state->has('entity_default_langcode')) {
      $form_state->set('entity_default_langcode', $this->entity->getUntranslated()->language()->getId());
    }
    if (!$form_state->has('langcode')) {
      $form_state->set('langcode', \Drupal::languageManager()->getCurrentLanguage()->getId());
    }
  }

  public function getQuestion() {
    return $this->t('Are you sure you want to remove %name from %cty county?', array('%name' => $this->entity->label(), '%cty' => $this->county->label()));
  }

  //Fix Incoming
  //https://www.drupal.org/project/drupal/issues/2582295
  public function getCancelUrl() {
    return new Url('staff_profile_reed.regional_director_panel');
  }

  public function getConfirmText() {
    return $this->t('Remove from County');
  }

  public function getDescription() {
    return $this->t('Removes staff_profile from users authorized in %cty county Web Editors.', array('%cty' => $this->county->label()));
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    //Remove item
    $staff_profile = $this->getEntity();
    $ctys = $staff_profile->get('field_staff_profile_cty_author')->getValue();
    $key = array_search($this->county->id(), array_column($ctys, 'target_id'));
    $staff_profile->get('field_staff_profile_cty_author')->removeItem($key);
    $staff_profile->save();

    //Send Mail
    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'staff_profile_reed';
    $params['netid'] = $this->entity->label();
    $params['county'] = $this->county->label();
    $params['reg_director'] = \Drupal::currentUser()->getUsername();
    $send = false; //TODO: Set to true to send emails, set emails to testing email while not in production

    //Send to regional director
    $director_key = 'remove_staff_profile_editor_cty_reg_director';
    $reg_director_email = 'eit_tcgerwig@iastate.edu';
    //$reg_director_email = \Drupal::currentUser()->getEmail();
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $reg_dir_result = $mailManager->mail($module, $director_key, $reg_director_email, $langcode, $params, NULL, $send);

    //Send to extweb
    $extweb_key = 'remove_staff_profile_editor_cty_extweb';
    $extweb_email = 'eit_tcgerwig@iastate.edu';
    //$extweb_email = 'extensionweb@iastate.edu';
    $langcode = 'en';
    $ext_result = $mailManager->mail($module, $extweb_key, $extweb_email, $langcode, $params, NULL, $send);

    if ($reg_dir_result['result'] !== true && $ext_result['result'] !== true) {
      drupal_set_message(t('There was a problem sending notification emails.'));
    } elseif ($reg_dir_result['result'] !== true) {
      drupal_set_message(t('There was a problem sending notification email to regional director.'));
    } elseif ($ext_result['result'] !== true) {
      drupal_set_message(t('There was a problem sending notification email to extensionweb.'));
    } else {
      drupal_set_message(t('Notification emails sent.'));
    }

    $this->logger('staff_profile_reed')->notice('Removed %title from county editor in %cty county', array('%title' => $this->entity->label(), '%cty' => $this->county->label()));
    $form_state->setRedirect('staff_profile_reed.regional_director_panel');
  }
}
