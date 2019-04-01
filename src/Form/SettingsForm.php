<?php

namespace Drupal\az_blob_fs\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SettingsForm.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'az_blob_fs.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('az_blob_fs.settings');
    dpm($config->get('account_key'));
    $form['account_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Account Name'),
      '#maxlength' => 255,
      '#size' => 64,
      '#default_value' => $config->get('account_name'),
    ];
    $form['account_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Account Key'),
      '#description' => $this->t('The Account Key is hidden in this field for security reason; If you need to change it, just add it again.'),
      '#maxlength' => 255,
      '#size' => 64,
    ];
    $form['azure_blob_container_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Azure Blob Container name'),
      '#description' => $this->t('Create a blob container on from your storage account with public permissions for the container.'),
      '#maxlength' => 255,
      '#size' => 64,
      '#default_value' => $config->get('azure_blob_container_name'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $az_blob_fs_settings = $this->config('az_blob_fs.settings');
    $account_key = $form_state->getValue('account_key');
    $az_blob_fs_settings
      ->set('account_name', $form_state->getValue('account_name'))
      ->set('azure_blob_container_name', $form_state->getValue('azure_blob_container_name'));

    if ($account_key != '') {
      $az_blob_fs_settings
        ->set('account_key', $form_state->getValue('account_key'));
    }

    $az_blob_fs_settings->save();
  }

}
