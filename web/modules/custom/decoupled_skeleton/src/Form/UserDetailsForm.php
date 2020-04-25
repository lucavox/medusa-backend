<?php

namespace Drupal\decoupled_skeleton\Form;

use Drupal\user\Entity\User;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\taxonomy\Entity\Term;
use Drupal\node\Entity\Node;
use Drupal\bat_event\Entity\Event;
use Drupal\bat_booking\Entity\Booking;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\commerce_product\Entity\Product;
use Drupal\events_commerce\BookingService;

/**
 * This is the booking form for Customers / Event Coordinators.
 */
class UserDetailsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_details_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $group = NULL) {

    $form['one']['first_name'] = [
      '#type' => 'textfield',
      '#title' => t('First Name'),
      '#placeholder' => t('First name'),
      '#required' => TRUE,
    ];

    $form['one']['last_name'] = [
      '#type' => 'textfield',
      '#title' => t('Last Name'),
      '#placeholder' => t('Last name'),
      '#required' => TRUE,
    ];

    $form['one']['phone'] = [
      '#type' => 'textfield',
      '#title' => t('Phone number'),
      '#placeholder' => t('Phone number'),
      '#required' => TRUE,
    ];

    $form['one']['email'] = [
      '#type' => 'email',
      '#title' => t('Email'),
      '#placeholder' => t('Email'),
      '#required' => TRUE,
    ];

    $form['one']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Save')
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

  }


}
