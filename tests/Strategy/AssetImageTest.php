<?php

include_once __DIR__ . '/../Repository/AssetTestStorage.php';

use PHPUnit\Framework\TestCase;
use Interfaces\AssetStrategy;
use Strategy\AssetImage;

class Strategy_AssetImageTest extends TestCase {

    /**
     * @dataProvider valid_params_provider
     * @param $params
     */
    public function test_get(array $params)
    {
        $expected_result = $this->_expected_url($params);

        $this->assertSame(
            $expected_result,
            $this->_strategy($params)->get()
        );
    }

    /**
     * @dataProvider invalid_params_provider
     * @param $params
     */
    public function test_get_invalid(array $params)
    {
        $this->assertSame(
            null,
            $this->_strategy($params)->get()
        );
    }

    /**
     * @dataProvider valid_params_provider
     * @param $params
     */
    public function test_save(array $params)
    {
        $expected_result = $this->_expected_url(
            $params
        );

        $this->assertSame(
            $expected_result,
            $this->_strategy($params)->save()
        );
    }

    /**
     * @dataProvider valid_params_provider
     * @param $params
     */
    public function test_validate(array $params)
    {
        $this->assertTrue(
            $this->_strategy($params)->validate()
        );
    }

    /**
     * @dataProvider validation_params_provider_invalid
     * @param $params
     */
    public function test_validate_invalid(array $params)
    {
        $this->assertFalse(
            $this->_strategy($params)->validate()
        );
    }

    public function valid_params_provider()
    {
        return [
            [$this->_build_params_array('logo', 'android', 'xhdpi')],
            [$this->_build_params_array('logo', 'ios', 'x1')],
        ];
    }

    public function invalid_params_provider()
    {
        return [
            [$this->_build_params_array(AssetTestStorage::NON_EXISTENT_ITEM_TYPE, 'android')],
            [$this->_build_params_array('logo', 'android')],
            [$this->_build_params_array('logo', 'ios')],
            [$this->_build_params_array('test', 'ios')],
            [$this->_build_params_array('logo', 'test')]
        ];
    }

    /**
     * Validation also checks if size is valid for this type of platform
     * Therefore we need an additional provider
     * @return array
     */
    public function validation_params_provider_invalid()
    {
       return $this->invalid_params_provider() + [
           [$this->_build_params_array('logo', 'android', 'x1')],
           [$this->_build_params_array('logo', 'ios', 'xhdpi')],
           [$this->_build_params_array('logo', 'android', null)],
           [$this->_build_params_array('logo', 'android', 1)],
       ];
    }

    private function _strategy(array $params = []): AssetStrategy
    {
        return  new AssetImage(
            new AssetTestStorage(),
            $params
        );
    }

    private function _build_params_array(string $type = null, string $platform = null, string $size = null): array
    {
        $params = [
            'item' => $type,
            'platform' => $platform
        ];

        if ( ! is_null($size) )
        {
            $params['size'] = $size;
        }

        return $params;
    }

    private function _expected_url(array $params): string
    {
        $url = AssetTestStorage::COMMON_TEST_DOMAIN;

        $url .= DIRECTORY_SEPARATOR . $params['platform'] .
            DIRECTORY_SEPARATOR . $params['item'] .
            DIRECTORY_SEPARATOR . $params['size'];

        return $url;
    }
}