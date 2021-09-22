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

        if (trans()->has('latus::nav.' . $label)) {
            return trans('latus::nav.' . $label);
        }

        return $label;
    }
}