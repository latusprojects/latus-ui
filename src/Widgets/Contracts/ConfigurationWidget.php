<?php


namespace Latus\UI\Widgets\Contracts;


interface ConfigurationWidget
{
    public function storeConfiguration(array $configurationItems);

    public function getConfiguration();
}