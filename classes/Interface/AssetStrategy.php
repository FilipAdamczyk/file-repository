<?php

interface Interface_AssetStrategy {

    public function get(): ?string;

    public function get_info(): ?array;

    public function validate(): bool;

    public function save(): bool;
}