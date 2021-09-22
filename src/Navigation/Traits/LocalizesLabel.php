<?php

namespace Latus\UI\Navigation\Traits;

trait LocalizesLabel
{
    public abstract function getLabel(): string;

    public function getLocalizedLabel(): string
    {
        $label = $this->getLabel();

        if (trans()->has($label)) {
            return trans($label);
        }

        if (trans()->has('nav.' . $label)) {
            return trans('nav.' . $label);
        }

        return $label;
    }
}