<?php

interface AssetStorageInterface {

    public function __construct();

    public function get(): ?string;

    public function save(): string;

    public function asset_exists(string $size = null): bool;

    public function set_size(string $size = null);

    public function set_platform(string $platform);

    public function set_type(string $type);

    public function set_ttl(int $ttl = 300);

    public function set_metadata(array $metadata);
}