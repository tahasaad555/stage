<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = AdminNotification::where('admin_id', Auth::id())
            ->when($request->unread_only, function($query) {
                return $query->whereNull('read_at');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'notifications' => $notifications->items(),
            'unread_count' => AdminNotification::where('admin_id', Auth::id())
                ->whereNull('read_at')->count(),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'total' => $notifications->total()
            ]
        ]);
    }

    public function markAsRead(Request $request, $id = null)
    {
        if ($id) {
            // Mark specific notification as read
            $notification = AdminNotification::where('admin_id', Auth::id())
                ->findOrFail($id);
            $notification->update(['read_at' => now()]);
            
            return response()->json([
                'message' => 'Notification marked as read'
            ]);
        } else {
            // Mark all notifications as read
            AdminNotification::where('admin_id', Auth::id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
            
            return response()->json([
                'message' => 'All notifications marked as read'
            ]);
        }
    }

    public function destroy($id)
    {
        $notification = AdminNotification::where('admin_id', Auth::id())
            ->findOrFail($id);
        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted'
        ]);
    }

    public function getStats()
    {
        $adminId = Auth::id();
        
        return response()->json([
            'total' => AdminNotification::where('admin_id', $adminId)->count(),
            'unread' => AdminNotification::where('admin_id', $adminId)
                ->whereNull('read_at')->count(),
            'today' => AdminNotification::where('admin_id', $adminId)
                ->whereDate('created_at', today())->count(),
            'this_week' => AdminNotification::where('admin_id', $adminId)
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count()
        ]);
    }
}

// app/Models/AdminNotification.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'type',
        'title',
        'message',
        'data',
        'action_url',
        'icon',
        'color',
        'read_at',
        'priority'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    public function isRead()
    {
        return !is_null($this->read_at);
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Helper method to create notifications
    public static function create_notification($adminId, $type, $title, $message, $data = [], $actionUrl = null)
    {
        $iconColors = [
            'user_registered' => ['icon' => 'fas fa-user-plus', 'color' => 'blue'],
            'payment_received' => ['icon' => 'fas fa-credit-card', 'color' => 'green'],
            'listing_pending' => ['icon' => 'fas fa-exclamation-triangle', 'color' => 'yellow'],
            'system_alert' => ['icon' => 'fas fa-bell', 'color' => 'red'],
            'land_approved' => ['icon' => 'fas fa-check-circle', 'color' => 'green'],
            'transaction_completed' => ['icon' => 'fas fa-money-bill-wave', 'color' => 'green'],
        ];

        $config = $iconColors[$type] ?? ['icon' => 'fas fa-info-circle', 'color' => 'blue'];

        return self::create([
            'admin_id' => $adminId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'action_url' => $actionUrl,
            'icon' => $config['icon'],
            'color' => $config['color'],
            'priority' => in_array($type, ['system_alert', 'payment_received']) ? 'high' : 'normal'
        ]);
    }
}

// app/Services/NotificationService.php
namespace App\Services;

use App\Models\AdminNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function notifyAllAdmins($type, $title, $message, $data = [], $actionUrl = null)
    {
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            AdminNotification::create_notification(
                $admin->id,
                $type,
                $title,
                $message,
                $data,
                $actionUrl
            );
        }
        
        Log::info("Notification sent to {$admins->count()} admins", [
            'type' => $type,
            'title' => $title
        ]);
    }
    
    public function notifyAdmin($adminId, $type, $title, $message, $data = [], $actionUrl = null)
    {
        return AdminNotification::create_notification(
            $adminId,
            $type,
            $title,
            $message,
            $data,
            $actionUrl
        );
    }
}

// Migration for admin_notifications table
// database/migrations/create_admin_notifications_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->string('type');
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();
            $table->string('action_url')->nullable();
            $table->string('icon')->default('fas fa-info-circle');
            $table->string('color')->default('blue');
            $table->enum('priority', ['low', 'normal', 'high'])->default('normal');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['admin_id', 'read_at']);
            $table->index(['admin_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_notifications');
    }
}