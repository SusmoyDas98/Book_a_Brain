<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Hire\HireController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\JobResponseController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Page_Redirection_Controller;
use App\Http\Controllers\Payment\AdminPaymentController;
use App\Http\Controllers\Payment\BkashPortalController;
use App\Http\Controllers\Payment\GuardianPaymentController;
use App\Http\Controllers\Payment\TutorPaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TuitionContractController;
use App\Http\Controllers\VerificationController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsGuardian;
use App\Http\Middleware\IsTutor;
use App\Http\Middleware\ValidUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing_page');
})->name('landing_page');

Route::view('/login_or_signup', 'login_signup_page')->name('login_or_signup_page_redirect');

Route::get('/google/redirect', [GoogleController::class, 'redirectToGoogle']);
Route::get('/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::resource('auth', AuthController::class)->only(['store', 'index', 'create', 'show', 'edit', 'update', 'destroy']);

// updates of job response
Route::patch('update_response', [JobResponseController::class, 'update'])->name('update_response.update');

// ------ Susmoy -----

Route::middleware(['auth', ValidUser::class])->group(function () {
    Route::get('/select_role_page', [Page_Redirection_Controller::class, 'select_role_page'])
        ->name('select_role_redirect');

    Route::get('/tutor_profile/{id}', [Page_Redirection_Controller::class, 'tutor_profile_page'])
        ->name('tutor_profile_redirect')
        ->middleware(ValidUser::class);

    Route::get('/tutor_message/{id}', [Page_Redirection_Controller::class, 'tutor_message_page'])->name('tutor_message_redirect');

    Route::get('/post_response', [Page_Redirection_Controller::class, 'post_response_page'])->name('post_response_redirect');
    Route::get('/tutor_message/{id}', [Page_Redirection_Controller::class, 'tutor_message_page'])
        ->name('tutor_message_redirect');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/select-role', [ProfileController::class, 'saveRole'])->name('role.save');
    Route::post('/profile/confirm', [ProfileController::class, 'confirmProfile'])->name('profile.confirm');

    // ── Notification routes (shared, accessible by all roles) ──
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read.all');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread.count');

    // Guardian routes
    Route::middleware([IsGuardian::class])->group(function () {
        Route::get('/tutor_search', [Page_Redirection_Controller::class, 'tutor_search_page'])
            ->name('tutor_search_redirect');
        Route::get('/contracts', [TuitionContractController::class, 'guardianIndex'])->name('contracts.guardian');
        Route::get('/contracts/hire', [TuitionContractController::class, 'create'])->name('contracts.create');
        Route::post('/contracts', [TuitionContractController::class, 'store'])->name('contracts.store');
        // ---- Feature 4: Job Posts (Guardian) ----
        Route::get('/job-posts', [JobPostController::class, 'index'])->name('job_posts.index');
        Route::get('/job-posts/create', [JobPostController::class, 'create'])->name('job_posts.create');
        Route::post('/job-posts', [JobPostController::class, 'store'])->name('job_posts.store');
        Route::get('/job-posts/{jobPost}', [JobPostController::class, 'show'])->name('job_posts.show');
        Route::get('/job-posts/{jobPost}/edit', [JobPostController::class, 'edit'])->name('job_posts.edit');
        Route::put('/job-posts/{jobPost}', [JobPostController::class, 'update'])->name('job_posts.update');
        Route::delete('/job-posts/{jobPost}', [JobPostController::class, 'destroy'])->name('job_posts.destroy');
        Route::post('/job-posts/{jobPost}/shortlist', [JobPostController::class, 'shortlist'])->name('job_posts.shortlist');
        Route::post('/job-posts/{jobPost}/remove-shortlist', [JobPostController::class, 'removeShortlist'])->name('job_posts.remove_shortlist');
        Route::post('/job-posts/{jobPost}/reject', [JobPostController::class, 'rejectApplicant'])->name('job_posts.reject');
        Route::get('/guardian/payment', [GuardianPaymentController::class, 'index'])->name('guardian.payment.index');
        Route::post('/guardian/payment/{tuitionPayment}/initiate', [GuardianPaymentController::class, 'initiatePayment'])->name('guardian.payment.initiate');
        Route::get('/guardian/subscribe', [GuardianPaymentController::class, 'showPlan'])->name('guardian.subscribe.plan');
        Route::post('/guardian/subscribe/confirm', [GuardianPaymentController::class, 'confirmPlan'])->name('guardian.subscribe.confirm');

        // ---- Feature 10: Hire System (Guardian) ----
        Route::post('/guardian/hire/{applicationId}', [HireController::class, 'hire'])->name('guardian.hire');
        Route::post('/guardian/hire/cancel/{hireConfirmationId}', [HireController::class, 'requestCancellation'])->name('guardian.hire.cancel');
    });

    // Tutor routes
    Route::middleware([IsTutor::class])->group(function () {
        Route::get('/contracts/tutor', [TuitionContractController::class, 'tutorIndex'])->name('contracts.tutor');
        Route::post('/contracts/{contract}/accept', [TuitionContractController::class, 'accept'])->name('contracts.accept');
        Route::post('/contracts/{contract}/decline', [TuitionContractController::class, 'decline'])->name('contracts.decline');

        // ---- Feature 8: Applications (Tutor) ----
        Route::get('/browse-jobs', [JobApplicationController::class, 'browse'])->name('jobs.browse');
        Route::post('/jobs/{jobPost}/apply', [JobApplicationController::class, 'apply'])->name('jobs.apply');
        Route::get('/my-applications', [JobApplicationController::class, 'index'])->name('applications.index');
        Route::delete('/applications/{response}/withdraw', [JobApplicationController::class, 'withdraw'])->name('applications.withdraw');
    });

    // Admin Routes
    Route::middleware([IsAdmin::class])->group(function () {
        Route::post('/admin/verify/{tutorId}/approve', [VerificationController::class, 'approve'])->name('admin.verify.approve');
        Route::post('/admin/verify/{tutorId}/reject', [VerificationController::class, 'reject'])->name('admin.verify.reject');
        Route::get('/dashboard', function () {
            return 'Dashboard coming soon.';
        })->name('dashboard');
        Route::get('/admin/tutors', function () {
            return view('admin.tutors');
        })->name('admin.tutors');
        Route::get('/admin/guardians', function () {
            return view('admin.guardians');
        })->name('admin.guardians');
        Route::get('/admin/contracts', function () {
            return view('admin.contracts');
        })->name('admin.contracts');
        Route::post('/admin/contracts/{contract}/payment', function (\App\Models\TuitionContract $contract, \Illuminate\Http\Request $request) {
            $contract->update(['is_paid' => $request->is_paid]);

            return back()->with('success', 'Payment status updated.');
        })->name('admin.contract.payment');

        // ---- Feature 16: Admin Complaint Management ----
        Route::get('/admin/complaints', [ComplaintController::class, 'index'])->name('admin.complaints');
        Route::get('/admin/complaints/{complaint}', [ComplaintController::class, 'show'])->name('admin.complaints.show');
        Route::patch('/admin/complaints/{complaint}', [ComplaintController::class, 'resolve'])->name('admin.complaints.resolve');

        // ---- Feature 17: Analytics & Reporting ----
        Route::get('/admin/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics');
        Route::get('/admin/analytics/export', [AnalyticsController::class, 'exportCsv'])->name('admin.analytics.export');
    });

    // Shared
    Route::get('/contracts/{contract}', [TuitionContractController::class, 'show'])->name('contracts.show');
    Route::post('/contracts/{contract}/log', [TuitionContractController::class, 'logSession'])->name('contracts.log');
    Route::post('/contracts/{contract}/end', [TuitionContractController::class, 'end'])->name('contracts.end');
    Route::post('/contracts/{contract}/notes', [TuitionContractController::class, 'updateNotes'])->name('contracts.notes');
    Route::post('/session-logs/{sessionLog}/note', [TuitionContractController::class, 'guardianNote'])->name('contracts.guardian_note');

    // ---- Feature 11: In-App Messaging ----
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/contracts/{contract}/chat', [MessageController::class, 'startConversation'])->name('messages.start');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{conversation}/poll', [MessageController::class, 'fetchNew'])->name('messages.poll');

    // ---- Feature 15: Rating & Review ----
    Route::get('/contracts/{contract}/review', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/contracts/{contract}/review', [ReviewController::class, 'store'])->name('reviews.store');

    // ---- Feature 16: Complaint Filing (any user) ----
    Route::get('/my-complaints', [ComplaintController::class, 'myComplaints'])->name('complaints.my');
    Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');

    // ---- Feature 18: Calendar (Tutor) ----
    Route::middleware([IsTutor::class])->group(function () {
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
        Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
        Route::post('/calendar', [CalendarController::class, 'store'])->name('calendar.store');
        Route::patch('/calendar/{event}', [CalendarController::class, 'update'])->name('calendar.update');
        Route::delete('/calendar/{event}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
    });

    Route::get('/dashboard', function () {
        return 'Dashboard coming soon.';
    })->name('dashboard');
});

Route::get('/login', function () {
    return redirect()->route('login_or_signup_page_redirect');
})->name('login');
Route::get('/dev/profile-preview', function () {
    Auth::login(User::first());

    return redirect('/profile');
});
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
