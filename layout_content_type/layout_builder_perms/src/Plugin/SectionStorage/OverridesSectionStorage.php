<?php

namespace Drupal\layout_builder_perms\Plugin\SectionStorage;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\layout_builder\Plugin\SectionStorage\OverridesSectionStorage as OverridesSectionStorageBase;

/**
 * Defines the 'overrides' section storage type.
 *
 * OverridesSectionStorage uses a negative weight because:
 * - It must be picked before
 *   \Drupal\layout_builder\Plugin\SectionStorage\DefaultsSectionStorage.
 * - The default weight is 0, so custom implementations will not take
 *   precedence unless otherwise specified.
 *
 * @SectionStorage(
 *   id = "overrides",
 *   weight = -20,
 *   handles_permission_check = TRUE,
 *   context_definitions = {
 *     "entity" = @ContextDefinition("entity", constraints = {
 *       "EntityHasField" = \Drupal\layout_builder\Plugin\SectionStorage\OverridesSectionStorage::FIELD_NAME,
 *     }),
 *     "view_mode" = @ContextDefinition("string"),
 *   }
 * )
 *
 * @internal
 *   Plugin classes are internal.
 */
class OverridesSectionStorage extends OverridesSectionStorageBase {

  /**
   * {@inheritdoc}
   */
  public function access($operation, AccountInterface $account = NULL, $return_as_object = FALSE) {
    $entity = $this->getEntity();
    if ($account === NULL) {
      $account = $this->currentUser;
    }

    $any_access = AccessResult::allowedIfHasPermission($account, 'access layout builder page');
    // The user can configure layout items of owned content.
    $own_content_access = AccessResult::allowedIfHasPermission($account, "configure own editable {$entity->bundle()} {$entity->getEntityTypeId()} layout overrides");
    $own_content_access = $own_content_access->andIf($entity->access('update', $account, TRUE));

    $result = $any_access->orIf($own_content_access);

    return $return_as_object ? $result : $result->isAllowed();
  }

}
