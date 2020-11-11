<?php
/**
* @file
*/
namespace Drupal\cse_selector\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Provides a "CSESearch" Block.
 *
 * @Block(
 *  id = "cse_search_block",
 *  admin_label = @Translation("CSE search block"),
 *  category = @Translation("Search")
 * )
 */
class CSESearchBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $form['search'] = [
      '#type' => 'container',
      '#attributes' => [
        'id' => 'isu-search_collapse',
        'class' => 'isu-search_collapse',
      ]
    ];
    $form['search']['search-block-form'] = [
      '#type' => 'container',
      '#attributes' => [
        'id' => 'block-iastate-theme-search',
        'class' => ['search-block-form','isu-search']
      ],
    ];
    $form['search']['search-block-form']['search'] = \Drupal::formBuilder()->getForm('Drupal\cse_selector\Form\CSESearchForm');
    $form['search']['search-block-form']['search']['form_id']['#access'] = FALSE;
    $form['search']['search-block-form']['search']['form_build_id']['#access'] = FALSE;
    $form['search']['search-block-form']['search']['form_token']['#access'] = FALSE;
    $cse_url_text = \Drupal::config('cse_selector.settings')->get('cse_selector_url_text');
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account, $return_as_object = FALSE) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }
  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }
}
