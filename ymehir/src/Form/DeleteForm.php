<?php
namespace Drupal\ymehir\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class DeleteForm extends ConfirmFormBase
{
  public $cid;
  /**
   * @inheritDoc
   */
  public function getQuestion()
  {
    $this->cid = \Drupal::routeMatch()->getParameter('id');
    return t('Do you want to delete %cid?', array('%cid' => $this->cid));
  }
  /**
   * @inheritDoc
   */
  public function getCancelUrl()
  {
    return new Url('ymehir.condidature_display');
  }
  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return t('Cancel');
  }
  /**
   * @inheritDoc
   */
  public function getFormId()
  {
    return 'delete_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->cid = $id;
    return parent::buildForm($form, $form_state);
  }
  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $query = \Drupal::database();
    $query->delete('ymehir')
      ->condition('id',$this->cid)
      ->execute();
    \Drupal::messenger()->addMessage('la candidature est suppriméé avec succssé');
    $form_state->setRedirect('ymehir.condidature_display');
  }
}



