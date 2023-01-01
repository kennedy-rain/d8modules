<?php

namespace Drupal\plp_programs\Plugin\Block;

use Drupal\Core\Field\FieldFilteredMarkup;

use Drupal\Core\Block\BlockBase;
use Drupal\isueo_helpers\ISUEOHelpers;

/**
 * Provides a 'PLP Programs Search' Block.
 *
 * @Block(
 *   id = "plp_programs_search_results",
 *   admin_label = @Translation("PLP Programs Search Results"),
 *   category = @Translation("PLP"),
 * )
 */
class PLPProgramsSearchResults extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function build()
  {

    // Do NOT cache a page with this block on it
    //\Drupal::service('page_cache_kill_switch')->trigger();

    $results = '
        <div class="row">
          <div class="search-results">
            <div class="search-results-facets">
              <h3>Program Area</h3>
              <div id="program_area"></div>
              <h3>Audiences</h3>
              <div id="audiences"></div>
              <h3>Categories</h3>
              <div id="category_name"></div>
              <h3>Topics</h3>
              <div id="topic_names"></div>
            </div>
            <div class="search-results-snipets">
              <div id="search-results-bar"></div>
              <div class="isueo-searchall" id="isueo-searchall"><a href="https://www.extension.iastate.edu/search-results?search_broadness=wide">Search all of Extension</a></div>
              <div id="stats"></div>
              <div id="hits"></div>
            </div>
          </div>

        </div>


    <script src="https://cdn.jsdelivr.net/npm/instantsearch.js@4.44.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/typesense-instantsearch-adapter@2/dist/typesense-instantsearch-adapter.min.js"></script>
    ';

    //Add allowed tags for svg map
    $tags = FieldFilteredMarkup::allowedTags();
    array_push($tags, 'script', 'div', 'img', 'src', 'h3',);

    $block = [];
    $block['#allowed_tags'] = $tags;
    $block['#markup'] = $results;
    $block['#attached']['library'][] = 'plp_programs/plp_programs_search_results';
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
