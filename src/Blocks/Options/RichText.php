<?php

namespace Jeffreyvr\PaverForWordpress\Blocks\Options;

use Jeffreyvr\Paver\Blocks\Options\Option;

class RichText extends Option
{
    public function __construct(public string $label, public string $name, public array $config = [])
    {
        //
    }

    public static function scripts()
    {
        wp_enqueue_editor();
    }

    public function render(): string
    {
        $config = htmlentities(json_encode($this->config), ENT_QUOTES, 'UTF-8');

        $optionClass = in_array('quicktags', $this->config) || in_array('mediaButtons', $this->config) ? '' : 'paver__border';

        return <<<HTML
            <div x-data="{
                init() {
                    \$refs.editor.id = this.generateUniqueId();

                    let config = JSON.parse(JSON.stringify({$config}));

                    wp.editor.initialize(\$refs.editor.id, {
                        ...config,
                        tinymce: {
                            setup: (editor) => {
                                const updateContent = () => {
                                    {$this->name} = editor.getContent();
                                };

                                editor.on('input keyup', updateContent);
                                editor.on('change', updateContent);

                                editor.on('init', () => {
                                    editor.setContent({$this->name} || '');
                                });
                            }, ...config.tinymce
                        },
                    });
                },

                generateUniqueId() {
                    return 'editor-' + Math.floor(Math.random() * 1000000);
                }
            }">
                <div class="paver__option">
                    <label>{$this->label}</label>
                    <div class="{$optionClass}">
                        <textarea x-ref="editor"></textarea>
                    </div>
                </div>
            </div>
        HTML;
    }
}
