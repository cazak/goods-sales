<?php

declare(strict_types=1);

namespace App\Api;

use Throwable;

/**
 * @phpstan-template-covariant T as array
 */
final readonly class ParameterBag
{
    /**
     * @param T $parameters
     */
    public function __construct(
        /** @var T */
        private array $parameters,
    ) {}

    /**
     * @return self<array<mixed>>
     */
    public static function createFromJson(string $json): self
    {
        try {
            $payload = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable) {
            $payload = [];
        }

        return new self($payload);
    }

    public function has(string $parameter): bool
    {
        return \array_key_exists($parameter, $this->parameters);
    }

    public function get(string $parameter, mixed $default = null): mixed
    {
        return $this->parameters[$parameter] ?? $default;
    }

    public function getString(string $parameter, string $default = ''): string
    {
        try {
            $value = trim((string) $this->get($parameter, $default));
        } catch (Throwable) {
            $value = $default;
        }

        return $value;
    }

    public function getStringWithoutSpaces(string $parameter, string $default = ''): string
    {
        try {
            $value = (string) preg_replace('/\s+/', '', $this->get($parameter, $default));
        } catch (Throwable) {
            $value = $default;
        }

        return $value;
    }

    /**
     * @param array<mixed> $default
     *
     * @return array<mixed>
     */
    public function getArray(string $parameter, array $default = []): array
    {
        try {
            $value = (array) $this->get($parameter, $default);
        } catch (Throwable) {
            $value = $default;
        }

        return $value;
    }

    public function getBool(string $parameter, bool $default = false): bool
    {
        try {
            $value = filter_var($this->get($parameter, $default), FILTER_VALIDATE_BOOLEAN);
        } catch (Throwable) {
            $value = $default;
        }

        return $value;
    }

    public function getInt(string $parameter, int $default = 0): int
    {
        try {
            $value = (int) $this->get($parameter, $default);
        } catch (Throwable) {
            $value = $default;
        }

        return $value;
    }

    /**
     * @return self<array<mixed>>
     */
    public function getParameterBag(string $parameter): self
    {
        try {
            $value = $this->getArray($parameter);
        } catch (Throwable) {
            $value = [];
        }

        return new self($value);
    }
}
