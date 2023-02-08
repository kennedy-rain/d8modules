<?php
namespace Drupal\staff_profile_reed\Controller;

use \Drupal\Core\Controller\ControllerBase;
use \Drupal\user\Entity\User;
/**
 * Regional Director Panels
 */
class RegionalDirectorPanel extends ControllerBase {
  /**
   * Get tids of counties that regional director has listed as web editor for
   */
  private function getCountiesServed($netid) {
    $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['field_staff_profile_netid' => $netid]);
    if ($node = reset($nodes)) {
      return $node->field_staff_profile_cty_author->referencedEntities();
    } else {
      return [];
    }
  }


  public function panel() {
    $user = User::load(\Drupal::currentUser()->id());
    $counties = RegionalDirectorPanel::getCountiesServed($user->getAccountName());
    $result = [];
    foreach ($counties as $key => $county) {
      $result[$county->label()] = array(
        '#type' => 'fieldset',
        "#title" => $this->t($county->label())
      );
      $result[$county->label()]['view'] = [
        '#type' => 'view',
        '#name' => 'regional_director_county',
        '#display_id' => 'block_1',
        '#arguments' => [$county->id()],
        '#embed' => TRUE,
      ];
      $result[$county->label()]['add-form'] = \Drupal::formBuilder()->getForm('\Drupal\staff_profile_reed\Form\StaffProfileReedAddCtyEditorForm');
      $result[$county->label()]['add-form']['cty']['#value'] = $county->id();
      $result[$county->label()]['add-form']['cty']['#default_value'] = $county->id();
    }
    return $result;
  }

}
