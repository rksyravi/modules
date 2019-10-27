<?php

namespace Drupal\site_location;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\PageCache\ResponsePolicyInterface;

/**
 * Class SiteLocationService.
 */
class SiteLocationService implements SiteLocationInterface {

  /**
   * Drupal\Component\Datetime\TimeInterface definition.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $datetimeTime;

  /**
   * Drupal\Core\Datetime\DateFormatterInterface definition.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;
  
  /**
   * Drupal\Core\PageCache\ResponsePolicyInterface definition.
   *
   * @var \Drupal\Core\PageCache\ResponsePolicyInterface
   */
  protected $pageCacheKillSwitch;

  /**
   * Constructs a new DefaultService object.
   */
  public function __construct(TimeInterface $datetime_time, DateFormatterInterface $date_formatter, ResponsePolicyInterface $page_cache_kill_switch) {
    $this->datetimeTime = $datetime_time;
    $this->dateFormatter = $date_formatter;
    $this->pageCacheKillSwitch = $page_cache_kill_switch;
  }
  
  public function CurrentDateTime($timezone) {
    if (empty(\Drupal::currentUser()->id())) {
      $this->pageCacheKillSwitch->trigger();
    }
    return ['#markup'=> $this->dateFormatter->format($this->datetimeTime->getCurrentTime(), 'custom', 'dS M Y - h:i A', $timezone)];
  }

}
