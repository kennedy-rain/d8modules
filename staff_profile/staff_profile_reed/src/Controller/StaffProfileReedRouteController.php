<?php
namespace Drupal\staff_profile_reed\Controller;

use Drupal\Core\Entity\Controller\EntityController;
use Drupal\Core\Entity\EntityHandlerInterface;
use Drupal\Core\Entity\EntityTypeInterface;

//https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Entity%21Routing%21DefaultHtmlRouteProvider.php/8.2.x
class StaffProfileReedRouteController extends DefaultHtmlRouteProvider {
  public function getRoutes(EntityTypeInterface $entity_type) {
    $collection = parent::getRoutes($entity_type);
    if ($add_cty_author = $this->getAddCtyAuthorRoute($entity_type)) {
      $collection->add("entity.{$entity_type_id}.add_cty_author");
    }
    if ($remove_cty_author = $this->getRemoveCtyAuthorRoute($entity_type)) {
      $collection->add("entity.{$entity_type_id}.remove_cty_author");
    }

    return $collection;
  }

  protected function getAddCtyAuthorRoute(EntityTypeInterface $entity_type) {
    if ($entity_type->bundle() == "staff_profile") {
      $route = \Drupal::service('router.route_provider')->getRouteByName('entity.staff_profile_reed.add_cty_author_form');
    }
  }

  protected function getRemoveCtyAuthorRoute(EntityTypeInterface $entity_type) {
    if ($entity_type->bundle() == "staff_profile") {
      $route = \Drupal::service('router.route_provider')->getRouteByName('entity.staff_profile_reed.remove_cty_author_form');
    }
  }
}
