<?php

namespace Drupal\staff_profile_reed\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * @ingroup staff_profile_reed
 */
class StaffProfileRemoveCtyAuthor extends ContentEntityConfirmFormBase {
  public function getQuestion() {
    return $this->t('Are you sure you want to remove %name from county?', array('%name' => $this->entity->label()));
  }

  public function getCancelUrl() {
    return new Url('staff_profile_reed.regional_director_panel');
  }

  public function getConfirmText() {
    return $this->t('Remove from County');
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $staff_profile = $this->getEntity();
    $this->logger('staff_profile_reed')->notice('Removed %title from county editor', array('%title' => $this->entity->label()));
    $form_state->setRedirect('staff_profile_reed.regional_director_panel');
  }
}
