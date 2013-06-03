<?php

require_once 'bicdnprov.civix.php';

// custom Group Name
define('CUSTOM_GROUP_NAME', 'canadian_state_province');
define('CANADA_COUNTRY_ID', 1039);

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
  $entities[] = array(
    'module' => 'biz.jmaconsulting.bicdnprov',
    'name' => 'bicdnprov',
    'entity' => 'CustomGroup',
    'params' => array(
      'title' => 'Canadian State/Provice',
      'name' => 'canadian_state_province',
      'extends' => array( 
        '0' => 'Address',
      ),
      'weight' => 4,
      'collapse_display' => 0,
      'style' => 'Inline',
      'is_active' => 1,
      'api.customField.create' => array(
        'name' => 'state_province',
        'label' => 'State/Province',
        'html_type' => 'Select',
        'data_type' => 'String',
        'is_searchable' => 1,
        'option_values' => array(
          0 => array(
            'label' => 'state',
            'value' => 'id',
            'is_active' => 1,
            'weight' => 1,
          )
        ),
        'is_active' => 1,
      ),
      'version' => 3,
    ),
  );
  return _bicdnprov_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_buildForm
 *
 * remove state/province field 
 *
 */
function bicdnprov_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Contact_Form_Contact' && $form->elementExists('address[1][state_province_id]')) {
    $form->removeElement('address[1][state_province_id]');
    $form->removeElement('address[1][state_province_id]');
  }
}

/**
 * Implementation of hook_civicrm_customFieldOptions
 *
 * alter options to include all states with multilingual
 *
 */
function bicdnprov_civicrm_customFieldOptions($fieldID, &$options) {
  
  $groupName = CRM_Core_BAO_CustomField::getNameFromID(array($fieldID));
  if ($groupName[$fieldID]['group_name'] == CUSTOM_GROUP_NAME) {

    $options = $states = array();
    $query = "
SELECT civicrm_state_province.{$field} name, civicrm_state_province.id id
  FROM civicrm_state_province
WHERE country_id = %1
ORDER BY name";
    $params = array(
      1 => array(
        CANADA_COUNTRY_ID,
        'Integer',
      ),
    );

    $dao = CRM_Core_DAO::executeQuery($query, $params);

    while ($dao->fetch()) {
      $states[$dao->id] = $dao->name;
    }

    $domain = new CRM_Core_DAO_Domain;
    $domain->find(TRUE);

    if ($domain->locales) {  
      global $tsLocale;
      $tempLocale = $tsLocale;
      $i18n = CRM_Core_I18n::singleton();
      $config = CRM_Core_Config::singleton();
      $locales = explode(CRM_Core_DAO::VALUE_SEPARATOR, $domain->locales);
      foreach ($locales as $locale) {
        $config->lcMessages = $tsLocale = $locale;
        $results = $states;
        $i18n->localizeArray($results, array(
          'context' => 'province',
        ));
        foreach ($results as $value) {
          $options[$value] = $value;
        }
      }
      $config->lcMessages = $tsLocale = $tempLocale;
    }
    else {
      foreach ($states as $value) {
        $options[$value] = $value;
      }
    }
  }
}