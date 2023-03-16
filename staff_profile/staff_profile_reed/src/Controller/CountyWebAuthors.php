<?php
namespace Drupal\staff_profile_reed\Controller;

use \Drupal\Core\Controller\ControllerBase;
use \Drupal\user\Entity\User;
/**
 * Regional Director Panels
 */
class CountyWebAuthors extends ControllerBase {

  public function panel() {
    $counties = \Drupal::service('staff_profile_reed.helper_functions')->getCountiesServed();

    $result = [];
    foreach ($counties as $key => $county) {
      $result[$county->label()] = array(
        '#type' => 'fieldset',
        "#title" => $this->t($county->label())
      );
      $result[$county->label()]['view'] = [
        '#type' => 'view',
        '#name' => 'regional_director_county',
        '#display_id' => 'county_web_authors',
        '#arguments' => [$county->id()],
        '#embed' => TRUE,
      ];
      $result[$county->label()]['add-form'] = \Drupal::formBuilder()->getForm('\Drupal\staff_profile_reed\Form\CountyWebAuthorsAddForm');
      $result[$county->label()]['add-form']['cty']['#value'] = $county->id();
      $result[$county->label()]['add-form']['cty']['#default_value'] = $county->id();
    }
    return $result;
  }

}
