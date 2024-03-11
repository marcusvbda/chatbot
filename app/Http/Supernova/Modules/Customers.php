<?php

namespace App\Http\Supernova\Modules;

use App\Models\Customer;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\Field;
use marcusvbda\supernova\FILTER_TYPES;
use marcusvbda\supernova\Module;
use marcusvbda\supernova\Panel;

class Customers extends Module
{
    public function subMenu(): string
    {
        return "Demandas";
    }

    public function name(): array
    {
        return ['Cliente', 'Clientes'];
    }

    public function model(): string
    {
        return Customer::class;
    }

    public function dataTable(): array
    {
        $columns[] = Column::make("id", "Id")->width("200px")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::NUMBER_RANGE);
        $columns[] = Column::make("name", "Nome")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::TEXT);
        $columns[] = Column::make("created_at", "Criado em ...")
            ->searchable()->sortable()
            ->callback(fn ($row) => $row->created_at?->format("d/m/Y - H:i:s"))
            ->filterable(FILTER_TYPES::DATE_RANGE);
        return $columns;
    }

    public function fields($row, $page): array
    {
        return [
            Panel::make("Informações")->fields([
                Field::make("name", "Nome")->rules(["required"], ["required" => "O campo nome é obrigatório"]),
                Field::make("phone", "Telefone")->mask("(99) 99999-9999"),
                Field::make("website", "Site")->rules(["nullable", "url"]),
            ]),
            Panel::make("Responsável")->fields([
                Field::make("contact", "Nome do contato"),
                Field::make("phone_contact", "Telefone do contato")->mask("(99) 99999-9999"),
                Field::make("email_contact", "Email do responsável")->rules(["nullable", "email"]),
            ])
        ];
    }
}
