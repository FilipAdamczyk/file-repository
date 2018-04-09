<?php

use Interfaces\AssetStorageInterface;

class AssetTestStorage implements AssetStorageInterface {

    const NON_EXISTENT_ITEM_TYPE = 'promo';

    const COMMON_TEST_DOMAIN = 'https://test.test';

    const FILE_ASSET_TYPE = 'config';

    private $_size = null;

    private $_platform = null;

    private $_type = null;

    private $_ttl = 300;

    private $_metadata = null;

    public function __construct()
    {
    }

    public function get(): string
    {
        return self::COMMON_TEST_DOMAIN . DIRECTORY_SEPARATOR . $this->_build_asset_uri();
    }

    public function save(): string
    {
        return self::COMMON_TEST_DOMAIN . DIRECTORY_SEPARATOR . $this->_build_asset_uri();
    }

    public function asset_exists(string $size = null): bool
    {
        return (
            $this->_type &&
            $this->_type != self::NON_EXISTENT_ITEM_TYPE &&
            ($this->_type != self::FILE_ASSET_TYPE xor is_null($this->_size))
        );
    }

    public function set_size(string $size = null)
    {
        $this->_size = $size;
    }

    public function set_platform(string $platform)
    {
        $this->_platform = $platform;
    }

    public function set_type(string $type)
    {
        $this->_type = $type;
    }

    public function set_metadata(array $metadata)
    {
        $this->_metadata = $metadata;
    }

    private function _build_asset_uri(string $size = null)
    {
        if ( ! ($this->_type && $this->_platform) )
        {
            return null;
        }
        $uri = $this->_platform .
            DIRECTORY_SEPARATOR . $this->_type;

        if ( $this->_size || $size )
        {
            $uri .= DIRECTORY_SEPARATOR . ($size ?? $this->_size);
        }
        return $uri;
    }

    public function set_ttl(int $ttl = 300)
    {
        $this->_ttl = $ttl;
    }
}