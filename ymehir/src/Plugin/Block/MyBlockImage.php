<?php

namespace Drupal\ymehir\Plugin\Block;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * @Block(
 *    id = "my_block_image",
 *   admin_label = @Translation("My block Image"),
 *   )
 */
class MyBlockImage extends BlockBase
{

  /**
   * @inheritDoc
   */
  public function build() {
    return [
      '#markup' => '<img src="https://www.google.com/url?sa=i&source=images&cd=&ved=2ahUKEwiEwqayj7rmAhUE2uAKHUR_Bb4QjRx6BAgBEAQ&url=https%3A%2F%2Fwww.futura-sciences.com%2Ftech%2Fdossiers%2Ftechnologie-photo-numerique-capteur-image-773%2F&psig=AOvVaw1j4H2LD8lRlsRnciJYD5b7&ust=1576584041297360g">',
    ];
  }
}
