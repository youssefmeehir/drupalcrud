<?php

namespace Drupal\ymehir\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class Controller extends ControllerBase
{
  public function content()
  {
    $build = [
      '#markup' => $this->t('Hello world'),
    ];
    return $build;
  }


  public function display()
  {
    $header_table = array(
      'id' => t('Id'),
      'nom' => t('Nom'),
      'prenom' => t('Prénom'),
      'civilite' => t('Civilite'),
      'telephone' => t('telephone'),
      'mail' => t('Email'),
      'dateNaissance' => t('DateNaissance'),
      'etablissement' => t('Etablissement'),
      'action_e' => t('Edit'),
      'action_d' => t('Delete'),
    );
    $query = \Drupal::database()->select('ymehir','c');
    $query->fields('c', ['id','nom','prenom','civilite','telephone','mail','dateNaissance','etablissement']);
    $results = $query->execute()->fetchAll();

    $rows = [];
    foreach ($results as $res){
      $delete = Url::fromUserInput('/ymehir/candidature/delete/'.$res->id);
      $edit = Url::fromUserInput('/ymehir/candidature?num='.$res->id);
      $rows[] = [
        'id' => $res->id,
        'nom' => $res->nom,
        'prenom' => $res->prenom,
        'civilite' => $res->civilite,
        'telephone' => $res->telephone,
        'mail' => $res->mail,
        'dateNaissance' => $res->dateNaissance,
        'etablissement' => $res->etablissement,
        Link::fromTextAndUrl('Delete', $delete),
        Link::fromTextAndUrl('Edit', $edit),
      ];
    }

    $form['table'] = [
      '#type' => 'table',
      '#header' => $header_table,
      '#rows' => $rows,
      '#empty' => t('aucune candidature trouvée')
    ];
    return $form;
  }

}
