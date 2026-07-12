<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\{Task, User};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Hash, Auth, Gate};

class TaskService
{
    public const FIELDS = [
        'title' => 'required|string|max:255|min:3',
        'description' => 'nullable|string|max:3000'
    ];

    public function handleSession(): void
    {
        if (!Auth::check()) {
            $guestId = session('guest_id');
            if (!$guestId) {
                $guestId = Str::uuid()->toString();
                session(['guest_id' => $guestId]);
            }
        }
    }

    public function transferGuestTask(): void
    {
        $guestId = session('guest_id');
        if ($guestId) {
            Task::where('guest_id', $guestId)
                ->update([
                    'user_id' => Auth::id(),
                    'guest_id' => null
                ]);
            session()->forget('guest_id');
        }
    }

    public function createTask(array $fields): void
    {
        Task::create([
            ...$fields,
            'user_id' => Auth::id(),
            'guest_id' => Auth::check() ? null : session('guest_id')
        ]);
    }

    public function prepareResult(Request $request): array
    {
        $search = $request->input('search', '');
        $status = $request->input('status', 'all');
        $statsQuery = Task::forUser(Auth::user(), session('guest_id'))->search($search);
        $total = $statsQuery->count();
        $active = $statsQuery->clone()->where('is_completed', false)->count();

        $stats = [
            'total' => $total,
            'active' => $active,
            'completed' => $total - $active,
        ];

        $tasks = $statsQuery
            ->status($status)
            ->latest()
            ->paginate(3)
            ->withQueryString();

        return [$tasks, $stats ,$search, $status];
    }

    public function validateFields(Request $request): array
    {
        return $request->validate(self::FIELDS);
    }

    public function validateCredentials(Request $request): User
    {
       $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

       return User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
    }

    public function validateLoginCredentials(Request $request): array
    {
        return $request->validate(['email' => 'required|email', 'password' => 'required']);
    }
}
