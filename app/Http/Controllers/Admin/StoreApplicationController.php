<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreApplication;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class StoreApplicationController extends Controller
{
    /**
     * Display a listing of store applications.
     */
    public function index(Request $request): View
    {
        $query = StoreApplication::query()->with('reviewer');

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Sort by newest first
        $applications = $query->latest()->paginate(20);

        return view('admin.applications.index', compact('applications'));
    }

    /**
     * Display the specified application.
     */
    public function show(StoreApplication $application): View
    {
        $application->load('reviewer');
        return view('admin.applications.show', compact('application'));
    }

    /**
     * Approve a store application and create store + owner user.
     */
    public function approve(Request $request, StoreApplication $application): RedirectResponse
    {
        if (!$application->isPending()) {
            return redirect()->back()->with('error', 'この申請は既に処理されています。');
        }

        try {
            DB::beginTransaction();

            // Create store
            $store = Store::create([
                'store_name' => $application->store_name,
                'industry' => $application->industry,
                'contact_name' => $application->contact_name,
                'email' => $application->email,
                'phone' => $application->phone,
                'status' => 'active',
            ]);

            // Generate username from email
            $username = $this->generateUsername($application->email);

            // Generate temporary password (will be sent via email)
            $temporaryPassword = bin2hex(random_bytes(8));

            // Create store owner user
            $user = User::create([
                'username' => $username,
                'name' => $application->contact_name,
                'email' => $application->email,
                'password' => Hash::make($temporaryPassword),
                'role' => 'store_owner',
                'user_level' => 'free',
                'status' => 'active',
                'store_id' => $store->id,
                'approved_at' => now(),
                'approved_by' => $request->user()->id,
            ]);

            // Update application status
            $application->update([
                'status' => 'approved',
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
            ]);

            // Fire Registered event (triggers email verification notification)
            event(new Registered($user));

            DB::commit();

            // TODO: Send email with temporary password to store owner
            // Mail::to($user->email)->send(new StoreApprovalMail($user, $temporaryPassword));

            return redirect()->route('admin.applications.index')
                ->with('success', "申請を承認し、店舗とオーナーアカウントを作成しました。\n店舗ID: {$store->id}\nユーザーID: {$user->id}");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', '承認処理中にエラーが発生しました: ' . $e->getMessage());
        }
    }

    /**
     * Reject a store application.
     */
    public function reject(Request $request, StoreApplication $application): RedirectResponse
    {
        if (!$application->isPending()) {
            return redirect()->back()->with('error', 'この申請は既に処理されています。');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $application->update([
            'status' => 'rejected',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // TODO: Send rejection email to applicant
        // Mail::to($application->email)->send(new StoreRejectionMail($application));

        return redirect()->route('admin.applications.index')
            ->with('success', '申請を却下しました。');
    }

    /**
     * Generate a unique username from email address.
     */
    private function generateUsername(string $email): string
    {
        $baseUsername = explode('@', $email)[0];
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }
}
