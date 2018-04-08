<?php

class MobilePlatforms
{
    const ANDROID_PLATFORM = 'android';
    const IOS_PLATFORM = 'ios';
    const IOS_SANDBOX_PLATFORM = 'ios_sandbox';

    public static function available_platforms(): array
    {
        return [
            self::ANDROID_PLATFORM,
            self::IOS_PLATFORM,
            self::IOS_SANDBOX_PLATFORM
        ];
    }
}