<?php

namespace Jeffreyvr\PaverForWordpress\Blocks\Options;

use Jeffreyvr\Paver\View;
use Jeffreyvr\Paver\Blocks\Options\Option;
use Jeffreyvr\PaverForWordpress\Paver;

class Image extends Option
{
    public bool $hideLabel = false;

    public string $storeAs = 'url';

    public function __construct(public string $label, public string $name, public array $config = [])
    {
        //
    }

    public static function scripts()
    {
        wp_enqueue_media();
    }

    public function storeAsId()
    {
        $this->storeAs = 'id';

        return $this;
    }

    public function storeAsUrl()
    {
        $this->storeAs = 'url';

        return $this;
    }

    public function hideLabel(): self
    {
        $this->hideLabel = true;

        return $this;
    }

    public function getPreviewUrl($id)
    {
        return wp_get_attachment_url($id);
    }

    public function render(): string
    {
        $config = [
            'button' => 'Upload or select',
            'remove' => 'Remove',
            'replace' => 'Replace',
            'media' => [
                'title' => 'Select or upload media',
                'button' => [
                    'text' => 'Use this media'
                ],
                'library' => [
                    'type' => 'image'
                ],
                'multiple' => false
            ],
            ...$this->config
        ];

        $mediaConfig = htmlentities(json_encode($config['media']), ENT_QUOTES, 'UTF-8');

        return (new View(Paver::instance()->wordpressViewPath . 'blocks/image/options.php', [
            'option' => $this,
            'config' => $config,
            'mediaConfig' => $mediaConfig,
            // 'preview' => $this->storeAs == 'id' ? wp_get_attachment_url($this->state[$this->name]) : $this->state[$this->name]
        ]))->render();
    }
}
