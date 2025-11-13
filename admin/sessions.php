<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

$pageTitle = 'Manage Spa Sessions';
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $name = sanitizeInput($_POST['name'] ?? '');
        $therapyTime = sanitizeInput($_POST['therapy_time'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $description = sanitizeInput($_POST['description'] ?? '');
        $status = $_POST['status'] ?? 'active';

        if (empty($name) || empty($therapyTime) || $price <= 0) {
            $message = 'Please fill all required fields';
            $messageType = 'danger';
        } else {
            $db = getDB();

            try {
                $imagePath = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadResult = uploadImage($_FILES['image'], 'sessions');
                    if ($uploadResult['success']) {
                        $imagePath = $uploadResult['path'];
                    }
                }

                if ($action === 'add') {
                    $stmt = $db->prepare("INSERT INTO spa_sessions (name, image, therapy_time, price, description, status) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $imagePath, $therapyTime, $price, $description, $status]);
                    $message = 'Session added successfully!';
                } else {
                    $sessionId = (int)$_POST['session_id'];

                    if ($imagePath) {
                        $oldSession = $db->prepare("SELECT image FROM spa_sessions WHERE id = ?");
                        $oldSession->execute([$sessionId]);
                        $old = $oldSession->fetch();
                        if ($old && $old['image']) {
                            deleteImage($old['image']);
                        }
                    }

                    $stmt = $db->prepare("UPDATE spa_sessions SET name = ?, therapy_time = ?, price = ?, description = ?, status = ?" . ($imagePath ? ", image = ?" : "") . " WHERE id = ?");
                    $params = [$name, $therapyTime, $price, $description, $status];
                    if ($imagePath) $params[] = $imagePath;
                    $params[] = $sessionId;
                    $stmt->execute($params);
                    $message = 'Session updated successfully!';
                }
                $messageType = 'success';
            } catch (Exception $e) {
                $message = 'Error: ' . $e->getMessage();
                $messageType = 'danger';
            }
        }
    } elseif ($action === 'delete') {
        $sessionId = (int)$_POST['session_id'];

        $db = getDB();
        try {
            $stmt = $db->prepare("SELECT image FROM spa_sessions WHERE id = ?");
            $stmt->execute([$sessionId]);
            $session = $stmt->fetch();

            if ($session && $session['image']) {
                deleteImage($session['image']);
            }

            $stmt = $db->prepare("DELETE FROM spa_sessions WHERE id = ?");
            $stmt->execute([$sessionId]);
            $message = 'Session deleted successfully!';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Error deleting session: ' . $e->getMessage();
            $messageType = 'danger';
        }
    }
}

$sessions = getAllSessions('all');
?>

<?php include 'includes/admin_header.php'; ?>

<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Manage Spa Sessions</h2>
            <p class="text-muted mb-0">Add, edit, and manage spa session packages</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sessionModal">
            <i class="bi bi-plus-lg me-2"></i>Add New Session
        </button>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show">
            <i class="bi bi-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php if (empty($sessions)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-calendar-check display-4 text-muted"></i>
                    <h5 class="text-muted mt-3">No sessions found</h5>
                    <p class="text-muted">Click "Add New Session" to get started.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Image</th>
                                <th>Session Name</th>
                                <th>Time</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sessions as $session): ?>
                                <tr>
                                    <td>
                                        <?php if ($session['image']): ?>
                                            <img src="<?php echo UPLOAD_URL . $session['image']; ?>"
                                                 alt="<?php echo htmlspecialchars($session['name']); ?>"
                                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                        <?php else: ?>
                                            <div class="d-flex align-items-center justify-content-center bg-secondary text-white rounded"
                                                 style="width: 60px; height: 60px;">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($session['name']); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?php echo htmlspecialchars($session['therapy_time']); ?></span>
                                    </td>
                                    <td>
                                        <strong class="text-primary">₹<?php echo number_format($session['price'], 0); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $session['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($session['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?php echo timeAgo($session['created_at']); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="editSession(<?php echo $session['id']; ?>)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deleteSession(<?php echo $session['id']; ?>, '<?php echo htmlspecialchars($session['name']); ?>')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add/Edit Session Modal -->
<div class="modal fade" id="sessionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="sessionModalTitle">Add New Session</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="sessionForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" id="formAction" value="add">
                    <input type="hidden" name="session_id" id="sessionId">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Session Name *</label>
                        <input type="text" class="form-control" name="name" id="sessionName" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Therapy Time *</label>
                            <input type="text" class="form-control" name="therapy_time" id="therapyTime" placeholder="e.g., 60 minutes" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Price *</label>
                            <input type="number" class="form-control" name="price" id="sessionPrice" min="0" step="0.01" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea class="form-control" name="description" id="sessionDescription" rows="4" placeholder="Describe the session..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Session Image</label>
                        <input type="file" class="form-control" name="image" id="sessionImage" accept="image/*">
                        <small class="form-text text-muted">Upload session image (JPG, PNG, WebP)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select class="form-control" name="status" id="sessionStatus">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>Save Session
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="bi bi-exclamation-triangle display-4 text-danger mb-3"></i>
                    <p>Are you sure you want to delete <strong id="deleteSessionName"></strong>?</p>
                    <p class="text-danger">This action cannot be undone.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form style="display: inline;" method="POST">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="session_id" id="deleteSessionId">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$extraScripts = '<script>
    function editSession(id) {
        fetch("get_session_data.php?id=" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("sessionModalTitle").textContent = "Edit Session";
                    document.getElementById("formAction").value = "edit";
                    document.getElementById("sessionId").value = id;
                    document.getElementById("sessionName").value = data.session.name;
                    document.getElementById("therapyTime").value = data.session.therapy_time;
                    document.getElementById("sessionPrice").value = data.session.price;
                    document.getElementById("sessionDescription").value = data.session.description || "";
                    document.getElementById("sessionStatus").value = data.session.status;

                    new bootstrap.Modal(document.getElementById("sessionModal")).show();
                } else {
                    alert("Error loading session data");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Error loading session data");
            });
    }

    function deleteSession(id, name) {
        document.getElementById("deleteSessionId").value = id;
        document.getElementById("deleteSessionName").textContent = name;
        new bootstrap.Modal(document.getElementById("deleteModal")).show();
    }

    document.getElementById("sessionModal").addEventListener("hidden.bs.modal", function() {
        document.getElementById("sessionForm").reset();
        document.getElementById("sessionModalTitle").textContent = "Add New Session";
        document.getElementById("formAction").value = "add";
        document.getElementById("sessionId").value = "";
    });
</script>';

include 'includes/admin_footer.php';
?>
