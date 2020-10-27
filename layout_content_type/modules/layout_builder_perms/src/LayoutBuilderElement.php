<?php

namespace Drupal\layout_builder_perms;

use Drupal\Component\Uuid\Uuid;
use Drupal\Core\Access\AccessResultInterface;

/**
 * Class LayoutBuilderElement overrides LayoutBuilder element.
 *
 * @package Drupal\layout_builder_perms
 */
class LayoutBuilderElement {

  /**
   * Remove elements which user doesn't have access.
   *
   * @param array $element
   *   The Layout Builder render element.
   *
   * @return array
   *   The modified Layout Builder render element.
   */
  public static function preRender(array $element) {
    foreach ($element['layout_builder'] as $key => $layout) {
      if (isset($layout['link'])) {
        if (static::elementAccess($layout['link']) === FALSE) {
          unset($element['layout_builder'][$key]);
        }
      }

      if (isset($layout['#type']) && $layout['#type'] == 'container') {
        foreach ($layout as $name => $item) {
          if (isset($item['link'])) {
            if (static::elementAccess($item['link']) === FALSE) {
              unset($element['layout_builder'][$key][$name]);
            }
          }
          elseif (isset($item['#url'])) {
            if (static::elementAccess($item) === FALSE) {
              unset($element['layout_builder'][$key][$name]);
            }
          }
        }
      }

      if (isset($layout['layout-builder__section'])) {
        foreach ($layout['layout-builder__section'] as $sid => $section) {
          if (!is_array($section)) {
            continue;
          }
          $section = array_filter($section, 'is_array');
          foreach ($section as $id => $content) {
            if (Uuid::isValid($id) && !\Drupal::currentUser()->hasPermission('reorder layout builder blocks')) {
              // Control access to block reordering functionality.
              $class = array_search('js-layout-builder-block', $section[$id]['#attributes']['class']);
              if (isset($section[$id]['#attributes']['class'][$class])) {
                unset($element['layout_builder'][$key]['layout-builder__section'][$sid][$id]['#attributes']['class'][$class]);
              }
            }
          }

          if (isset($section['layout_builder_add_block'])) {
            if (static::elementAccess($section['layout_builder_add_block']['link']) === FALSE) {
              unset($element['layout_builder'][$key]['layout-builder__section'][$sid]['layout_builder_add_block']);
            }
          }
        }
      }
    }

    if (!\Drupal::currentUser()->hasPermission('reorder layout builder blocks')) {
      $element['layout_builder']['#attached']['library'][] = 'layout_builder_perms/css.override';
    }

    return $element;
  }

  /**
   * Check if user has access to a specific route.
   *
   * @param array $element
   *   The Link element.
   *
   * @return bool
   *   TRUE user has access to given route, FALSE otherwise.
   */
  public static function elementAccess(array $element) {
    $access_manager = \Drupal::service('access_manager');
    $account = \Drupal::currentUser();

    $route_name = $element['#url']->getRouteName();
    $route_params = $element['#url']->getRouteParameters();

    // Check if user has access to a named route.
    $access = $access_manager->checkNamedRoute($route_name, $route_params, $account);

    return ($access instanceof AccessResultInterface) ? $access->isAllowed() : $access;
  }

}
