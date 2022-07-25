<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\ShippingCost;
use Maatwebsite\Excel\Concerns\ToCollection;

class ShippingCostImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $data = [
                'poblacion_origen' => $row[0],
                'poblacion_destino' => $row[1],
                'departamento_destino' => $row[2],
                'tipo_envio' => $row[3],
                'd2021_paq' => floatval(str_replace(",", ".", substr($row[4], 1))),
                'd2021_msj' => $row[5],
                'd1kg_msj' => floatval(str_replace(",", ".", substr($row[6], 1))),
                'd2kg_msj' => floatval(str_replace(",", ".", substr($row[7], 1))),
                'd3kg_msj' => floatval(str_replace(",", ".", substr($row[8], 1))),
                'd4kg_msj' => floatval(str_replace(",", ".", substr($row[9], 1))),
                'd5kg_msj' => floatval(str_replace(",", ".", substr($row[10], 1))),

                'price' => 0,
                'price_percentage' => 0,
                'is_active' => false
            ];

            ShippingCost::create($data);
        }
    }
}
