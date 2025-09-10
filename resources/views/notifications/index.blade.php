@extends('layouts.app')

@section('title', 'การแจ้งเตือน')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="page-title">
                            <i class="fas fa-bell text-primary"></i> การแจ้งเตือน
                        </h2>
                        <p class="page-subtitle text-muted">ดูการแจ้งเตือนทั้งหมดของคุณ</p>
                    </div>
                    <div class="header-actions">
                        <button id="markAllRead" class="btn btn-outline-primary me-2">
                            <i class="fas fa-check-double"></i> อ่านทั้งหมด
                        </button>
                        <a href="{{ url('/') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> กลับ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="row">
        <div class="col-12">
            <div class="content-section">
                @if($notifications->count() > 0)
                    <div class="notifications-list">
                        @foreach($notifications as $notification)
                            <div class="notification-item {{ $notification->is_read ? 'read' : 'unread' }}" 
                                 data-id="{{ $notification->id }}">
                                <div class="notification-icon">
                                    @if($notification->type === 'approval')
                                        <i class="fas fa-check-circle text-success"></i>
                                    @elseif($notification->type === 'rejection')
                                        <i class="fas fa-times-circle text-danger"></i>
                                    @elseif($notification->type === 'new_portfolio')
                                        <i class="fas fa-plus-circle text-warning"></i>
                                    @else
                                        <i class="fas fa-info-circle text-info"></i>
                                    @endif
                                </div>
                                
                                <div class="notification-content">
                                    <div class="notification-header">
                                        <h6 class="notification-title">{{ $notification->title }}</h6>
                                        <div class="notification-actions">
                                            @if(!$notification->is_read)
                                                <button class="btn btn-sm btn-outline-primary mark-read-btn" 
                                                        data-id="{{ $notification->id }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-outline-danger delete-btn" 
                                                    data-id="{{ $notification->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <p class="notification-message">{{ $notification->message }}</p>
                                    
                                    <div class="notification-footer">
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> 
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                        
                                        @if($notification->action_url)
                                            <a href="{{ $notification->action_url }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> ดูรายละเอียด
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-bell-slash"></i>
                        </div>
                        <h5>ไม่มีการแจ้งเตือน</h5>
                        <p>คุณยังไม่มีการแจ้งเตือนใดๆ</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Page Header */
.page-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 2rem;
    border-radius: 1rem;
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    font-size: 1.1rem;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 0.5rem;
}

/* Content Section */
.content-section {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

/* Notifications List */
.notifications-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.notification-item {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    border: 1px solid #dee2e6;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
    background: white;
}

.notification-item:hover {
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
}

.notification-item.unread {
    background: #f8f9fa;
    border-left: 4px solid #0d6efd;
}

.notification-item.read {
    opacity: 0.7;
}

.notification-icon {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #f8f9fa;
}

.notification-icon i {
    font-size: 1.5rem;
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.5rem;
}

.notification-title {
    font-weight: 600;
    color: #2d3748;
    margin: 0;
    font-size: 1.1rem;
}

.notification-actions {
    display: flex;
    gap: 0.5rem;
}

.notification-message {
    color: #6c757d;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.notification-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    margin-bottom: 1.5rem;
}

.empty-icon i {
    font-size: 4rem;
    color: #dee2e6;
}

.empty-state h5 {
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #adb5bd;
    margin-bottom: 1.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .page-header {
        padding: 1.5rem;
    }
    
    .content-section {
        padding: 1.5rem;
    }
    
    .notification-item {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .notification-footer {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }
    
    .header-actions {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark as read
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notificationId = this.dataset.id;
            markAsRead(notificationId);
        });
    });

    // Delete notification
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notificationId = this.dataset.id;
            if (confirm('คุณต้องการลบการแจ้งเตือนนี้หรือไม่?')) {
                deleteNotification(notificationId);
            }
        });
    });

    // Mark all as read
    document.getElementById('markAllRead').addEventListener('click', function() {
        if (confirm('คุณต้องการทำเครื่องหมายทั้งหมดว่าอ่านแล้วหรือไม่?')) {
            markAllAsRead();
        }
    });
});

function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
            notificationItem.classList.remove('unread');
            notificationItem.classList.add('read');
            
            // Remove mark-read button
            const markReadBtn = notificationItem.querySelector('.mark-read-btn');
            if (markReadBtn) {
                markReadBtn.remove();
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('เกิดข้อผิดพลาดในการทำเครื่องหมายว่าอ่านแล้ว');
    });
}

function deleteNotification(notificationId) {
    fetch(`/notifications/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
            notificationItem.remove();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('เกิดข้อผิดพลาดในการลบการแจ้งเตือน');
    });
}

function markAllAsRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload page to show updated state
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('เกิดข้อผิดพลาดในการทำเครื่องหมายทั้งหมดว่าอ่านแล้ว');
    });
}
</script>
@endsection
