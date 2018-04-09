<?php

namespace Strategy;

use Interfaces\AssetStrategy;
use Interfaces\AssetStorageInterface;

class AssetFile implements AssetStrategy
{
    /**
     * @var AssetStorageInterface
     */
    private $_asset_handler;

    /**
     * @var string|null
     */
    private $_platform = null;

    /**
     * @var string|null
     */
    private $_type = null;

    /**
     * @var array|null
     */
    private $_metadata = null;

    public function __construct(AssetStorageInterface $asset_handler, array $params)
    {
        $this->_asset_handler = $asset_handler;

        $this->_type = $params[ 'item' ];

        if ( ! empty( $params[ 'platform' ] )) {
            $this->_platform = $params[ 'platform' ];
        }

        if ( ! empty( $params[ 'meta' ] ) && is_array( $params[ 'meta' ] )) {
            $this->_metadata = $params[ 'meta' ];
        }
    }

    public function get(): ?string
    {
        $this->_asset_handler_set_config();

        if ( ! $this->_asset_handler->asset_exists()) {
            return null;
        }

        return $this->_asset_handler->get();
    }

    public function validate(): bool
    {
        return true;
    }

    public function save(): string
    {
        $this->_asset_handler_set_config();
        return $this->_asset_handler->save();
    }

    private function _asset_handler_set_config()
    {
        $this->_asset_handler->set_type( $this->_type );
        $this->_asset_handler->set_platform( $this->_platform );
        $this->_asset_handler->set_ttl( 300 );
        if ( ! is_null( $this->_metadata )) {
            $this->_asset_handler->set_metadata( $this->_metadata );
        }
    }
}