<?php


namespace Latus\UI\Repositories\Eloquent;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use Latus\Settings\Models\Setting;
use Latus\Settings\Services\SettingService;
use Latus\UI\Components\Contracts\ModuleComponent;
use Latus\UI\Repositories\Contracts\ComponentRepository as ComponentRepositoryContract;

class ComponentRepository implements ComponentRepositoryContract
{

    public function __construct(
        protected Collection $providedModules,
        protected array $definedWidgets,
        protected array $definedModules,
        protected SettingService $settingService
    )
    {
    }

    public function defineModule(string $moduleContract)
    {
        if (!in_array($moduleContract, $this->definedModules)) {
            $this->definedModules[] = $moduleContract;
        }
    }

    public function provideModule(string $moduleContract, string $moduleClass)
    {
        if (in_array($moduleContract, $this->definedModules)
            && !in_array($moduleContract, $this->getDisabledModules())
            && ($this->providedModules->has($moduleContract) && !$this->providedModules->get($moduleContract)->has($moduleClass))
        ) {
            $this->providedModules->mergeRecursive(collect([$moduleContract => [$moduleClass]]));
        }
    }

    public function defineWidget(string $widgetClass)
    {
        if (!in_array($widgetClass, $this->definedWidgets)) {
            $this->definedWidgets[] = $widgetClass;
        }
    }

    public function getActiveModule(string $moduleContract): ModuleComponent|null
    {
        try {
            return app()->make($moduleContract);
        } catch (BindingResolutionException $e) {
            return null;
        }
    }

    public function setActiveModule(string $moduleContract, string $moduleClass)
    {

        /**
         * @var Setting $setting
         */
        $setting = $this->settingService->findByKey('active_modules');
        $activeModules = unserialize($setting->getValue());
        $activeModules[$moduleContract] = $moduleClass;

        $this->settingService->setSettingValue($setting, serialize($activeModules));

    }

    public function disableModule(string $moduleContract)
    {
        /**
         * @var Setting $setting
         */
        $setting = $this->settingService->findByKey('disabled_modules');
        $disabledModules = unserialize($setting->getValue());

        if (!in_array($moduleContract, $disabledModules)) {
            $disabledModules[] = $moduleContract;
            $this->settingService->setSettingValue($setting, serialize($disabledModules));
        }

    }

    public function enableModule(string $moduleContract)
    {
        /**
         * @var Setting $setting
         */
        $setting = $this->settingService->findByKey('disabled_modules');
        $disabledModules = unserialize($setting->getValue());
        if ($key = array_search($moduleContract, $disabledModules)) {

            unset($disabledModules[$key]);
            $this->settingService->setSettingValue($setting, serialize($disabledModules));
        }
    }

    public function getDisabledModules(): array
    {
        /**
         * @var Setting $setting
         */
        $setting = $this->settingService->findByKey('disabled_modules');
        return unserialize($setting->getValue());
    }
}