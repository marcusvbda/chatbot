<?php

namespace App\Http\Supernova\Modules;

use App\Models\TrainRow;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\Field;
use marcusvbda\supernova\FILTER_TYPES;
use marcusvbda\supernova\Module;

class TrainRows extends Module
{
    public function name(): array
    {
        return ['Linha de Treinamento', 'Linhas de Treinamento'];
    }

    public function permissions()
    {
        return [
            "view_index" => false,
            "delete" => false,
            "create" => false,
            "edit" => false,
        ];
    }

    public function model(): string
    {
        return TrainRow::class;
    }

    public function dataTable(): array
    {
        $columns[] = Column::make("id", "Id")->width("200px")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::NUMBER_RANGE);
        $columns[] = Column::make("prompt", "Prompt")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::TEXT);
        $columns[] = Column::make("completion", "Completion")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::TEXT);
        return $columns;
    }

    public function fields($row, $page): array
    {
        return [
            Field::make("prompt", "Prompt")->rules(["required"]),
            Field::make("completion", "Completion")->rules(["required"]),
        ];
    }
}
