<?php

declare(strict_types=1);

namespace Axleus;

use function file_exists;

class SettingsProvider
{
    public const SETTINGS_PATH = __DIR__ . '/../../../../data/settings/';
    /**
     * Extending class should define this property as the target file name
     */
    protected ?string $file = null;

    public function __invoke(): array
    {
        $settings = [];
        if ($this->file !== null && file_exists(static::SETTINGS_PATH . $this->file)) {
            $settings = (include_once static::SETTINGS_PATH . $this->file);
        }
        if (is_array($settings)) {
            return $settings;
        }
        return [];
    }
}
