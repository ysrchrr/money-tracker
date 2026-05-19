<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AdminAuditLog::query()->with(['admin', 'targetUser']);

        if ($request->filled('action')) {
            $query->where('action', $request->string('action')->toString());
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($query) use ($search) {
                $query->where('context', 'like', '%'.$search.'%')
                    ->orWhereHas('admin', function ($query) use ($search) {
                        $query->where('name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('targetUser', function ($query) use ($search) {
                        $query->where('name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
                    });
            });
        }

        return view('admin.audit-logs.index', [
            'auditLogs' => $query->latest()->limit(200)->get(),
            'actions' => AdminAuditLog::query()->select('action')->distinct()->orderBy('action')->pluck('action'),
            'filters' => $request->only(['action', 'search']),
        ]);
    }
}
