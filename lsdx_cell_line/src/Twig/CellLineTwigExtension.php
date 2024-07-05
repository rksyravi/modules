<?php

namespace Drupal\lsdx_cell_line\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class for Json to array.
 */
class CellLineTwigExtension extends AbstractExtension {

  /**
   * Get Filters function.
   */
  public function getFilters(): array {
    return [
      new TwigFilter('lsdx_json_decode', [$this, 'jsonDecode']),
    ];
  }

  /**
   * The actual implementation of the filter.
   */
  public function jsonDecode($context): array {
    $context = json_decode($context, TRUE);
    if (json_last_error() === JSON_ERROR_NONE) {
      return $context;
    }
    return [];
  }

}
