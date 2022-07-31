<?php

namespace App\Imports;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Spatie\Permission\Models\Role;

class RoleImport implements ToModel,WithUpserts,WithValidation,WithHeadingRow,SkipsOnFailure,SkipsEmptyRows
{
    use Importable,SkipsFailures;
    /**
     * @param array $row
     *
     * @return Model|Model[]|null
     */
    public function model(array $row)
    {
        // TODO: Implement model() method.
        return new Role([
            'name' => $row['aldor']
        ]);
    }
    public function uniqueBy()
    {
        return 'name';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        // TODO: Implement rules() method.
        return [
            'aldor' => 'required|string|unique:roles,name',
        ];
    }
    public function customValidationMessages()
    {
        return [
            'aldor.required' => 'اسم الدور مطلوب',
            'aldor.string' => 'اسم الدور من نوع نص',
            'aldor.unique' => 'اسم الدور يجب ان يكون فريد',
        ];
    }
}
