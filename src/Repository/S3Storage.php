<?php

namespace Repository;

use Interfaces\AssetStorageInterface;
use \Aws\S3\S3Client;
use \Aws\S3\Exception\S3Exception;

class S3Storage implements AssetStorageInterface
{

    private $_bucket = ''; //Bucket you would like to upload files to

    private $_s3_handle;

    private $_size = null;

    private $_platform = null;

    private $_type = null;

    private $_ttl = 300;

    private $_metadata = null;

    public function __construct()
    {
        $this->_s3_handle = new S3Client([
            //Your credentials and API version here
        ]);
    }

    public function get(): ?string
    {
        try {
            return $this->_s3_handle->GetObject([
                'Bucket' => $this->_bucket,
                'Key' => $this->_build_asset_uri()
            ]);
        } catch ( S3Exception $e ) {
            return null;
        }
    }

    public function save(): string
    {
        $request_params = [
            'ACL' => 'private',
            'Bucket' => $this->_bucket,
            'Body' => '',
            'Key' => $this->_build_asset_uri()
        ];

        if ( ! is_null($this->_metadata) )
        {
            $request_params['Metadata'] = $this->_metadata;
        }

        $request = $this->_s3_handle->getCommand('PutObject', $request_params);

        return $this->_s3_handle->createPresignedRequest($request, $this->_ttl);
    }

    public function asset_exists(string $size = null): bool
    {
        try {
            $this->_s3_handle->HeadObject([
                'Bucket' => $this->_bucket,
                'Key' => $this->_build_asset_uri( $size )
            ]);

            return true;
        } catch ( S3Exception $e ) {
            return false;
        }
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

    public function set_metadata(array $metadata = null)
    {
        $this->_metadata = $metadata;
    }

    private function _build_asset_uri(string $size = null)
    {
        if ( ! ($this->_type && $this->_platform)) {
            return null;
        }
        $uri = $this->_platform .
            DIRECTORY_SEPARATOR . $this->_type;

        if ($this->_size || $size) {
            $uri .= DIRECTORY_SEPARATOR . ($size ?? $this->_size);
        }
        return $uri;
    }

    public function set_ttl(int $ttl = 300)
    {
        $this->_ttl = $ttl;
    }

}