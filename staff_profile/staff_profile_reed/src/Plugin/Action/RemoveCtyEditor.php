<?php
/**
 * @file
 * Contains \Drupal\staff_profile_reed\Plugin\Action\RemoveCtyEditor
 */
namespace Drupal\staff_profile_reed\Plugin\Action;
use \Drupal\Core\Action\ActionBase;
use \Drupal\Core\Access\AccessResult;
/**
 * Removes specific county from staff_profile's field_staff_profile_cty_author
 *
 * @Action(
 *  id = "staff_profile_remove_cty_editor",
 *  label = @Translation("Remove County Editor"),
 *  type = "node"
 * )
 */
class RemoveCtyEditor extends ActionBase {
  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    \Drupal::logger('staff_profile_reed')->notice("Removing " . $entity->getTitle());
    $entity->save();
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, $account = NULL, $return_as_object = FALSE) {
    $user = \Drupal::currentUser()->getRoles();
    if (in_array('regional_director', $user)) {
      if ($return_as_object) {
        return AccessResult::isAllowed();
      } else {
        return TRUE;
      }
    } else {
      if ($return_as_object) {
        return AccessResult::isForbidden();
      } else {
        return FALSE;
      }
    }
  }

}
