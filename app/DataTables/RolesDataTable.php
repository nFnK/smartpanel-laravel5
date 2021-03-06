<?php

namespace App\DataTables;

use App\Role;
use Yajra\Datatables\Services\DataTable;

class RolesDataTable extends DataTable
{

    protected $exportColumns = ['id', 'name', 'permissions'];
    protected $printColumns = ['id', 'name', 'permissions'];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables->eloquent($this->query())
            ->setRowId(function ($role) {
                return 'arrayorder_' . $role->id;
            })
            ->addColumn('checkbox', function ($role) {
                return "<input type=\"checkbox\" name=\"item[]\" value=\"$role->id\"/>";
            })
            ->addColumn('active', function ($role) {
                if ($role->active == 0) {
                    return '<span class="label label-flat border-danger text-danger-600">PASİF</span>';
                } elseif ($role->active == 1) {
                    return '<span class="label label-flat border-success text-success-600">AKTİF</span>';
                }
            })
            ->addColumn('actions', function ($role) {
            return '<td class="text-center">
                    <ul class="icons-list">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="' . route('admin.roles.edit', array('id' => $role->id)) . '"><i class="icon-cogs"></i> Düzenle</a></li>
                                    <li><a class="confirm-btn" href="#" data-id="' . $role->id . '" data-token="' . csrf_token() . '"
                                    data-url="' . route('admin.roles.destroy', array('id' => $role->id)) . '" data-title="' . $role->name . '"><i class="icon-cross2"></i> Sil</a></li>
                                </ul>
                        </li>
                    </ul>
            </td>';
        })->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = Role::query()->orderBy('id', 'desc');
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())->ajax()->addAction(['width' => '80px'])
            ->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'roles_' . time();
    }
}

