<?php

namespace Drupal\layout_builder_perms;

use Drupal\layout_builder\LayoutBuilderOverridesPermissions;

/**
 * Provides dynamic permissions for Layout Builder overrides.
 *
 * @see \Drupal\layout_builder\Plugin\SectionStorage\OverridesSectionStorage::access()
 *
 * @internal
 *   Dynamic permission callbacks are internal.
 */
class LayoutBuilderPermissions extends LayoutBuilderOverridesPermissions {

  /**
   * {@inheritdoc}
   */
  public function permissions() {
    $permissions = [];

    /** @var \Drupal\layout_builder\Entity\LayoutEntityDisplayInterface[] $entity_displays */
    $entity_displays = $this->entityTypeManager->getStorage('entity_view_display')->loadByProperties(['third_party_settings.layout_builder.allow_custom' => TRUE]);
    foreach ($entity_displays as $entity_display) {
      $entity_type_id = $entity_display->getTargetEntityTypeId();
      $entity_type = $this->entityTypeManager->getDefinition($entity_type_id);
      $bundle = $entity_display->getTargetBundle();
      $args = [
        '%entity_type' => $entity_type->getCollectionLabel(),
        '@entity_type_singular' => $entity_type->getSingularLabel(),
        '@entity_type_plural' => $entity_type->getPluralLabel(),
        '%bundle' => $this->bundleInfo->getBundleInfo($entity_type_id)[$bundle]['label'],
      ];
      if ($entity_type->hasKey('bundle')) {
        $permissions["configure own editable $bundle $entity_type_id layout overrides"] = [
          'title' => $this->t('%entity_type - %bundle: Configure layout overrides for @entity_type_plural that user owns', $args),
        ];
      }
    }

    return $permissions;
  }

}
