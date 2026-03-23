<?php

namespace App;

trait ResolvesJti
{
    private function resolveJti(): ?string
    {
        try {
            return auth('api')->payload()->get('jti');
        } catch (\Throwable) {
            return null;
        }
    }
}
