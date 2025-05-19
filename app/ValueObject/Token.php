<?php

namespace App\ValueObject;

use Illuminate\Support\Str;

final readonly class Token
{
    public function __construct(private string $token)
    {
        if (! Str::isUuid($this->token)) {
            throw new \InvalidArgumentException('Invalid token');
        }
    }

    public static function generate(): self
    {
        return new self(Str::uuid());
    }

    public static function fromString(string $token): self
    {
        return new self($token);
    }

    public function getValue(): string
    {
        return $this->token;
    }
}
