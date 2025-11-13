<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

$pageTitle = 'Manage Therapists';
$message = '';
$messageType = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' || $action === 'edit') {
        $name = sanitizeInput($_POST['name'] ?? '');
        $priceNcr = (float)($_POST['price_ncr'] ?? 0);
        $priceOther = (float)($_POST['price_other'] ?? 0);
        $height = sanitizeInput($_POST['height'] ?? '');
        $weight = sanitizeInput($_POST['weight'] ?? '');
        $description = sanitizeInput($_POST['description'] ?? '');
        $availability = sanitizeInput($_POST['availability_slots'] ?? '');
        $status = $_POST['status'] ?? 'active';
        $services = $_POST['services'] ?? [];
        
        if (empty($name) || $priceNcr <= 0 || $priceOther <= 0) {
            $message = 'Name and valid prices for all regions are required';
            $messageType = 'danger';
        } else {
            $db = getDB();
            
            try {
                $db->beginTransaction();
                
                if ($action === 'add') {
                    // Add new therapist
                    $stmt = $db->prepare("
                        INSERT INTO therapists (name, price_ncr, price_other, height, weight, description, availability_slots, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$name, $priceNcr, $priceOther, $height, $weight, $description, $availability, $status]);
                    $therapistId = $db->lastInsertId();
                    
                } else {
                    // Edit existing therapist
                    $therapistId = (int)$_POST['therapist_id'];
                    $stmt = $db->prepare("
                        UPDATE therapists 
                        SET name = ?, price_ncr = ?, price_other = ?, height = ?, weight = ?, description = ?, availability_slots = ?, status = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([$name, $priceNcr, $priceOther, $height, $weight, $description, $availability, $status, $therapistId]);
                }
                
                // Update services
                $db->prepare("DELETE FROM therapist_services WHERE therapist_id = ?")->execute([$therapistId]);
                
                foreach ($services as $serviceId) {
                    $stmt = $db->prepare("INSERT INTO therapist_services (therapist_id, service_id) VALUES (?, ?)");
                    $stmt->execute([$therapistId, $serviceId]);
                }
                
                // Handle image upload
                if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
                    $uploadResult = uploadImage($_FILES['main_image'], 'therapists');
                    if ($uploadResult['success']) {
                        // Delete old main image
                        $oldImage = $db->prepare("SELECT main_image FROM therapists WHERE id = ?")->execute([$therapistId]);
                        $oldImage = $db->prepare("SELECT main_image FROM therapists WHERE id = ?")->fetch();
                        if ($oldImage && $oldImage['main_image']) {
                            deleteImage('therapists/' . $oldImage['main_image']);
                        }
                        
                        // Update main image
                        $stmt = $db->prepare("UPDATE therapists SET main_image = ? WHERE id = ?");
                        $stmt->execute([$uploadResult['filename'], $therapistId]);
                        
                        // Add to therapist_images
                        $stmt = $db->prepare("
                            INSERT INTO therapist_images (therapist_id, image_path, is_main) 
                            VALUES (?, ?, 1)
                            ON DUPLICATE KEY UPDATE image_path = VALUES(image_path)
                        ");
                        $stmt->execute([$therapistId, 'therapists/' . $uploadResult['filename']]);
                    }
                }
                
                // Handle gallery images
                if (isset($_FILES['gallery_images'])) {
                    foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmpName) {
                        if ($_FILES['gallery_images']['error'][$key] === UPLOAD_ERR_OK) {
                            $file = [
                                'name' => $_FILES['gallery_images']['name'][$key],
                                'tmp_name' => $tmpName,
                                'size' => $_FILES['gallery_images']['size'][$key],
                                'error' => $_FILES['gallery_images']['error'][$key]
                            ];
                            
                            $uploadResult = uploadImage($file, 'therapists');
                            if ($uploadResult['success']) {
                                $stmt = $db->prepare("
                                    INSERT INTO therapist_images (therapist_id, image_path, is_main) 
                                    VALUES (?, ?, 0)
                                ");
                                $stmt->execute([$therapistId, 'therapists/' . $uploadResult['filename']]);
                            }
                        }
                    }
                }
                
                $db->commit();
                $message = $action === 'add' ? 'Therapist added successfully!' : 'Therapist updated successfully!';
                $messageType = 'success';
                
            } catch (Exception $e) {
                $db->rollback();
                $message = 'Error: ' . $e->getMessage();
                $messageType = 'danger';
            }
        }
    } elseif ($action === 'delete') {
        $therapistId = (int)$_POST['therapist_id'];
        
        $db = getDB();
        try {
            // Get images to delete
            $images = getTherapistImages($therapistId);
            foreach ($images as $image) {
                deleteImage($image['image_path']);
            }
            
            // Delete therapist (cascades to images and services)
            $stmt = $db->prepare("DELETE FROM therapists WHERE id = ?");
            $stmt->execute([$therapistId]);
            
            $message = 'Therapist deleted successfully!';
            $messageType = 'success';
            
        } catch (Exception $e) {
            $message = 'Error deleting therapist: ' . $e->getMessage();
            $messageType = 'danger';
        }
    }
}

// Get all therapists
$therapists = getAllTherapists('all'); // Get both active and inactive
$services = getAllServices();
?>

<?php include 'includes/admin_header.php'; ?>

<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Manage Therapists</h2>
            <p class="text-muted mb-0">Add, edit, and manage therapist profiles</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#therapistModal">
            <i class="bi bi-plus-lg me-2"></i>Add New Therapist
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
            <?php if (empty($therapists)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-person-plus display-4 text-muted"></i>
                    <h5 class="text-muted mt-3">No therapists found</h5>
                    <p class="text-muted">Click "Add New Therapist" to get started.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>NCR ₹</th>
                                <th>Other ₹</th>
                                <th>Services</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($therapists as $therapist): 
                                $therapistServices = getTherapistServices($therapist['id']);
                                $images = getTherapistImages($therapist['id']);
                                $mainImage = !empty($images) ? UPLOAD_URL . $images[0]['image_path'] : 'https://images.pexels.com/photos/3757942/pexels-photo-3757942.jpeg?auto=compress&cs=tinysrgb&w=100';
                            ?>
                                <tr>
                                    <td><?php echo $therapist['id']; ?></td>
                                    <td>
                                        <img src="<?php echo $mainImage; ?>" 
                                             class="rounded" width="50" height="50" style="object-fit: cover;"
                                             alt="<?php echo htmlspecialchars($therapist['name']); ?>">
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($therapist['name']); ?></strong><br>
                                        <small class="text-muted">
                                            <?php echo $therapist['height'] ? 'H: ' . $therapist['height'] : ''; ?>
                                            <?php echo $therapist['weight'] ? ' W: ' . $therapist['weight'] : ''; ?>
                                        </small>
                                    </td>
                                    <td><?php echo formatPrice($therapist['price_ncr'] ?? $therapist['price_per_session']); ?></td>
                                    <td><?php echo formatPrice($therapist['price_other'] ?? $therapist['price_per_session']); ?></td>
                                    <td>
                                        <?php foreach (array_slice($therapistServices, 0, 2) as $service): ?>
                                            <span class="badge bg-light text-dark me-1"><?php echo htmlspecialchars($service['name']); ?></span>
                                        <?php endforeach; ?>
                                        <?php if (count($therapistServices) > 2): ?>
                                            <span class="badge bg-secondary">+<?php echo count($therapistServices) - 2; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $therapist['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($therapist['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo timeAgo($therapist['created_at']); ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="editTherapist(<?php echo $therapist['id']; ?>)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deleteTherapist(<?php echo $therapist['id']; ?>, '<?php echo htmlspecialchars($therapist['name']); ?>')">
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

<!-- Add/Edit Therapist Modal -->
<div class="modal fade" id="therapistModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="therapistModalTitle">Add New Therapist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="therapistForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" id="formAction" value="add">
                    <input type="hidden" name="therapist_id" id="therapistId">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name *</label>
                            <input type="text" class="form-control" name="name" id="therapistName" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Delhi-NCR Price (₹) *</label>
                            <input type="number" class="form-control" name="price_ncr" id="therapistPriceNcr" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rest of India Price (₹) *</label>
                            <input type="number" class="form-control" name="price_other" id="therapistPriceOther" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Height</label>
                            <input type="text" class="form-control" name="height" id="therapistHeight" placeholder="e.g., 5'6"">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Weight</label>
                            <input type="text" class="form-control" name="weight" id="therapistWeight" placeholder="e.g., 55kg">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Services</label>
                            <div class="row">
                                <?php foreach ($services as $service): ?>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="services[]" 
                                                   value="<?php echo $service['id']; ?>" id="service<?php echo $service['id']; ?>">
                                            <label class="form-check-label" for="service<?php echo $service['id']; ?>">
                                                <?php echo htmlspecialchars($service['name']); ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="therapistDescription" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Availability</label>
                            <textarea class="form-control" name="availability_slots" id="therapistAvailability" rows="2" 
                                      placeholder="e.g., Mon-Fri: 9 AM - 6 PM, Sat: 10 AM - 4 PM"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status" id="therapistStatus">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Main Image</label>
                            <input type="file" class="form-control" name="main_image" accept="image/*">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Gallery Images</label>
                            <input type="file" class="form-control" name="gallery_images[]" accept="image/*" multiple>
                            <small class="form-text text-muted">You can select multiple images for the gallery.</small>
                            
                            <!-- Gallery Preview Container -->
                            <div id="galleryPreview" class="mt-3" style="display: none;">
                                <h6 class="fw-bold">Selected Images:</h6>
                                <div id="galleryPreviewContainer" class="d-flex flex-wrap gap-2"></div>
                            </div>
                            
                            <!-- Existing Images Preview (for editing) -->
                            <div id="existingImagesPreview" class="mt-3" style="display: none;">
                                <h6 class="fw-bold">Current Images:</h6>
                                <div id="existingImagesContainer" class="d-flex flex-wrap gap-2"></div>
                            </div>
                        </div>
                        
                        <!-- Main Image Preview -->
                        <div class="col-12">
                            <div id="mainImagePreview" class="mt-3" style="display: none;">
                                <h6 class="fw-bold">Main Image Preview:</h6>
                                <div class="position-relative d-inline-block">
                                    <img id="mainImagePreviewImg" src="" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Therapist</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteTherapistName"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form style="display: inline;" method="POST">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="therapist_id" id="deleteTherapistId">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
$extraScripts = '<script>
    let selectedGalleryFiles = [];
    
    // Main image preview functionality
    document.querySelector("input[name=\'main_image\']").addEventListener("change", function(e) {
        const file = e.target.files[0];
        const previewSection = document.getElementById("mainImagePreview");
        const previewImg = document.getElementById("mainImagePreviewImg");
        
        if (file && file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewSection.style.display = "block";
            };
            reader.readAsDataURL(file);
        } else {
            previewSection.style.display = "none";
        }
    });
    
    // Gallery image preview functionality
    document.querySelector("input[name=\'gallery_images[]\']").addEventListener("change", function(e) {
        const files = Array.from(e.target.files);
        selectedGalleryFiles = files;
        updateGalleryPreview();
    });
    
    function updateGalleryPreview() {
        const previewContainer = document.getElementById("galleryPreviewContainer");
        const previewSection = document.getElementById("galleryPreview");
        
        if (selectedGalleryFiles.length === 0) {
            previewSection.style.display = "none";
            return;
        }
        
        previewSection.style.display = "block";
        previewContainer.innerHTML = "";
        
        selectedGalleryFiles.forEach((file, index) => {
            if (file.type.startsWith("image/")) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewItem = document.createElement("div");
                    previewItem.className = "position-relative";
                    previewItem.style.cssText = "width: 100px; height: 100px;";
                    
                    previewItem.innerHTML = `
                        <img src="${e.target.result}" 
                             class="img-thumbnail" 
                             style="width: 100%; height: 100%; object-fit: cover;">
                        <button type="button" 
                                class="btn btn-danger btn-sm position-absolute top-0 end-0" 
                                style="width: 20px; height: 20px; padding: 0; font-size: 10px; line-height: 1;"
                                onclick="removeGalleryImage(${index})"
                                title="Remove image">
                            ×
                        </button>
                        <div class="text-center mt-1">
                            <small class="text-muted">${file.name.length > 15 ? file.name.substring(0, 12) + "..." : file.name}</small>
                        </div>
                    `;
                    
                    previewContainer.appendChild(previewItem);
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    function removeGalleryImage(index) {
        selectedGalleryFiles.splice(index, 1);
        updateFileInput();
        updateGalleryPreview();
    }
    
    function updateFileInput() {
        const fileInput = document.querySelector("input[name=\'gallery_images[]\']");
        const dt = new DataTransfer();
        
        selectedGalleryFiles.forEach(file => {
            dt.items.add(file);
        });
        
        fileInput.files = dt.files;
    }
    
    function editTherapist(id) {
        // Fetch therapist data and populate form
        fetch("get_therapist_data.php?id=" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("therapistModalTitle").textContent = "Edit Therapist";
                    document.getElementById("formAction").value = "edit";
                    document.getElementById("therapistId").value = id;
                    document.getElementById("therapistName").value = data.therapist.name;
                    document.getElementById("therapistPriceNcr").value = data.therapist.price_ncr;
                    document.getElementById("therapistPriceOther").value = data.therapist.price_other;
                    document.getElementById("therapistHeight").value = data.therapist.height || "";
                    document.getElementById("therapistWeight").value = data.therapist.weight || "";
                    document.getElementById("therapistDescription").value = data.therapist.description || "";
                    document.getElementById("therapistAvailability").value = data.therapist.availability_slots || "";
                    document.getElementById("therapistStatus").value = data.therapist.status;
                    
                    // Show main image preview if exists
                    if (data.therapist.main_image) {
                        const mainImagePreview = document.getElementById("mainImagePreview");
                        const mainImagePreviewImg = document.getElementById("mainImagePreviewImg");
                        mainImagePreviewImg.src = "' . UPLOAD_URL . 'therapists/" + data.therapist.main_image;
                        mainImagePreview.style.display = "block";
                    }
                    
                    // Show existing gallery images
                    showExistingImages(data.therapist.id);
                    
                    // Check services
                    const checkboxes = document.querySelectorAll("input[name=\'services[]\']");
                    checkboxes.forEach(cb => cb.checked = false);
                    data.services.forEach(service => {
                        const checkbox = document.getElementById("service" + service.id);
                        if (checkbox) checkbox.checked = true;
                    });
                    
                    new bootstrap.Modal(document.getElementById("therapistModal")).show();
                }
            });
    }
    
    function showExistingImages(therapistId) {
        fetch("get_therapist_images.php?id=" + therapistId)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.images.length > 0) {
                    const existingImagesSection = document.getElementById("existingImagesPreview");
                    const existingImagesContainer = document.getElementById("existingImagesContainer");
                    
                    existingImagesContainer.innerHTML = "";
                    
                    data.images.forEach(image => {
                        const imageItem = document.createElement("div");
                        imageItem.className = "position-relative";
                        imageItem.style.cssText = "width: 100px; height: 100px;";
                        
                        imageItem.innerHTML = `
                            <img src="' . UPLOAD_URL . '${image.image_path}" 
                                 class="img-thumbnail" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                            <button type="button" 
                                    class="btn btn-danger btn-sm position-absolute top-0 end-0" 
                                    style="width: 20px; height: 20px; padding: 0; font-size: 10px; line-height: 1;"
                                    onclick="removeExistingImage(${image.id})"
                                    title="Remove image">
                                ×
                            </button>
                            ${image.is_main ? \'<span class="badge bg-primary position-absolute bottom-0 start-0" style="font-size: 8px;">Main</span>\' : \'\'}
                        `;
                        
                        existingImagesContainer.appendChild(imageItem);
                    });
                    
                    existingImagesSection.style.display = "block";
                } else {
                    document.getElementById("existingImagesPreview").style.display = "none";
                }
            })
            .catch(error => {
                console.error("Error loading existing images:", error);
            });
    }
    
    function removeExistingImage(imageId) {
        if (confirm("Are you sure you want to remove this image?")) {
            fetch("remove_therapist_image.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ image_id: imageId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload existing images
                    const therapistId = document.getElementById("therapistId").value;
                    if (therapistId) {
                        showExistingImages(therapistId);
                    }
                } else {
                    alert("Error removing image: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Error removing image");
            });
        }
    }
    
    function deleteTherapist(id, name) {
        document.getElementById("deleteTherapistId").value = id;
        document.getElementById("deleteTherapistName").textContent = name;
        new bootstrap.Modal(document.getElementById("deleteModal")).show();
    }
    
    // Reset form when modal is closed
    document.getElementById("therapistModal").addEventListener("hidden.bs.modal", function() {
        document.getElementById("therapistForm").reset();
        document.getElementById("therapistModalTitle").textContent = "Add New Therapist";
        document.getElementById("formAction").value = "add";
        document.getElementById("therapistId").value = "";
        
        // Reset gallery preview
        selectedGalleryFiles = [];
        document.getElementById("galleryPreview").style.display = "none";
        document.getElementById("galleryPreviewContainer").innerHTML = "";
        
        // Reset main image preview
        document.getElementById("mainImagePreview").style.display = "none";
        
        // Reset existing images preview
        document.getElementById("existingImagesPreview").style.display = "none";
    });
</script>';

include 'includes/admin_footer.php'; 
?>