<?php

interface AssetStrategy {

    public function get(): ?string;

    public function validate(): bool;

    public function save(): string;
}