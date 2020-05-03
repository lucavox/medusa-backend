<?php

/**
 * @file
 * Contains customers.page.inc.
 *
 * Page callback for Customers entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Customers templates.
 *
 * Default template: customers.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_customers(array &$variables) {
  // Fetch Customers Entity Object.
  $customers = $variables['elements']['#customers'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
