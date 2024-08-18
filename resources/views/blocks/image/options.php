<div x-data="{
    preview: null,
    async init() {
        <?php if($this->option->storeAs === 'id') : ?>
            let response = await api.resolve('Jeffreyvr\\PaverForWordpress\\Blocks\\Options\\Image', 'getPreviewUrl', {id: <?php echo $this->option->name; ?>}, {label: 'Image', name: 'image'})

            this.preview = response.result;
        <?php else: ?>
            this.preview = <?php echo $this->option->name; ?>
        <?php endif; ?>
    },
    open() {
        let library = wp.media(<?php echo $mediaConfig; ?>);

        library.open();

        library.on('select', () => {
            let attachment = library.state().get('selection').first().toJSON();

            <?php echo $this->option->name; ?> = attachment.<?php echo $this->option->storeAs ?? 'url'; ?>;

            this.preview = attachment.url;
        });
    },
}">
    <div class="paver__option">
        <?php if(!$this->option->hideLabel): ?>
        <label><?php echo $this->option->label; ?></label>
        <?php endif; ?>
        <div class="paver__bg-light">
            <template x-if="<?php echo $this->option->name; ?>">
                <div>
                    <div class="paver__selected-image">
                        <img :src="preview">
                        <div class="paver__image-buttons">
                            <button type="button" class="paver__btn-icon" x-on:click="open()" aria-label="<?php echo $config['replace']; ?>" x-tooltip="text('<?php echo $config['replace']; ?>')">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                            </button>
                            <button type="button" class="paver__btn-icon" x-on:click="<?php echo $this->option->name; ?> = null" aria-label="<?php echo $config['remove']; ?>" x-tooltip="text('<?php echo $config['remove']; ?>')">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="!<?php echo $this->option->name; ?>">
                <div class="paver__placeholder-image" x-on:click="open()">
                    <?php echo $config['button']; ?>
                </div>
            </template>
        </div>
    </div>
</div>
