<?php

namespace Drupal\plp_programs\Plugin\Block;
use Drupal\Core\Field\FieldFilteredMarkup;

use Drupal\Core\Block\BlockBase;
use Drupal\isueo_helpers\ISUEOHelpers;

/**
 * Provides a 'PLP Programs Search' Block.
 *
 * @Block(
 *   id = "plp_programs_search",
 *   admin_label = @Translation("PLP Programs Search"),
 *   category = @Translation("PLP"),
 * )
 */
class PLPProgramsSearch extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function build()
  {

    // Do NOT cache a page with this block on it
    //\Drupal::service('page_cache_kill_switch')->trigger();

    $results = '
    <div class="container">
        <div class="search-panel">
            <div class="search-panel__results">
                <div id="searchbox"></div>
                <div id="hits"></div>
            </div>
        </div>

        <div id="pagination"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/instantsearch.js@4.44.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/typesense-instantsearch-adapter@2/dist/typesense-instantsearch-adapter.min.js"></script>
    ';

    //Add allowed tags for svg map
    $tags = FieldFilteredMarkup::allowedTags();
    array_push($tags, 'script', 'div', 'img', 'src');

    $block = [];
    $block['#allowed_tags'] = $tags;
    $block['#markup'] = $results;
    $block['#attached']['library'][] = 'plp_programs/plp_programs_search';
    return $block;
  }

  /**
   * @return int
   */
  public function getCacheMaxAge()
  {
    return 0;
  }
}
