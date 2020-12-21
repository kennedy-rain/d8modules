<?php

namespace Drupal\layout_builder_perms\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Cache\RefinableCacheableDependencyInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\layout_builder\Access\LayoutBuilderAccessCheck;
use Drupal\layout_builder\SectionStorageInterface;
use Symfony\Component\Routing\Route;

/**
 * Override Layout Builder access check.
 *
 * @ingroup layout_builder_access
 *
 * @internal
 *   Tagged services are internal.
 */
class AdvancedAccessCheck extends LayoutBuilderAccessCheck {

  /**
   * Checks routing access to the layout.
   *
   * @param \Drupal\layout_builder\SectionStorageInterface $section_storage
   *   The section storage.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The current user.
   * @param \Symfony\Component\Routing\Route $route
   *   The route to check against.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(SectionStorageInterface $section_storage, AccountInterface $account, Route $route) {
    $operation = $route->getRequirement('_layout_builder_access');
    $access = $section_storage->access($operation, $account, TRUE);

    switch ($operation) {
      case 'block_add':
        $access = AccessResult::allowedIfHasPermission($account, 'create layout builder blocks');
        break;

      case 'block_config':
        $access = AccessResult::allowedIfHasPermission($account, 'config layout builder blocks');
        break;

      case 'block_remove':
        $access = AccessResult::allowedIfHasPermission($account, 'remove layout builder blocks');
        break;

      case 'block_reorder':
        $access = AccessResult::allowedIfHasPermission($account, 'reorder layout builder blocks');
        break;

      case 'section_add':
        $access = AccessResult::allowedIfHasPermission($account, 'create layout builder sections');
        break;

      case 'section_edit':
        $access = AccessResult::allowedIfHasPermission($account, 'edit layout builder sections');
        break;

      case 'section_remove':
        $access = AccessResult::allowedIfHasPermission($account, 'remove layout builder sections');
        break;
    }

    // Check for the global permission unless the section storage checks
    // permissions itself.
    if (!$section_storage->getPluginDefinition()->get('handles_permission_check')) {
      $access = $access->andIf(AccessResult::allowedIfHasPermission($account, 'configure any layout'));
    }

    if ($access instanceof RefinableCacheableDependencyInterface) {
      $access->addCacheableDependency($section_storage);
    }

    return $access;
  }

}
