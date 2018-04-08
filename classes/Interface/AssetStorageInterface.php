<?php

interface Interface_AssetStorageInterface {

    public function __construct();

    public function get(): string;

    public function get_info(): ?array;

    public function save(string $mode = null): string;

    public function asset_exists(string $size = null): bool;

    public function set_size(string $size = null);

    public function set_platform(string $platform);

    public function set_type(string $type);

    public function set_tenant_id(int $tenant_id);

    public function set_ttl(int $ttl = 300);

    public function set_metadata(array $metadata);
}