<?php

namespace Drupal\layout_builder_perms;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Define a service provider for Layout Builder access.
 */
class LayoutBuilderPermsServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    $definition = $container->getDefinition('access_check.entity.layout_builder_access');
    $definition->setClass('Drupal\layout_builder_perms\Access\AdvancedAccessCheck');
  }

}
