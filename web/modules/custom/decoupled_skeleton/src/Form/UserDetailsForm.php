<?php

namespace Drupal\decoupled_skeleton\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\decoupled_skeleton\Entity\Customers;

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

    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => t('First Name'),
      '#placeholder' => t('First name'),
      '#required' => TRUE,
    ];

    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => t('Last Name'),
      '#placeholder' => t('Last name'),
      '#required' => TRUE,
    ];

    $form['phone'] = [
      '#type' => 'textfield',
      '#title' => t('Phone number'),
      '#placeholder' => t('Phone number'),
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => t('Email'),
      '#placeholder' => t('Email'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
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
    $customer_entity = Customers::create([
      'type' => 'default',
      'name' => $values['first_name'] . ' ' . $values['last_name'],
      'field_customer_first_name' => $values['first_name'],
      'field_customer_last_name' => $values['last_name'],
      'field_customer_phone_number' => $values['phone'],
      'field_customer_email' => $values['email'],
    ]);
    $customer_entity->save();
    if($customer_entity) {
      \Drupal::messenger()->addMessage('Customer information saved successfully.');
    }
  }

}
