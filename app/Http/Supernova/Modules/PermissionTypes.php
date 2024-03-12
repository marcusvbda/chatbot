<?php

namespace App\Http\Supernova\Modules;

use App\Models\PermissionType;
use Illuminate\Support\Facades\Auth;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\Field;
use marcusvbda\supernova\FILTER_TYPES;
use marcusvbda\supernova\Module;

class PermissionTypes extends Module
{
    public function subMenu(): string
    {
        return "Acessos";
    }

    public function name(): array
    {
        return ['Tipo de permissão', 'Tipos de permissões'];
    }

    public function model(): string
    {
        return PermissionType::class;
    }

    public function dataTable(): array
    {
        $columns[] = Column::make("id", "Id")->width("200px")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::NUMBER_RANGE);
        $columns[] = Column::make("name", "Nome")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::TEXT);
        return $columns;
    }

    public function fields($row, $page): array
    {
        return [
            Field::make("name", "Nome")->rules(["required"], ["required" => "O campo nome é obrigatório"]),
        ];
    }

    public function permissions()
    {
        $user = Auth::user();
        $isRoot = $user->role === 'root';
        return [
            "view_details" => $isRoot,
            "create" => $isRoot,
            "edit" => $isRoot,
            "delete" => $isRoot
        ];
    }
}
