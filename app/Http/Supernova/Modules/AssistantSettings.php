<?php

namespace App\Http\Supernova\Modules;

use App\Models\AssistantSetting;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\Field;
use marcusvbda\supernova\FIELD_TYPES;
use marcusvbda\supernova\FILTER_TYPES;
use marcusvbda\supernova\Module;

class AssistantSettings extends Module
{
    public function name(): array
    {
        return ['Configuração', 'Configurações'];
    }

    public function subMenu(): string
    {
        return "Chatbot";
    }

    public function model(): string
    {
        return AssistantSetting::class;
    }

    public function dataTable(): array
    {
        $columns[] = Column::make("id", "Id")->width("200px")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::NUMBER_RANGE);
        $columns[] = Column::make("key", "Chave")
            ->searchable()->sortable()
            ->callback(function ($row) {
                $keys = AssistantSetting::$KEYS;
                return data_get(collect($keys)->where("value", $row->key)->first(), "label");
            })
            ->filterable(FILTER_TYPES::SELECT)
            ->filterOptions(AssistantSetting::$KEYS);
        $columns[] = Column::make("value", "Valor")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::TEXT);
        return $columns;
    }

    public function fields($row, $page): array
    {
        return [
            Field::make("key", "Chave")->rules(["required", "unique:assistant_settings,key," . @$row->id])
                ->type(FIELD_TYPES::SELECT)->options(AssistantSetting::$KEYS),
            Field::make("value", "Valor")->rules(["required"]),
        ];
    }
}
