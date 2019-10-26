<?php

namespace Drupal\site_location;

/**
 * Provides an interface defining a current date and time custom.
 */
interface SiteLocationInterface {
  
  public function CurrentDateTime($timezone);
}

