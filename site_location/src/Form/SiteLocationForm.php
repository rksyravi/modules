<?php

namespace Drupal\site_location\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SiteLocationForm.
 */
class SiteLocationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'site_location.sitelocation',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'site_location_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('site_location.sitelocation');
    
    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('country'),
      '#required' => true,
    ];
    
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('city'),
      '#required' => true,
    ];
    
    $form['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Timezone'),
      '#options' => getTimeZone(),
      '#size' => 1,
      '#default_value' => $config->get('timezone'),
      '#required' => true,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('site_location.sitelocation')
      ->set('country', $form_state->getValue('country'))
      ->set('city', $form_state->getValue('city'))
      ->set('timezone', $form_state->getValue('timezone'))
      ->save();
  }

}
