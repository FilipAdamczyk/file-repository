<?php

namespace Assets;

use Interfaces\AssetStorageInterface;
use Interfaces\AssetStrategy;

abstract class AssetsAbstract {

    /**
     * @var AssetStorageInterface
     */
    private $_strategy;

    public final function __construct(AssetStrategy $strategy)
    {
        $this->_strategy = $strategy;
    }

    public final function strategy(): AssetStrategy
    {
        return $this->_strategy;
    }

    /**
     * Function validating request parameters
     * @return boolean
     */
    abstract public function validate(): bool;

    /**
     * Function for getting object from bucket
     * @return string|null
     */
    abstract public function get();

    /**
     * Function for getting url to upload file to
     * @return string
     */
    abstract public function save(): string;
}