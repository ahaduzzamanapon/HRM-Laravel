<style>
    .nav{
        padding: 0px;
    }
</style>
<nav class="nav navbar navbar-expand-xl navbar-light iq-navbar"> 
    <div class="container-fluid navbar-inner">

        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20px" class="icon-20" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
                </svg>
            </i>
        </div>
        <!-- Navbar Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <span class="mt-2 navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
            </span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="mb-2 navbar-nav ms-auto align-items-center navbar-list mb-lg-0">
                @php
                    $user = Auth::user();
                    $isEmployee = $user ? (\App\Services\AuthorizationEngine::isEmployeeRole($user) || (int)($user->role_id ?? 0) === 3 || (int)($user->group_id ?? 0) === 3) : false;

                    $recentNotifications = collect();
                    $unreadCount = 0;

                    if ($user) {
                        $rawNotifications = $user->notifications()->take(20)->get();
                        $unreadNotifications = $user->unreadNotifications;

                        if ($isEmployee) {
                            // Employee end: filter out generic HR "New Loan Application" alerts
                            $unreadNotifications = $unreadNotifications->reject(function($n) {
                                $d = $n->data;
                                return isset($d['type']) && $d['type'] === 'loan_application' && ($d['title'] ?? '') === 'New Loan Application';
                            });
                            $filteredDbNotifs = $rawNotifications->reject(function($n) {
                                $d = $n->data;
                                return isset($d['type']) && $d['type'] === 'loan_application' && ($d['title'] ?? '') === 'New Loan Application';
                            });
                        } else {
                            $filteredDbNotifs = $rawNotifications;
                        }

                        $unreadCount = $unreadNotifications->count();

                        $loanIds = [];
                        $leaveIds = [];

                        foreach ($filteredDbNotifs as $dbNotif) {
                            $d = $dbNotif->data;
                            if (!empty($d['loan_id'])) {
                                $loanIds[] = $d['loan_id'];
                            }
                            if (!empty($d['leave_application_id'])) {
                                $leaveIds[] = $d['leave_application_id'];
                            }
                        }

                        $loansMap = !empty($loanIds) ? \App\Models\Loan::whereIn('id', array_unique($loanIds))->pluck('status', 'id') : collect();
                        $leavesMap = !empty($leaveIds) ? \App\Models\LeaveApplication::whereIn('id', array_unique($leaveIds))->pluck('status', 'id') : collect();

                        foreach ($filteredDbNotifs as $dbNotif) {
                            $d = $dbNotif->data;
                            $currentStatus = $d['status'] ?? null;

                            if (!empty($d['loan_id']) && isset($loansMap[$d['loan_id']])) {
                                $currentStatus = $loansMap[$d['loan_id']];
                            } elseif (!empty($d['leave_application_id']) && isset($leavesMap[$d['leave_application_id']])) {
                                $currentStatus = $leavesMap[$d['leave_application_id']];
                            }

                            $recentNotifications->push([
                                'id' => $dbNotif->id,
                                'is_unread' => is_null($dbNotif->read_at),
                                'type' => $d['type'] ?? 'general',
                                'title' => $d['title'] ?? 'Notification',
                                'status' => $currentStatus,
                                'applicant_name' => $d['applicant_name'] ?? ($d['user_name'] ?? 'Employee'),
                                'message' => $d['message'] ?? 'Notification received',
                                'url' => route('notifications.read', $dbNotif->id),
                                'created_at' => $dbNotif->created_at,
                            ]);
                        }
                    }
                @endphp
                <!-- Notification Dropdown -->
                <li class="nav-item dropdown me-3 position-relative">
                    <a class="nav-link position-relative p-2 text-secondary d-flex align-items-center" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                        <i class="fa fa-bell" style="font-size: 20px;color: white;border: 2px solid white;padding: 5px;border-radius: 10px 10px 10px 10px;"></i>
                        @if($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm" style="font-size: 10px; padding: 3px 6px; margin-top: 12px; margin-left: -9px;">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                <span class="visually-hidden">unread notifications</span>
                            </span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-0" aria-labelledby="notificationDropdown" style="width: 530px; max-height: 480px; overflow-y: auto;">
                        <div class="d-flex align-items-center justify-content-between px-3 py-2-5 border-bottom text-white rounded-top" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                            <h6 class="m-0 fw-bold text-white d-flex align-items-center gap-2" style="font-size: 15px;">
                                <i class="fa fa-bell me-1" style="font-size: 16px;"></i>
                                Notifications
                                @if($unreadCount > 0)
                                    <span class="badge bg-danger rounded-pill ms-1" style="font-size: 11px;">{{ $unreadCount }} New</span>
                                @endif
                            </h6>
                            @if($unreadCount > 0)
                                <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-light py-0 px-2 text-primary fw-semibold shadow-sm" style="font-size: 11px; border-radius: 12px;">Mark all read</button>
                                </form>
                            @endif
                        </div>
                        <div class="notification-list">
                            @forelse($recentNotifications as $notif)
                                @php
                                    $isUnread = $notif['is_unread'] ?? false;
                                    $title = $notif['title'] ?? 'Notification';
                                    $applicantName = $notif['applicant_name'] ?? 'Employee';
                                    $msg = $notif['message'] ?? 'Notification received';
                                    $status = $notif['status'] ?? null;
                                    $url = $notif['url'] ?? '#';
                                    $createdAt = \Carbon\Carbon::parse($notif['created_at']);

                                    $iconColor = 'bg-primary text-primary';
                                    $statusBadgeClass = 'bg-secondary';
                                    $iconClass = (isset($notif['type']) && $notif['type'] === 'loan_application') ? 'im im-icon-Coins' : 'im im-icon-Calendar-4';

                                    if (in_array($status, ['Approved', 'Disbursed', 'Completed', 'Repaid'])) {
                                        $iconColor = 'bg-success text-success';
                                        $statusBadgeClass = 'bg-success';
                                    } elseif (in_array($status, ['First Level Approved', 'Active Repayment'])) {
                                        $iconColor = 'bg-info text-info';
                                        $statusBadgeClass = 'bg-info text-dark';
                                    } elseif ($status === 'Rejected') {
                                        $iconColor = 'bg-danger text-danger';
                                        $statusBadgeClass = 'bg-danger';
                                    } elseif ($status === 'Amount Modified') {
                                        $iconColor = 'bg-warning text-warning';
                                        $statusBadgeClass = 'bg-warning text-dark';
                                    } elseif (isset($notif['type']) && in_array($notif['type'], ['leave_application', 'loan_application'])) {
                                        $statusBadgeClass = 'bg-warning text-dark';
                                    }
                                @endphp
                                <a href="{{ $url }}" class="dropdown-item py-3 px-3 border-bottom d-flex align-items-start gap-3 text-wrap {{ $isUnread ? 'bg-light' : '' }}" style="transition: background-color 0.2s;">
                                    <div class="rounded-circle {{ $iconColor }} bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 38px; height: 38px;">
                                        <i class="{{ $iconClass }}" style="font-size: 18px;"></i>
                                    </div>
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="fw-bold text-dark d-block" style="font-size: 13.5px;">
                                                {{ $title }}
                                                @if($status)
                                                    <span class="badge {{ $statusBadgeClass }} ms-1" style="font-size: 10px; font-weight: 500;">{{ $status }}</span>
                                                @endif
                                            </span>
                                            <small class="text-muted flex-shrink-0 ms-2" style="font-size: 11px;">{{ $createdAt->diffForHumans(null, true, true) }}</small>
                                        </div>
                                        <p class="mb-0 text-secondary" style="font-size: 12.5px; line-height: 1.4;">{{ $msg }}</p>
                                    </div>
                                    @if($isUnread)
                                        <span class="rounded-circle bg-danger flex-shrink-0 mt-2" style="width: 9px; height: 9px;" title="Unread"></span>
                                    @endif
                                </a>
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-2 opacity-50"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                                    <p class="mb-0 small">No notifications yet</p>
                                </div>
                            @endforelse
                        </div>
                        @if($recentNotifications->count() > 0)
                            <div class="p-2-5 text-center bg-light border-top rounded-bottom d-flex justify-content-center gap-3">
                                <a href="{{ route('leaveApplications.index') }}" class="small text-primary text-decoration-none fw-bold" style="font-size: 12.5px;">View Leaves →</a>
                                <a href="{{ route('employeeLoans.index') }}" class="small text-primary text-decoration-none fw-bold" style="font-size: 12.5px;">View Loans →</a>
                            </div>
                        @endif
                    </div>
                </li>

                <!-- User Profile Dropdown -->
                <li class="nav-item dropdown custom-drop">
                    <a class="py-0 nav-link d-flex align-items-center" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('assets/images/avatars/01.png') }}" alt="User-Profile"
                            class="theme-color-default-img img-fluid avatar avatar-20 avatar-rounded" />
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>
</nav>