<?php

namespace Drupal\aseboston_misc\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Render\Markup;
use Drupal\Component\Utility\Html;

/**
 * @file
 * Contains \Drupal\aseboston_misc\Plugin\Field\FieldFormatter\EmailFormatter.
 */

/**
 * Plugin implementation of the 'email_formatter' formatter.
 *
 * @FieldFormatter (
 *   id = "email_formatter",
 *   label = @Translation("E-mail formatter (with options)"),
 *   field_types = {
 *     "email"
 *   }
 * )
 */
class EmailFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form['mailto'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Render the e-mail address as a mailto: link'),
      '#description' => $this->t('Check to render the e-mail address as a mailto: link'),
      '#default_value' => $this->getSetting('mailto'),
    ];

    $form['truncate'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of characters to truncate the e-mail address down to, including any trailing ellipsis (&hellip;)'),
      '#description' => $this->t('Blank/empty means no truncation'),
      '#size' => 5,
      '#maxlength' => 12,
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#default_value' => $this->getSetting('truncate'),
    ];

    $form['text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom text to preceed the address'),
      '#description' => $this->t('Add a trailing space character if required'),
      '#default_value' => $this->getSetting('text'),
    ];

    $form['HTML'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom HTML to preceed the address'),
      '#description' => $this->t('Add a trailing space character if required'),
      '#default_value' => $this->getSetting('HTML'),
    ];

    $form['icon'] = [
      '#type' => 'select',
      '#title' => $this->t('Font Awesome icon to preceed the address'),
      '#description' => $this->t('Compare the icons on the') . ' ' . Link::fromTextAndUrl(
        $this->t('the Font Awesome site'),
        Url::fromUri('https://fontawesome.com/icons?d=gallery&q=email',
          [
            'attributes' => [
              'title' => $this->t('the Font Awesome site'),
              'target' => '_blank',
            ],
          ]
        ))->toString(),
      '#default_value' => $this->getSetting('icon'),
      '#options' => [
        'none' => $this->t('(none)'),
        'envelope' => $this->t('Envelope'),
        'envelope-square' => $this->t('Envelope square'),
        'envelope-open' => $this->t('Envelope open'),
        'envelope-open-text' => $this->t('Envelope open text'),
        'paper-plane' => $this->t('Paper plane'),
        'reply' => $this->t('Reply'),
        'reply-all' => $this->t('Reply all'),
        'inbox' => $this->t('Inbox'),
        'mail-bulk' => $this->t('Mail bulk'),
      ],
    ];

    $form['iconlink'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Render the icon as a mailto: link'),
      '#description' => $this->t('Check to render the Font Awesome icon as a mailto: link'),
      '#default_value' => $this->getSetting('iconlink'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $summary[] = $this->t('Output @mailto@truncate@text@HTML@icon',
      [
        '@mailto' => ($this->getSetting('mailto') == TRUE ? 'mailto: link' : 'e-mail address'),
        '@truncate' => ($this->getSetting('truncate') > 0 ? ', truncated to ' . $this->getSetting('truncate') . ' characters' : ''),
        '@text' => ($this->getSetting('text') != '' ? ', preceded by custom text' : ''),
        '@HTML' => ($this->getSetting('HTML') != '' ? ($this->getSetting('text') == '' ? ', preceded by' : ',') . ' custom HTML' : ''),
        '@icon' => ($this->getSetting('icon') != 'none' ? ($this->getSetting('text') == '' && $this->getSetting('HTML') == '' ? ', preceded by a ' : ', ') . ($this->getSetting('iconlink') == TRUE ? 'mailto: linked' : 'plain') . ' ' . $this->getSetting('icon') . ' icon' : ''),
      ]
    );
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'mailto' => TRUE,
      'truncate' => 40,
      'text' => '',
      'HTML' => '',
      'icon' => 'none',
      'iconlink' => TRUE,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    // Step through the items.
    foreach ($items as $delta => $item) {
      $address = $item->getValue()['value'];

      // Generate the icon, if required.
      $icon = ($this->getSetting('icon') != 'none' ?
        '<i class="fas fa-' . $this->getSetting('icon') . ' fa-fw"></i> ' :
        ''
      );

      // Make the icon into a link, if required.
      $icon = ($this->getSetting('iconlink') == TRUE ?
        Link::fromTextAndUrl(
          Markup::create($icon),
          Url::fromUri('mailto:' . $address,
            [
              'attributes' => [
                'title' => $address,
                'target' => '_blank',
              ],
            ]
          )
        )->toString() :
        ''
      );

      $text = $address;

      // Truncate the text, if required.
      $text = ($this->getSetting('truncate') != '0' ?
        ($this->getSetting('truncate') != '' ?
          (mb_strlen($address) - 1 > $this->getSetting('truncate') ?
            substr($address, 0, (int) $this->getSetting('truncate') - 1) :
            $address)
          . (mb_strlen($address) - 1 > $this->getSetting('truncate') ?
            '&hellip;' :
            '') :
          $address
        ) :
        ''
      );

      // Make the text into a link, if required.
      if ($this->getSetting('mailto') == TRUE && $text != '') {
        $markup = Markup::create($text);
        $text = Link::fromTextAndUrl(
          $markup,
          Url::fromUri('mailto:' . $address,
            [
              'attributes' => [
                'title' => $address,
                'target' => '_blank',
              ],
            ]
          )
        )->toString();
      }

      if ($this->getSetting('HTML') != '') {
        $text = XSS::filterAdmin($this->getSetting('HTML')) . $text;
      }

      if ($this->getSetting('text') != '') {
        $text = HTML::escape($this->getSetting('text')) . $text;
      }

      $markup = $icon . ($icon != '' && $text != '' ? ' ' : '') . $text;

      // Return the $markup.
      $elements[$delta] = [
        '#markup' => Markup::create($markup),
      ];
    }

    return $elements;
  }

}
