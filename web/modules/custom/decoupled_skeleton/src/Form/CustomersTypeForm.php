<?php

namespace Drupal\decoupled_skeleton\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CustomersTypeForm.
 */
class CustomersTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $customers_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $customers_type->label(),
      '#description' => $this->t("Label for the Customers type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $customers_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\decoupled_skeleton\Entity\CustomersType::load',
      ],
      '#disabled' => !$customers_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $customers_type = $this->entity;
    $status = $customers_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Customers type.', [
          '%label' => $customers_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Customers type.', [
          '%label' => $customers_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($customers_type->toUrl('collection'));
  }

}
