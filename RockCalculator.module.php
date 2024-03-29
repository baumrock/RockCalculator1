<?php

namespace ProcessWire;

/**
 * Adds a calculator to any inputfield in the PW backend
 *
 * @author Bernhard Baumrock, 03.03.2021
 * @license Licensed under MIT
 * @link https://www.baumrock.com
 */
class RockCalculator extends WireData implements Module, ConfigurableModule
{

  private $assetsLoaded = false;

  public static function getModuleInfo()
  {
    return [
      'title' => 'RockCalculator',
      'version' => '1.0.6',
      'summary' => 'Adds a calculator to any inputfield in the PW backend.',
      'autoload' => true,
      'singular' => true,
      'icon' => 'calculator',
      'requires' => [],
      'installs' => [],
    ];
  }

  public function init()
  {
    $this->wire->addHookBefore("Inputfield::render", $this, "hookRender");
    $this->wire->addHookAfter("Inputfield::renderReadyHook", $this, "loadAssets");
  }

  /**
   * Add RockCalculator class to inputfield
   */
  public function hookRender(HookEvent $event)
  {
    /** @var Inputfield $field */
    $field = $event->object;
    if (!$this->isEnabled($field)) return;
    $field->attr("data-rockcalculator", $field->hasField->rockcalculator);
  }

  /**
   * Is calculator enabled on this field?
   */
  public function isEnabled($inputfield)
  {
    if (!$field = $inputfield->hasField) return false;
    if (!$field->rockcalculator) return false;
    if ($field->inputType and $field->inputType != "text") {
      $this->warning("Field $field has inputType={$field->inputType} and should have 'text' to make RockCalculator work!");
    }
    return true;
  }

  /**
   * load assets
   * @param HookEvent $event
   * @return void
   */
  public function loadAssets(HookEvent $event)
  {
    if ($this->assetsLoaded) return;
    $inputfield = $event->object;
    if (!$this->isEnabled($inputfield)) return;

    // load files
    $this->wire->config->scripts->add($this->m('lib/math.min.js'));
    $this->wire->config->scripts->add($this->m('lib/tooltip.js'));
    $this->wire->config->scripts->add($this->m($this->className . '.js'));
    $this->wire->config->styles->add($this->m($this->className . '.css'));
    $this->assetsLoaded = true;
  }

  /**
   * Return url including timestamp (cache buster)
   * @return string
   */
  public function m($file)
  {
    $config = $this->wire->config;
    if (!is_file($file)) $file = $config->paths($this) . $file;
    $m = "?m=" . filemtime($file);
    return str_replace($config->paths->root, $config->urls->root, $file) . $m;
  }

  /**
   * Config inputfields
   * @param InputfieldWrapper $inputfields
   */
  public function getModuleConfigInputfields($inputfields)
  {
    $name = strtolower($this);
    $inputfields->add([
      'type' => 'markup',
      'label' => 'Documentation & Updates',
      'icon' => 'life-ring',
      'value' => "<p>Hey there, coding rockstars! 👋</p>
        <ul>
          <li><a class=uk-text-bold href=https://www.baumrock.com/modules/$name/docs>Read the docs</a> and level up your coding game! 🚀💻😎</li>
          <li><a class=uk-text-bold href=https://www.baumrock.com/rock-monthly>Sign up now for our monthly newsletter</a> and receive the latest updates and exclusive offers right to your inbox! 🚀💻📫</li>
          <li><a class=uk-text-bold href=https://github.com/baumrock/$name>Show some love by starring the project</a> and keep me motivated to build more awesome stuff for you! 🌟💻😊</li>
          <li><a class=uk-text-bold href=https://paypal.me/baumrockcom>Support my work with a donation</a>, and together, we'll keep rocking the coding world! 💖💻💰</li>
        </ul>",
    ]);
  }
}
