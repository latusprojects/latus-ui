<?php


namespace Latus\UI\Repositories\Contracts;


use Illuminate\Database\Eloquent\Model;
use Latus\Repositories\Contracts\Repository;
use Latus\UI\Models\PageSetting;

interface PageSettingRepository extends Repository
{

    public function __construct(PageSetting $pageSetting);

    public function delete(PageSetting $pageSetting);

    public function getValue(PageSetting $pageSetting): string;

    public function setValue(PageSetting $pageSetting, string $value);

    public function findByKey(string $module, string $page, string $key): Model|null;

}