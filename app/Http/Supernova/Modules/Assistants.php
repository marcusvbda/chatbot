<?php

namespace App\Http\Supernova\Modules;

use App\Http\Controllers\OpenIaController;
use App\Models\Assistant;
use Illuminate\Support\Facades\Auth;
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
            Field::make("actions", "Ações")->component(function ($entity) {
                return view("chatbot.actions", compact("entity"));
            })->canSee(in_array($page, ["details", "edit"])),
            Field::make(TrainRows::class)
        ];
    }

    public function onSave($id, $values, $info = []): int
    {
        $parentResult = parent::onSave($id, $values, $info);
        $controller = new OpenIaController;
        $name =  data_get($values, 'save.name');
        $instructions =  data_get($values, 'save.instructions');
        if ($id) {
            $model = $this->makeModel()->findOrFail($id);
            $controller->updateAssistant($model->openia_id, $name, $instructions);
        } else {
            $result = $controller->createAssistant($name, $instructions);
            $model = $this->makeModel()->findOrFail($parentResult);
            $model->openia_id = data_get($result, "id");
            $model->save();
        }
        return $parentResult;
    }

    public function onDelete($entity): void
    {
        $controller = new OpenIaController;
        if ($entity->file_id) {
            $controller->removeAssistantFile($entity);
        }
        $controller->deleteAssistant($entity->openia_id);
        parent::onDelete($entity);
    }

    public function canCreate(): bool
    {
        return Auth::user()->hasPermission('create-assistants');
    }

    public function canEdit(): bool
    {
        return Auth::user()->hasPermission('edit-assistants');
    }

    public function canDelete(): bool
    {
        return Auth::user()->hasPermission('delete-assistants');
    }
}
