<?php

class AssetImage implements AssetStrategy
{

    const IOS_SIZE_X1 = 'x1';
    const IOS_SIZE_X2 = 'x2';
    const IOS_SIZE_X3 = 'x3';
    const ANDROID_SIZE_LDPI = 'ldpi';
    const ANDROID_SIZE_MDPI = 'mdpi';
    const ANDROID_SIZE_HDPI = 'hdpi';
    const ANDROID_SIZE_XHDPI = 'xhdpi';
    const ANDROID_SIZE_XXHDPI = 'xxhdpi';
    const ANDROID_SIZE_XXXHDPI = 'xxxhdpi';

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
     * @var string|null
     */
    private $_size = null;

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

        if ( ! empty( $params[ 'size' ] )) {
            $this->_size = $params[ 'size' ];
        }
    }

    public function get(): ?string
    {
        $this->_asset_handler_set_config();

        if ( ! $this->_determine_size()) {
            return null;
        }

        return $this->_asset_handler->get();
    }

    public function validate(): bool
    {
        if ( ! ($this->_size && in_array( $this->_size, $this->_available_sizes() ))) {
            return false;
        }
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
        $this->_asset_handler->set_size( $this->_size );
        $this->_asset_handler->set_ttl( 3600 );
        if ( ! is_null( $this->_metadata )) {
            $this->_asset_handler->set_metadata( $this->_metadata );
        }
    }

    private function _available_sizes(): array
    {
        switch ( $this->_platform ) {
            case MobilePlatforms::ANDROID_PLATFORM :
                return [
                    self::ANDROID_SIZE_LDPI,
                    self::ANDROID_SIZE_MDPI,
                    self::ANDROID_SIZE_HDPI,
                    self::ANDROID_SIZE_XHDPI,
                    self::ANDROID_SIZE_XXHDPI,
                    self::ANDROID_SIZE_XXXHDPI
                ];
            case MobilePlatforms::IOS_SANDBOX_PLATFORM :
            case MobilePlatforms::IOS_PLATFORM :
                return [
                    self::IOS_SIZE_X1,
                    self::IOS_SIZE_X2,
                    self::IOS_SIZE_X3
                ];
            default :
                return [];
        }
    }

    private function _determine_size(): bool
    {
        if ($this->_asset_handler->asset_exists()) {
            return true;
        }

        $available_sizes = $this->_available_sizes();

        $index = array_search( $this->_size, $available_sizes );
        if ($index === false) {
            return false;
        }

        //Search for larger
        $current_index = $index + 1;
        while ( ! empty( $available_sizes[ $current_index ] ) ) {
            if ($this->_asset_handler->asset_exists( $available_sizes[ $current_index ] )) {
                $this->_update_size( $available_sizes[ $current_index ] );
                return true;
            }
            $current_index++;
        }

        //Search for smaller
        $current_index = $index - 1;
        while ( ! empty( $available_sizes[ $current_index ] ) ) {
            if ($this->_asset_handler->asset_exists( $available_sizes[ $current_index ] )) {
                $this->_update_size( $available_sizes[ $current_index ] );
                return true;
            }
            $current_index--;
        }

        //Not found
        return false;
    }

    private function _update_size(string $size)
    {
        $this->_size = $size;
        $this->_asset_handler->set_size( $this->_size );
    }
}