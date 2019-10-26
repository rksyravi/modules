<?php

namespace Drupal\site_location\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\site_location\SiteLocationInterface;

/**
 * Provides a 'SiteLocationBlock' block.
 *
 * @Block(
 *  id = "site_location_block",
 *  admin_label = @Translation("Site location"),
 * )
 */
class SiteLocationBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\site_location\SiteLocationInterface definition.
   *
   * @var \Drupal\site_location\SiteLocationInterface
   */
  protected $siteLocation;
  
  /**
   * Constructs a new DefaultBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\site_location\SiteLocationInterface
   *   The SiteLocationInterface definition.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, SiteLocationInterface $siteLocation) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->siteLocation = $siteLocation;
  }
  
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('site_location.current_time')
    );
  }
  
  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = \Drupal::config('site_location.sitelocation');
    $datetime = !empty($config->get('timezone')) ? ['#lazy_builder' => ['site_location.current_time:CurrentDateTime', [$config->get('timezone')]], '#create_placeholder' => TRUE] : '';
    $build = [
      '#theme'=> 'site_location_block',
      '#content'=> [
        'country'=> $config->get('country'),
        'city'=> $config->get('city'),
        'timezone'=> $config->get('timezone'),
        'current_datetime'=> $datetime,
      ],
      '#cache' => [
        'tags' => ['config:site_location.sitelocation'],
      ],
    ];

    return $build;
  }

}
