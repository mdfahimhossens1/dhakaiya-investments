<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductCatalogueReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Check Main Table
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasTable('projects')) {
            return view('super-admin.reports.product-catalogue', [
                'available' => false,
            ]);
        }

        $columns = Schema::getColumnListing('projects');

        $hasColumn = fn ($column) => in_array($column, $columns);

        /*
        |--------------------------------------------------------------------------
        | 2. Detect Available Columns
        |--------------------------------------------------------------------------
        */

        $nameColumn = collect(['title', 'name'])
            ->first(fn ($column) => $hasColumn($column));

        $categoryColumn = collect([
            'project_category_id',
            'category_id',
        ])->first(fn ($column) => $hasColumn($column));

        $companyColumn = collect(['company_id'])
            ->first(fn ($column) => $hasColumn($column));

        $statusColumn = $hasColumn('status') ? 'status' : null;

        $idColumn = $hasColumn('id') ? 'id' : null;

        $createdColumn = $hasColumn('created_at')
            ? 'created_at'
            : null;

        /*
        |--------------------------------------------------------------------------
        | 3. Detect Category Table
        |--------------------------------------------------------------------------
        */

        $categoryTableExists = Schema::hasTable('project_categories');

        $categoryColumns = $categoryTableExists
            ? Schema::getColumnListing('project_categories')
            : [];

        $categoryNameColumn = collect(['name', 'title'])
            ->first(fn ($column) => in_array($column, $categoryColumns));

        $categoryIdExists = in_array('id', $categoryColumns);

        $useCategory = $categoryColumn
            && $categoryNameColumn
            && $categoryIdExists;

        /*
        |--------------------------------------------------------------------------
        | 4. Detect Company Table
        |--------------------------------------------------------------------------
        */

        $companyTableExists = Schema::hasTable('companies');

        $companyColumns = $companyTableExists
            ? Schema::getColumnListing('companies')
            : [];

        $companyNameColumn = collect([
            'name',
            'title',
            'company_name',
        ])->first(fn ($column) => in_array($column, $companyColumns));

        $companyIdExists = in_array('id', $companyColumns);

        $useCompany = $companyColumn
            && $companyNameColumn
            && $companyIdExists;

        /*
        |--------------------------------------------------------------------------
        | 5. Base Query
        |--------------------------------------------------------------------------
        */

        $baseQuery = function () use (
            $useCategory,
            $useCompany,
            $categoryColumn,
            $categoryNameColumn,
            $companyColumn,
            $companyNameColumn
        ) {
            $query = DB::table('projects as p');

            if ($useCategory) {
                $query->leftJoin(
                    'project_categories as c',
                    "p.{$categoryColumn}",
                    '=',
                    'c.id'
                );
            }

            if ($useCompany) {
                $query->leftJoin(
                    'companies as co',
                    "p.{$companyColumn}",
                    '=',
                    'co.id'
                );
            }

            return $query;
        };

        /*
        |--------------------------------------------------------------------------
        | 6. Select Available Fields
        |--------------------------------------------------------------------------
        */

        $select = [];

        if ($idColumn) {
            $select[] = 'p.id';
        }

        if ($nameColumn) {
            $select[] = "p.{$nameColumn} as name";
        } elseif ($idColumn) {
            $select[] = 'p.id as name';
        } else {
            $select[] = DB::raw("'Unnamed product' as name");
        }

        if ($createdColumn) {
            $select[] = "p.{$createdColumn} as created_at";
        } else {
            $select[] = DB::raw('NULL as created_at');
        }

        if ($useCategory) {
            $select[] = "c.{$categoryNameColumn} as category_name";
        }

        if ($useCompany) {
            $select[] = "co.{$companyNameColumn} as company_name";
        }

        if ($statusColumn) {
            $select[] = "p.{$statusColumn} as status";
        }

        /*
        |--------------------------------------------------------------------------
        | 7. Product Rows
        |--------------------------------------------------------------------------
        */

        $rowsQuery = $baseQuery()->select($select);

        if ($request->filled('search') && $nameColumn) {
            $rowsQuery->where(
                "p.{$nameColumn}",
                'like',
                '%' . trim($request->search) . '%'
            );
        }

        if ($request->filled('category') && $useCategory) {
            $rowsQuery->where(
                "p.{$categoryColumn}",
                $request->category
            );
        }

        if ($request->filled('company') && $useCompany) {
            $rowsQuery->where(
                "p.{$companyColumn}",
                $request->company
            );
        }

        if ($request->filled('status') && $statusColumn) {
            $rowsQuery->where(
                "p.{$statusColumn}",
                $request->status
            );
        }

        if ($createdColumn) {
            $rowsQuery->orderByDesc("p.{$createdColumn}");
        } elseif ($idColumn) {
            $rowsQuery->orderByDesc('p.id');
        } elseif ($nameColumn) {
            $rowsQuery->orderBy("p.{$nameColumn}");
        }

        $rows = $rowsQuery
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | 8. General Catalogue Statistics
        |--------------------------------------------------------------------------
        */

        $stats = [
            'total' => DB::table('projects')->count(),

            // Financial/share-related metrics are not included.
            'shares' => null,
            'sold' => null,
        ];

        /*
        |--------------------------------------------------------------------------
        | 9. Status Breakdown
        |--------------------------------------------------------------------------
        */

        $statusBreakdown = $statusColumn
            ? DB::table('projects')
                ->select(
                    "{$statusColumn} as label",
                    DB::raw('COUNT(*) as total')
                )
                ->groupBy($statusColumn)
                ->pluck('total', 'label')
            : collect();

        /*
        |--------------------------------------------------------------------------
        | 10. Category Breakdown
        |--------------------------------------------------------------------------
        */

        $categoryBreakdown = $useCategory
            ? $baseQuery()
                ->selectRaw(
                    "COALESCE(c.{$categoryNameColumn}, 'Uncategorized') as label"
                )
                ->selectRaw('COUNT(p.id) as total')
                ->groupByRaw(
                    "COALESCE(c.{$categoryNameColumn}, 'Uncategorized')"
                )
                ->orderByDesc('total')
                ->get()
            : collect();

        /*
        |--------------------------------------------------------------------------
        | 11. Company Breakdown
        |--------------------------------------------------------------------------
        */

        $companyBreakdown = $useCompany
            ? $baseQuery()
                ->selectRaw(
                    "COALESCE(co.{$companyNameColumn}, 'No company') as label"
                )
                ->selectRaw('COUNT(p.id) as total')
                ->groupByRaw(
                    "COALESCE(co.{$companyNameColumn}, 'No company')"
                )
                ->orderByDesc('total')
                ->limit(8)
                ->get()
            : collect();

        /*
        |--------------------------------------------------------------------------
        | 12. Dropdown Data
        |--------------------------------------------------------------------------
        */

        $categories = $useCategory
            ? DB::table('project_categories')
                ->select('id', "{$categoryNameColumn} as name")
                ->orderBy($categoryNameColumn)
                ->get()
            : collect();

        $companies = $useCompany
            ? DB::table('companies')
                ->select('id', "{$companyNameColumn} as name")
                ->orderBy($companyNameColumn)
                ->get()
            : collect();

        $statuses = $statusBreakdown->keys();

        /*
        |--------------------------------------------------------------------------
        | 13. Available Fields
        |--------------------------------------------------------------------------
        */

        $has = [
            'category' => (bool) $useCategory,
            'company' => (bool) $useCompany,
            'status' => (bool) $statusColumn,

            // Financial fields intentionally excluded.
            'price' => false,
            'shares' => false,
            'sold' => false,

            'created_at' => (bool) $createdColumn,
        ];

        $available = true;

        /*
        |--------------------------------------------------------------------------
        | 14. Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'super-admin.reports.product-catalogue',
            compact(
                'available',
                'rows',
                'stats',
                'statusBreakdown',
                'categoryBreakdown',
                'companyBreakdown',
                'categories',
                'companies',
                'statuses',
                'has'
            )
        );
    }
}