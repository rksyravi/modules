<?php

namespace Drupal\lsdx_cell_line\Controller;

use Drupal\Component\Utility\Tags;
use Drupal\Component\Utility\Xss;
use Drupal\config_pages\Entity\ConfigPages;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controls the cell-line searches.
 */
class CellLineController extends ControllerBase {

  /**
   * The current active database's master connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The current active database's master connection.
   *
   * @var \Drupal\Core\Utility\Token
   */
  protected $token;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->database = $container->get('database');
    $instance->token = $container->get('token');
    return $instance;
  }

  /**
   * Search product based on the query.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The current request.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   An array of label and value.
   */
  public function searchProduct(Request $request) {
    $msg = $this->token->replacePlain('[config_page:cell_line_terms_conditions:field_error_messages]', [], ['clear' => TRUE]);
    $error_msg = json_decode($msg, TRUE) ?: ['autocomplete' => 'No results found. Try a different product number.'];
    /** @var \Drupal\config_pages\Entity\ConfigPages $config_pages */
    $config_pages = $this->entityTypeManager()->getStorage('config_pages')->load('cell_line_global_settings');
    if ($config_pages instanceof ConfigPages && !$config_pages->get('field_brand_filter')->isEmpty()) {
      $brands = array_column($config_pages->get('field_brand_filter')->getValue(), 'target_id');
    }

    $results = [];
    $empty[] = [
      'value' => ' ',
      'label' => '<span class="cell-line-autocomplete-none">' . $error_msg['autocomplete'] . '</span>',
    ];

    $input = $request->query->get('q');
    // Get the typed string from the URL, if it exists.
    if (!$input) {
      return new JsonResponse($empty);
    }
    $input = Xss::filter($input);

    $query = $this->entityTypeManager()->getStorage('node')->getQuery()
      ->accessCheck(TRUE)
      ->condition('type', 'product_variation');
    if (!empty($brands)) {
      $query->condition('field_brand', $brands, 'IN');
    }
    $or = $query->orConditionGroup();
    $or->condition('title', $input, 'CONTAINS');
    $or->condition('field_part_number', $input, 'CONTAINS');
    $select = $query->condition($or)
      ->range(0, 20)
      ->execute();

    if (!empty($select)) {
      $entities = $this->entityTypeManager()->getStorage('node')->loadMultiple($select);
      $langcode = $this->languageManager()->getCurrentLanguage()->getId();
      /** @var \Drupal\node\NodeInterface $entity */
      foreach ($entities as $entity) {
        if ($entity->hasTranslation($langcode)) {
          $entity = $entity->getTranslation($langcode);
        }
        
        $markup = $entity->get('field_part_number')->getValue()[0]['value'] . ' | ' . $entity->label();
        $value = $entity->label() . ' (' . $entity->id() . ')';
        $results[] = [
          'value' => $value,
          'label' => $markup,
        ];
      }
    }

    return new JsonResponse($results ?: $empty);
  }

}
