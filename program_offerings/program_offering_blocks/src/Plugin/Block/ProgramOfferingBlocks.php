<?php

namespace Drupal\program_offering_blocks\Plugin\Block;

use DateInterval;
use DateTime;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;


use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityViewBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\program_offering_blocks\Controller\Helpers;

/**
 * Provides a 'ProgramOfferingBlocks' block plugin.
 *
 * @Block(
 *   id = "program_offering_blocks",
 *   admin_label = @Translation("Program Offering blocks"),
 *   deriver = "Drupal\program_offering_blocks\Plugin\Derivative\ProgramOfferingBlocks"
 * )
 */


class ProgramOfferingBlocks extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    global $base_url;

    // Do NOT cache a page with this block on it
    \Drupal::service('page_cache_kill_switch')->trigger();

    // Initalize variables
    $results = '';
    $count = 0;
    $id = $this->getDerivativeID();
    $config = $this->getConfiguration();
    $module_config = \Drupal::config('program_offering_blocks.settings');

    // Show annoncement if there is one
    if (!empty($config['announcement_text'])) {
      $results .= PHP_EOL . '<div class="program_offering_blocks_announcement_text">' . $config['announcement_text'] . '</div>' . PHP_EOL;
    }

    // Get the maximum events to display
    $max_events = intval($config['max_events']);
    if ($max_events == 0) {
      $max_events = PHP_INT_MAX;
    }

    // Get the current node
    $node = \Drupal::routeMatch()->getParameter('node');

    // Check for a filter in the query string
    $querystring_filter = \Drupal::request()->query->get('filter');
    if (!empty($querystring_filter)) {
      $querystring_filter = urldecode($querystring_filter);
    }

    // Combine all search filters, into a $search_terms array()
    $string_of_search_terms = $this->build_search_string(!empty($node->field_ungerboeck_search_term->value) ? $node->field_ungerboeck_search_term->value : '', $config['title_search']);
    $string_of_search_terms = $this->build_search_string(!empty($node->field_program_offerings_filter->value) ? $node->field_program_offerings_filter->value : '', $string_of_search_terms);
    $string_of_search_terms = $this->build_search_string($string_of_search_terms, $querystring_filter);
    $search_terms_array = explode('|', $string_of_search_terms);

    // Get the events from the JSON feed
    $buffer = file_get_contents($module_config->get('url'));
    //$buffer = ""; //Helpers::read_ungerboeck_file();
    $json_events = json_decode($buffer, TRUE);
    //$json_events = array_reverse($json_events);
    //\Drupal::logger('program_offering_blocks')->info(sizeof($json_events));
    //$json_events = array();

    $results .= PHP_EOL . '<ul class="program_offering_blocks program_offering_blocks_' . $id . '">' . PHP_EOL;

    foreach ($json_events as $event) {
      $display_event = TRUE;
      if (!empty($config['program_area']) && $config['program_area'] != $event['PrimaryProgramUnit__c']) {
        $display_event = FALSE;
      }
      if ($config['only_planned_programs'] && empty($event['Planned_Program__c'])) {
        $display_event = FALSE;
      }
      foreach ($search_terms_array as $search_term) {
        //$search_term = trim($search_term);
        if (!empty($search_term) &&  !(strpos(strtolower($event['Name_Placeholder__c']), $search_term) !== FALSE)) {
          $display_event = FALSE;
        }
      }

      if ($display_event) {
        if ($count < $max_events) {
          $results .= '  <li>' . PHP_EOL;

          $results .= $this->format_title($event, $config);
//          $startDate = new DateTime($event['Start_Time_and_Date__c']);
$startDate = date($config['format_with_time'], strtotime($event['Start_Time_and_Date__c']));
//$startDate = date($config['format_with_time'], strtotime(str_replace('Z','',$event['Start_Time_and_Date__c'])));
          $results .= '    <div class="event_venue">' . $event['Event_Location_Site_Building__c'] . '</div>' . PHP_EOL;
          $results .= '    <div class="event_startdate">' . $startDate . '</div>' . PHP_EOL;

          //$results .= '    ' . $title . PHP_EOL;
          //$results .= '    <div class="event_venue">' . $event['ANCHORVENUE'] . '</div>' . PHP_EOL;

          //$results .= $event['Id'] . '<br/>' . PHP_EOL;
          //$results .= 'Registraion Link: ' . $event['Registration_Link__c'] . '<br/>' . PHP_EOL;
          //$results .= 'Planned Program Website: ' . $event['Planned_Program_Website__c'] . '<br/>' . PHP_EOL;
          //$results .= 'Program Offering Website: ' . $event['Program_Offering_Website__c'] . '<br/>' . PHP_EOL;
          //$results .= $event['Id'] . '<br/>' . PHP_EOL;
//          $results .= PHP_EOL . $event['Ungerboeck_Event_ID__c'] . PHP_EOL;
          $results .= '  </li>' . PHP_EOL;
        }
        $count++;
      }
    }

    $results .= '</ul>' . PHP_EOL;

    if (!empty($config['show_more_page']) && !empty($config['show_more_text']) && $count > $max_events) {
      $results .= '<a class="events_show_more" href="' . $base_url . '/' . $config['show_more_page'] . '?filter=' . urlencode($string_of_search_terms) . '">' . $config['show_more_text'] . '</a><br />';
    }

    return [
      '#markup' => $this->t($results),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account, $return_as_object = FALSE)
  {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state)
  {
    $config = $this->getConfiguration();

    $form['max_events'] = array(
      '#type' => 'textfield',
      '#title' => t('Maximum Number of Events to Display'),
      '#description' => t('Zero (0) means display all events'),
      '#size' => 15,
      '#default_value' => $config['max_events'],
    );

    $form['event_details_page'] = array(
      '#type' => 'checkbox',
      '#title' => t('Link to Details Page'),
      '#description' => t('When checked, every event title will link to a details page, otherwise, titles will link to registration pages where available'),
      '#default_value' => $config['event_details_page'],
    );

    $form['format_with_time'] = array(
      '#type' => 'textfield',
      '#title' => t('Date/Time Format'),
      '#description' => t('Format of the date, see <a href="http://php.net/manual/en/function.date.php">php date manual</a>'),
      '#default_value' => $config['format_with_time'],
    );

    $form['format_without_time'] = array(
      '#type' => 'textfield',
      '#title' => t('Date Only Format'),
      '#description' => t('Use this format when the time is 12:00 am (midnight)'),
      '#default_value' => $config['format_without_time'],
    );

    $form['title_search'] = array(
      '#type' => 'textfield',
      '#title' => t('Restrict Search by Title'),
      '#description' => t('Only show events with this search term in title, blank means show all events'),
      '#default_value' => $config['title_search'],
    );

    $form['show_more_page'] = array(
      '#type' => 'textfield',
      '#title' => t('Show More Events Page'),
      '#description' => t('Path to add to base URL where all events are listed'),
      '#default_value' => $config['show_more_page'],
    );
    $form['show_more_text'] = array(
      '#type' => 'textfield',
      '#title' => t('Text for Show More Events Link'),
      '#description' => t('Text that\'s used in the anchor tag'),
      '#default_value' => $config['show_more_text'],
    );

    $form['announcement_text'] = array(
      '#type' => 'textfield',
      '#title' => t('Announcement Text'),
      '#description' => t('Text to be placed above the events in this block. Used for special announcements, ie COVID-19 cancellations'),
      '#size' => 75,
      '#maxlength' => 300,
      '#default_value' => $config['announcement_text'],
    );

    $form['only_planned_programs'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show Only Planned Programs'),
      '#description' => t('When checked, only show events that are in the list of planned programs. Skip the other lists'),
      '#default_value' => $config['only_planned_programs'],
    );

    $form['program_area'] = array(
      '#type' => 'select',
      '#options' => [
        '' => $this->t('Include All'),
        '4-H & Youth Development' => $this->t('4-H & Youth Development'),
        'Agriculture & Natural Resources' => $this->t('Agriculture & Natural Resources'),
        'Community & Economic Development' => $this->t('Community & Economic Development'),
        'County Services' => $this->t('County Services'),
        'Human Sciences' => $this->t('Human Sciences'),
      ],
      '#title' => t('Program Area'),
      '#description' => t('If something is selected, then only show events for that program area'),
      //'#size' => 75,
      //'#maxlength' => 300,
      '#default_value' => $config['program_area'],
    );

    $form['placement'] = array(
      '#type' => 'textfield',
      '#title' => t('Placed on Page'),
      '#description' => t('Documentation: what page(s) the block is placed on'),
      '#size' => 75,
      '#maxlength' => 300,
      '#default_value' => $config['placement'],
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state)
  {
    $values = $form_state->getValues();

    $this->configuration['event_details_page'] = $values['event_details_page'];
    $this->configuration['max_events'] = $values['max_events'];
    $this->configuration['format_with_time'] = $values['format_with_time'];
    $this->configuration['format_without_time'] = $values['format_without_time'];
    $this->configuration['title_search'] = $values['title_search'];
    $this->configuration['show_more_page'] = $values['show_more_page'];
    $this->configuration['show_more_text'] = $values['show_more_text'];
    $this->configuration['announcement_text'] = $values['announcement_text'];
    $this->configuration['only_planned_programs'] = $values['only_planned_programs'];
    $this->configuration['program_area'] = $values['program_area'];
    $this->configuration['placement'] = $values['placement'];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration()
  {
    return array(
      'event_details_page' => TRUE,
      'max_events' => 0,
      'format_with_time' => 'M j, Y, g:i a',
      'format_without_time' => 'M j, Y',
      'title_search' => '',
      'show_more_page' => '',
      'show_more_text' => 'More',
      'announement_text' => '',
      'only_planned_programs' => FALSE,
      'program_area' => '',
      'placement' => '',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge()
  {
    return 0;
  }

  /**
   * Format Date
   */
  private function format_date_time($datetime)
  {
    $config = $this->getConfiguration();

    if (date('Gi', $datetime) == '0000') {
      $datetimestr = date($config['format_without_time'], $datetime);
    } else {
      $datetimestr = date($config['format_with_time'], $datetime);
    }

    return $datetimestr;
  }
  /**
   * Format the title and get it ready to output
   */
  private function format_title($event, $config)
  {
    $title = '<div class="event_title">';
    if ($config['event_details_page']) {
      $title .= '<a href="' . base_path() . 'event_details/' . $event['Id'] . '/' . $event['Name_Placeholder__c'] .'">' . $event['Name_Placeholder__c'] . '</a>';
    } else {
      $now = strtotime('today midnight');
      $regstartdate = !empty($event['Registration_Opens__c']) ? strtotime($event['Registration_Opens__c']) : $now;
      $regenddate = !empty($event['Registration_Deadline__c']) ? strtotime($event['Registration_Deadline__c']) : $now;
      $regenddate = date_add(new DateTime('@'.$regenddate), new DateInterval('P1D'))->getTimestamp();

      if (!empty($event['Registration_Link__c']) && ($now >= $regstartdate && $now <= $regenddate)) {
        $title .= '<a href="' . $event['Registration_Link__c'] . '">' . $event['Name_Placeholder__c'] . '</a>';
      } elseif (!empty($event['Planned_Program_Website__c'])) {
        $title .= '<a href="' . $event['Planned_Program_Website__c'] . '">' . $event['Name_Placeholder__c'] . '</a>';
      } else {
        $title .= $event['Name_Placeholder__c'];
      }
    }
    //$title .= '<br/>';
    //$title .= 'Now: ' . date($config['format_with_time'], $now);
    //$title .= '<br/>';
    //$title .= 'Open: ' . date($config['format_with_time'], $regstartdate) . ' - ' . $event['Registration_Opens__c'];
    //$title .= '<br/>';
    //$title .= 'Close: ' . date($config['format_with_time'], $regenddate) . ' - ' . $event['Registration_Deadline__c'];
    //$title .= '<br/>';
    $title .= '</div>';

    return $title;
  }

  /**
   * Combine two search strings into 1
   */
  private function build_search_string($str1, $str2)
  {
    $return_string = '';
    $str1 = trim(strtolower($str1));
    $str2 = trim(strtolower($str2));
    if (!empty($str1) && !empty($str2)) {
      $return_string = $str1 . '|' . $str2;
    } elseif (!empty($str1)) {
      $return_string = $str1;
    } else {
      $return_string = $str2;
    }

    return $return_string;
  }
}
