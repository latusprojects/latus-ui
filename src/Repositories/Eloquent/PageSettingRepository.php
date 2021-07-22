<?php


namespace Latus\UI\Repositories\Eloquent;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Latus\Repositories\EloquentRepository;
use Latus\UI\Models\PageSetting;
use Latus\UI\Repositories\Contracts\PageSettingRepository as PageSettingRepositoryContract;

class PageSettingRepository extends EloquentRepository implements PageSettingRepositoryContract
{

    public function delete(PageSetting $pageSetting)
    {
        $pageSetting->delete();
    }

    public function getValue(PageSetting $pageSetting): string
    {
        return $pageSetting->getValue();
    }

    public function setValue(PageSetting $pageSetting, string $value)
    {
        $pageSetting->setValue($value);
    }

    public function findByKey(string $module, string $page, string $key): Model|null
    {
        return PageSetting::where('module', $module)->where('page', $page)->where('key', $key)->first();
    }

    public function getSettings(string $module, string $page): Collection
    {
        return PageSetting::where('module', $module)->where('page', $page)->get();
    }
}