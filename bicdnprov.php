<?php

require_once 'bicdnprov.civix.php';

/**
 * Implementation of hook_civicrm_config
 */
function bicdnprov_civicrm_config(&$config) {
  _bicdnprov_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function bicdnprov_civicrm_xmlMenu(&$files) {
  _bicdnprov_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 */
function bicdnprov_civicrm_install() {
  return _bicdnprov_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function bicdnprov_civicrm_uninstall() {
  return _bicdnprov_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function bicdnprov_civicrm_enable() {
  return _bicdnprov_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function bicdnprov_civicrm_disable() {
  return _bicdnprov_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 */
function bicdnprov_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _bicdnprov_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function bicdnprov_civicrm_managed(&$entities) {
  return _bicdnprov_civix_civicrm_managed($entities);
}

function bicdnprov_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Contact_Form_Contact' && $form->elementExists('address[1][state_province_id]')) {
    $form->removeElement('address[1][state_province_id]');
    $form->removeElement('address[1][state_province_id]');
  }
}