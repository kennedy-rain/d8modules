<?php
namespace Drupal\staff_profile_reed\Controller;

use \Drupal\Core\Controller\ControllerBase;
use \Drupal\user\Entity\User;
/**
 * Regional Director Panels
 */
class CountyStaff extends ControllerBase {

  public function panel() {
    $counties = \Drupal::service('staff_profile_reed.helper_functions')->getCountiesServed();

    $result = [];

    $result['#markup'] = '<p>These are the staff members housed in each county.<br/>
      We should have info here about how to remove staff, maybe a link to a MyExtension page?<br/>
      What about a link to county web authors?</p>';

    foreach ($counties as $key => $county) {
      $result[$county->label()] = array(
        '#type' => 'fieldset',
        "#title" => $this->t($county->label())
      );
      $result[$county->label()]['description'] = [
        '#markup' => '<p>Could also put links or info about ' . $this->t($county->label()) . ' County here</p>',
      ];
      $result[$county->label()]['view'] = [
        '#type' => 'view',
        '#name' => 'regional_director_county',
        '#display_id' => 'county_staff',
        '#arguments' => [$county->id()],
        '#embed' => TRUE,
      ];
    }
    return $result;
  }

}
