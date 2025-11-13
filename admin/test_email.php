<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/email_functions.php';

requireAdminLogin();

$pageTitle = 'Test Email Configuration';
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['test_email'])) {
        $result = testEmailConfiguration();
        
        if ($result) {
            $message = 'Test email sent successfully! Check your inbox at ' . NOTIFICATION_EMAIL;
            $messageType = 'success';
        } else {
            $message = 'Failed to send test email. Please check your email configuration.';
            $messageType = 'danger';
        }
    }
}
?>

<?php include 'includes/admin_header.php'; ?>

<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Email Configuration Test</h2>
            <p class="text-muted mb-0">Test your email notification system</p>
        </div>
    </div>
    
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show">
            <i class="bi bi-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Email Configuration Status</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span>SMTP Enabled:</span>
                                <span class="<?php echo SMTP_ENABLED ? 'text-success' : 'text-warning'; ?>">
                                    <?php echo SMTP_ENABLED ? 'Yes' : 'No (Using PHP mail())'; ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span>Notification Email:</span>
                                <span class="text-info"><?php echo NOTIFICATION_EMAIL; ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span>SMTP Host:</span>
                                <span class="text-muted"><?php echo SMTP_HOST; ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span>SMTP Port:</span>
                                <span class="text-muted"><?php echo SMTP_PORT; ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span>SMTP Username:</span>
                                <span class="text-muted"><?php echo SMTP_USERNAME; ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span>SMTP Password:</span>
                                <span class="text-muted"><?php echo !empty(SMTP_PASSWORD) && SMTP_PASSWORD !== 'your-app-password' ? 'Configured' : 'Not Set'; ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <form method="POST">
                        <div class="d-grid">
                            <button type="submit" name="test_email" class="btn btn-primary btn-lg">
                                <i class="bi bi-envelope-check me-2"></i>Send Test Email
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-4">
                        <div class="alert alert-info">
                            <h6>Email Configuration Instructions:</h6>
                            <ol class="mb-0">
                                <li>Update SMTP_PASSWORD in <code>includes/email_functions.php</code> with your Gmail app password</li>
                                <li>Make sure SMTP_USERNAME is set to your Gmail address</li>
                                <li>For Gmail, you need to enable 2-factor authentication and create an app password</li>
                                <li>Test the configuration using the button above</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="bookings.php" class="btn btn-outline-primary">
                            <i class="bi bi-calendar-check me-2"></i>View Bookings
                        </a>
                        <a href="leads.php" class="btn btn-outline-info">
                            <i class="bi bi-person-lines-fill me-2"></i>View Leads
                        </a>
                        <a href="settings.php" class="btn btn-outline-secondary">
                            <i class="bi bi-gear me-2"></i>Settings
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Email Triggers</h5>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        Emails are automatically sent when:
                        <ul class="mt-2 mb-0">
                            <li>New booking is created</li>
                            <li>New inquiry is submitted</li>
                            <li>Contact form is submitted</li>
                        </ul>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>