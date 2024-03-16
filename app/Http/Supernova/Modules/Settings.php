<?php

namespace App\Http\Supernova\Modules;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\Field;
use marcusvbda\supernova\FIELD_TYPES;
use marcusvbda\supernova\FILTER_TYPES;
use marcusvbda\supernova\Module;

class Settings extends Module
{
    public function name(): array
    {
        return ['Parâmetro', 'Configurações'];
    }

    public function permissions()
    {
        $user = Auth::user();
        $isRoot = $user->role === 'root';
        return [
            "view_index" => $isRoot,
            "view_details" => $isRoot,
            "create" => $isRoot,
            "edit" => $isRoot,
            "delete" => $isRoot
        ];
    }

    public function menu(): ?string
    {
        return null;
    }

    public function model(): string
    {
        return Setting::class;
    }

    public function dataTable(): array
    {
        $columns[] = Column::make("id", "Id")->width("200px")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::NUMBER_RANGE);
        $columns[] = Column::make("key", "Chave")
            ->searchable()->sortable()
            ->callback(function ($row) {
                $keys = Setting::$KEYS;
                return data_get(collect($keys)->where("value", $row->key)->first(), "label");
            })
            ->filterable(FILTER_TYPES::SELECT)
            ->filterOptions(Setting::$KEYS);
        $columns[] = Column::make("value", "Valor")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::TEXT);
        return $columns;
    }

    public function fields($row, $page): array
    {
        return [
            Field::make("key", "Chave")->rules(["required", "unique:settings,key," . @$row->id])
                ->type(FIELD_TYPES::SELECT)->options(Setting::$KEYS),
            Field::make("value", "Valor")->rules(["required"]),
        ];
    }
}
