<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Setup;

use Omar\DependencyInjection\Configuration\ConfigurationAssembler;
use Omar\DependencyInjection\Configuration\Setting;

final class SettingCollection
{
    /** @var Setting[] */
    private $settings = [];

    public function add(Setting $setting): self
    {
        $result = new self();
        $result->settings = $this->settings;
        $result->settings[] = $setting;

        return $result;
    }

    public function apply(ConfigurationAssembler $assembler): void
    {
        foreach ($this->settings as $setting) {
            $setting->apply($assembler);
        }
    }
}
