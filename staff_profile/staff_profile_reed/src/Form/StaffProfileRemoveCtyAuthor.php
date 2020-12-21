<?php

namespace Drupal\staff_profile_reed\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;

/**
 * @ingroup staff_profile_reed
 */
class StaffProfileRemoveCtyAuthor extends ContentEntityConfirmFormBase {
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

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $staff_profile = $this->getEntity();
    $ctys = $staff_profile->get('field_staff_profile_cty_author')->getValue();
    $key = array_search($this->county->id(), array_column($ctys, 'target_id'));
    $staff_profile->get('field_staff_profile_cty_author')->removeItem($key);
    $staff_profile->save();

    $this->logger('staff_profile_reed')->notice('Removed %title from county editor in %cty county', array('%title' => $this->entity->label(), '%cty' => $this->county->label()));
    $form_state->setRedirect('staff_profile_reed.regional_director_panel');
  }
}
