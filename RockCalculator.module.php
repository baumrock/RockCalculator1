<?php namespace ProcessWire;
/**
 * Adds a calculator to any inputfield in the PW backend
 *
 * @author Bernhard Baumrock, 03.03.2021
 * @license Licensed under MIT
 * @link https://www.baumrock.com
 */
class RockCalculator extends WireData implements Module, ConfigurableModule {

  private $assetsLoaded = false;

  public static function getModuleInfo() {
    return [
      'title' => 'RockCalculator',
      'version' => '1.0.1',
      'summary' => 'Adds a calculator to any inputfield in the PW backend.',
      'autoload' => true,
      'singular' => true,
      'icon' => 'calculator',
      'requires' => [],
      'installs' => [],
    ];
  }

  public function init() {
    $this->wire->addHookBefore("Inputfield::render", $this, "hookRender");
    $this->wire->addHookAfter("Inputfield::renderReadyHook", $this, "loadAssets");
  }

  /**
   * Add RockCalculator class to inputfield
   */
  public function hookRender(HookEvent $event) {
    /** @var Inputfield $field */
    $field = $event->object;
    if(!$this->isEnabled($field)) return;
    $field->attr("data-rockcalculator", $field->hasField->rockcalculator);
  }

  /**
   * Is calculator enabled on this field?
   */
  public function isEnabled($inputfield) {
    if(!$inputfield instanceof InputfieldText AND
      !$inputfield instanceof InputfieldInteger AND
      !$inputfield instanceof InputfieldFloat) return false;
    if(!$field = $inputfield->hasField) return false;
    if(!$field->rockcalculator) return false;
    return true;
  }

  /**
   * load assets
   * @param HookEvent $event
   * @return void
   */
  public function loadAssets(HookEvent $event) {
    if($this->assetsLoaded) return;
    $inputfield = $event->object;
    if(!$this->isEnabled($inputfield)) return;

    // load files
    $url = $this->wire->config->urls($this);
    $this->wire->config->scripts->add($url.'lib/math.min.js');
    $this->wire->config->scripts->add($url.'lib/tooltip.js');
    $this->wire->config->scripts->add($url.$this->className.'.js');
    $this->wire->config->styles->add($url.$this->className.'.css');
    $this->assetsLoaded = true;
  }

  /**
  * Config inputfields
  * @param InputfieldWrapper $inputfields
  */
  public function getModuleConfigInputfields($inputfields) {
    return $inputfields;
  }
}
