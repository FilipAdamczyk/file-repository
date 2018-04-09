<?php

include_once __DIR__ . '/../Repository/AssetTestStorage.php';

use PHPUnit\Framework\TestCase;
use Interfaces\AssetStrategy;
use Strategy\AssetFile;

class AssetFileTest extends TestCase {

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

    public function test_validate()
    {
        $this->assertTrue(
            $this->_strategy(
                $this->_build_params_array('config', 'android')
            )
            ->validate()
        );
    }

    public function valid_params_provider()
    {
        return [
            [$this->_build_params_array('config', 'android')],
            [$this->_build_params_array('config', 'ios')],
            [$this->_build_params_array('config', 'android', 'xhdpi')],
            [$this->_build_params_array('config', 'ios', 'x1')]
        ];
    }

    public function invalid_params_provider()
    {
        return [
            [$this->_build_params_array(AssetTestStorage::NON_EXISTENT_ITEM_TYPE, 'android')],
            [$this->_build_params_array('test', 'ios')]
        ];
    }

    private function _strategy(array $params = []): AssetStrategy
    {
        return  new AssetFile(
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
        DIRECTORY_SEPARATOR . $params['item'];

        return $url;
    }
}