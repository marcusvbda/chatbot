<?php

namespace App\Http\Supernova\Modules;

use App\Http\Controllers\OpenIaController;
use App\Livewire\Chatbot\TestBot;
use App\Models\Assistant;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\Field;
use marcusvbda\supernova\FIELD_TYPES;
use marcusvbda\supernova\FILTER_TYPES;
use marcusvbda\supernova\Module;

class Assistants extends Module
{
    public function subMenu(): string
    {
        return "Chatbot";
    }

    public function name(): array
    {
        return ['Assistente', 'Assistentes'];
    }

    public function model(): string
    {
        return Assistant::class;
    }

    public function dataTable(): array
    {
        $columns[] = Column::make("id", "Id")->width("200px")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::NUMBER_RANGE);
        $columns[] = Column::make("name", "Nome")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::TEXT);
        $columns[] = Column::make("openia_id", "Id na OpenIA")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::TEXT);
        return $columns;
    }

    public function fields($row, $page): array
    {
        return [
            Field::make("name", "Nome")->rules(["required"]),
            Field::make("instructions", "Instruções")->type(FIELD_TYPES::TEXTAREA)->rules(["required"]),
            Field::make("openia_id", "Id na OpenIA")->disabled()->canSee(in_array($page, ["details", "edit"])),
            Field::make("signture", "Chatbot")->component(function ($entity) {
                $id = data_get($entity, "id");
                return <<<BLADE
                    <a href="/assistants/$id/test-bot" class="text-sm text-blue-500 hover:underline">Testar assistente</a>
                BLADE;
            })->canSee(in_array($page, ["details", "edit"])),
        ];
    }

    public function onSave($id, $values, $info = []): int
    {
        $parentResult = parent::onSave($id, $values, $info);

        if (!$id) {
            $name =  data_get($values, 'save.name');
            $instructions =  data_get($values, 'save.instructions');
            $result = (new OpenIaController)->createAssistant($name, $instructions);
            $model = $this->makeModel()->findOrFail($parentResult);
            $model->openia_id = data_get($result, "id");
            $model->save();
        }
        return $parentResult;
    }
}
