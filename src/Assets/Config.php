<?php

namespace Assets;

class AssetsConfig extends AssetsAbstract {

    public function validate(): bool
    {
        return $this->strategy()->validate();
    }

    public function get()
    {
        return $this->strategy()->get();
    }

    public function save(): string
    {
        return $this->strategy()->save();
    }
}