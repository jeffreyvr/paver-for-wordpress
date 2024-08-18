<?php

namespace Spatie\XRay\Configuration;

class ConfigurationItemList
{
    public array $include = [];

    public array $ignore = [];

    public array $default = [];

    protected bool $isPartial = false;

    public static function make(array $defaults, bool $isPartial = false): self
    {
        $result = new self();
        $result->isPartial = $isPartial;
        $result->default = $defaults;

        return $result;
    }

    /**
     * @return array|string[]
     */
    public function ignored(): array
    {
        return array_unique($this->ignore);
    }

    /**
     * @return array|string[]
     */
    public function included(): array
    {
        return array_unique($this->include);
    }

    public function values(): array
    {
        $result = ! empty($this->include)
            ? array_merge($this->default, $this->include)
            : $this->default;

        if (! empty($this->include)) {
            $result = in_array('*', $this->include, true)
                ? $this->default
                : $this->include;
        }

        if (! empty($this->ignore)) {
            $result = in_array('*', $this->include, true)
                ? []
                : array_diff($result, $this->ignore);
        }

        return array_unique($result);
    }
}
