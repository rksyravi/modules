<?php

namespace Drupal\site_location;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Datetime\DateFormatterInterface;

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
   * Constructs a new DefaultService object.
   */
  public function __construct(TimeInterface $datetime_time, DateFormatterInterface $date_formatter) {
    $this->datetimeTime = $datetime_time;
    $this->dateFormatter = $date_formatter;
  }
  
  public function CurrentDateTime($timezone) {
    return ['#markup'=> $this->dateFormatter->format($this->datetimeTime->getCurrentTime(), 'custom', 'dS M Y - h:i A', $timezone)];
  }

}
