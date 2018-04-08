<?php

class Asset {
    const CONFIG = 'config';
    const LOGO = 'logo';

    /**
     * @param array $item_params
     * @return AssetsAbstract
     * @throws HttpException
     */
    public static function factory(array $item_params): AssetsAbstract
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
        return 'Assets' . ucfirst(strtolower($item_type));
    }
}