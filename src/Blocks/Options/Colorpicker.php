<?php

namespace Jeffreyvr\PaverForWordpress\Blocks\Options;

use Jeffreyvr\Paver\View;
use Jeffreyvr\Paver\Blocks\Options\Option;
use Jeffreyvr\PaverForWordpress\Paver;

class Colorpicker extends Option
{
    public function __construct(public string $label, public string $name, public array|bool $palettes = true)
    {
        //
    }

    public static function scripts()
    {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    }

    public function render(): string
    {
        $palettes = is_array($this->palettes) ? htmlentities(json_encode($this->palettes), ENT_QUOTES, 'utf-8') : $this->palettes;

        return <<<HTML
            <div class="paver__option" x-data="{
                init() {
                    jQuery(() => {
                        jQuery(this.\$refs.colorpicker).wpColorPicker({
                            change: (el) => {
                                this.\$nextTick(() => {
                                    this.{$this->name} = el.target.value
                                })
                            },
                            clear: (el) => {
                                this.{$this->name} = null
                            },
                            palettes: {$palettes}
                        });
                    })
                }
            }">
                <label>{$this->label}</label>
                <input type="text" x-ref="colorpicker" x-model="{$this->name}" />
            </div>
        HTML;
    }
}
