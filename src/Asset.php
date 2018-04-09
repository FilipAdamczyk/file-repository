<?php

use Strategy\AssetFile;
use Strategy\AssetImage;
use Repository\S3Storage;

class Asset {
    const CONFIG = 'config';
    const LOGO = 'logo';

    /**
     * $item_params array consists of item - item type, platform - android, ios, ios_sandbox and size - optional
     *
     * @param array $item_params
     * @return Assets\AssetsAbstract
     * @throws HttpException
     */
    public static function factory(array $item_params): Assets\AssetsAbstract
    {
        $item_type = $item_params['item'];
        $model_name = self::_build_model_name($item_type);

        switch ($item_type)
        {
            //Files
            case self::CONFIG : {
                $object = new $model_name(
                    new AssetFile(
                        new S3Storage(),
                        $item_params
                    )
                );
                break;
            }
            //Images
            case self::LOGO : {
                $object = new $model_name(
                    new AssetImage(
                        new S3Storage(),
                        $item_params
                    )
                );
                break;
            }
            default: throw new HttpException('Unsupported item type', 400);
        }
        return $object;
    }

    public static function validate(string $item_type): bool
    {
        if ( ! class_exists(self::_build_model_name($item_type)) )
        {
            return false;
        }

        return true;
    }

    private static function _build_model_name(string $item_type): string
    {
        return 'Assets\Assets' . ucfirst(strtolower($item_type));
    }
}