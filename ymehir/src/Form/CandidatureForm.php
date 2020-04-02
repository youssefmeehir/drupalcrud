<?php


namespace Drupal\ymehir\Form;


use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;

class CandidatureForm extends FormBase
{
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $conn = Database::getConnection();
    $record = array();
    if (isset($_GET['num'])) {
      $query = $conn->select('ymehir', 'c')
        ->condition('id', $_GET['num'])
        ->fields('c');
      $record = $query->execute()->fetchAssoc();
    }
    $form['nom'] = array(
      '#type' => 'textfield',
      '#title' => t('le nom de candidat:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['nom']) && $_GET['num']) ? $record['nom'] : '',
    );
    $form['prenom'] = array(
      '#type' => 'textfield',
      '#title' => t('le prénom de candidat:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['prenom']) && $_GET['num']) ? $record['prenom'] : '',
    );
    $form['civilite'] = array(
      '#type' => 'textfield',
      '#title' => t('la civilité  du candidat:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['civilite']) && $_GET['num']) ? $record['civilite'] : '',
    );
    $form['telephone'] = array(
      '#type' => 'tel',
      '#required' => TRUE,
      '#title' => t('numéro de telephone'),
      '#default_value' => (isset($record['telephone']) && $_GET['num']) ? $record['telephone'] : '',
    );
    $form['mail'] = array(
      '#type' => 'email',
      '#title' => t('Email:'),
      '#required' => TRUE,
      '#maxlength' => 255,
      '#default_value' => (isset($record['mail']) && $_GET['num']) ? $record['mail'] : '',
    );
    $form['dateNaissance'] = array(
      '#type' => 'date',
      '#title' => t('la date de naissance:'),
      '#required' => FALSE,
      '#default_value' => (isset($record['dateNaissance']) && $_GET['num']) ? $record['dateNaissance'] : '',
    );
    $form['etablissement'] = array(
      '#type' => 'textarea',
      '#title' => t('etablissement'),
      '#required' => FALSE,
      '#default_value' => (isset($record['etablissement']) && $_GET['num']) ? $record['etablissement'] : '',
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Sauvegarder'),

    );
    $url = Url::fromRoute('ymehir.condidature_display')->toString();
    $form['actions']['cancel'] = array(
      '#type' => 'markup',
      '#markup' => "<a class='btn btn-info' href='".$url."'>Annuler</a>"
    );

    $form['actions']['cancel']['#attributes']['class'][] = 'btn-danger';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);
    if (empty($form_state->getValue('nom'))) {
      $form_state->setErrorByName('nom', $this->t('Merci de saisir votre nom.'));
    }
    if (empty($form_state->getValue('prenom'))) {
      $form_state->setErrorByName('prenom', $this->t('Merci de saisir votre prènom.'));
    }
    if (empty($form_state->getValue('telephone'))) {
      $form_state->setErrorByName('telephone', $this->t('le telephone est obligatoire !'));
    }else {
      if(preg_match('/(05|06)(\d){8}/', trim($form_state->getValue('telehone')))) {
        $form_state->setErrorByName('mail', $this->t('Le numéro de telephone n\'accepte que les chiffres, doit contenir 10 chiffres et commence par 06 ou 05'));
      }
    }
    if (empty($form_state->getValue('mail'))) {
      $form_state->setErrorByName('mail', $this->t('Merci de saisir votre adresse mail.'));
    }
  }

  /**
   * @inheritDoc
   */
  public function getFormId()
  {
    return 'form_candidat';
  }

  /**
   * @inheritDoc
   * @throws \Exception
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $fields = $form_state->getValues();
    if (isset($_GET['num'])) {
      \Drupal::database()->update('ymehir')
        ->fields([
          'civilite' => $fields['civilite'],
          'nom' => $fields['nom'],
          'prenom' => $fields['prenom'],
          'telephone' => $fields['telephone'],
          'mail' => $fields['mail'],
          'dateNaissance' => $fields['dateNaissance'],
          'etablissement' => $fields['etablissement']
        ])->condition('id', $_GET['num'])->execute();
      \Drupal::messenger()->addMessage('Modification réussite');
    } else {
      \Drupal::database()->insert('ymehir')
        ->fields([
          'civilite',
          'nom',
          'prenom',
          'telephone',
          'mail',
          'dateNaissance',
          'etablissement'
        ])
        ->values([
          $fields['civilite'],
          $fields['nom'],
          $fields['prenom'],
          $fields['telephone'],
          $fields['mail'],
          $fields['dateNaissance'],
          $fields['etablissement'],
        ])
        ->execute();
      \Drupal::messenger()->addMessage('Ajout réussit');
    }
    $form_state->setRedirect('ymehir.condidature_display');
  }
}
