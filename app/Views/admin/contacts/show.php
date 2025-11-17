<?php $title = 'Chi tiết tin nhắn #' . $contact['id'] . ' - Admin'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Chi tiết tin nhắn #<?= $contact['id'] ?></h1>
    <a href="/admin/contacts" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-envelope me-2"></i>
                    Nội dung tin nhắn
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="text-muted">Từ:</h6>
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px;">
                                <?= strtoupper(substr($contact['name'], 0, 1)) ?>
                            </div>
                        </div>
                        <div>
                            <strong><?= htmlspecialchars($contact['name']) ?></strong><br>
                            <a href="mailto:<?= htmlspecialchars($contact['email']) ?>" class="text-decoration-none">
                                <?= htmlspecialchars($contact['email']) ?>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6 class="text-muted">Thời gian gửi:</h6>
                    <p><?= date('d/m/Y H:i:s', strtotime($contact['created_at'])) ?></p>
                </div>
                
                <div class="mb-4">
                    <h6 class="text-muted">Nội dung:</h6>
                    <div class="border p-3 rounded bg-light">
                        <?= nl2br(htmlspecialchars($contact['message'])) ?>
                    </div>
                </div>
                
                <!-- Reply Form -->
                <div class="mt-4">
                    <h6 class="text-muted">Phản hồi nhanh:</h6>
                    <div class="border p-3 rounded">
                        <form id="replyForm">
                            <div class="mb-3">
                                <label for="replySubject" class="form-label">Tiêu đề email</label>
                                <input type="text" class="form-control" id="replySubject" 
                                       value="Re: Liên hệ từ website - <?= htmlspecialchars($contact['name']) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="replyMessage" class="form-label">Nội dung phản hồi</label>
                                <textarea class="form-control" id="replyMessage" rows="5" 
                                          placeholder="Nhập nội dung phản hồi...">Xin chào <?= htmlspecialchars($contact['name']) ?>,

Cảm ơn bạn đã liên hệ với chúng tôi. 

Chúng tôi đã nhận được tin nhắn của bạn và sẽ phản hồi trong thời gian sớm nhất.

Trân trọng,
Restaurant Team</textarea>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="mailto:<?= htmlspecialchars($contact['email']) ?>?subject=Re: Liên hệ từ website - <?= htmlspecialchars($contact['name']) ?>&body=Xin chào <?= htmlspecialchars($contact['name']) ?>,%0D%0A%0D%0ACảm ơn bạn đã liên hệ với chúng tôi.%0D%0A%0D%0AChúng tôi đã nhận được tin nhắn của bạn và sẽ phản hồi trong thời gian sớm nhất.%0D%0A%0D%0ATrân trọng,%0D%0ARestaurant Team" 
                                   class="btn btn-primary">
                                    <i class="fas fa-reply me-2"></i>Mở Email Client
                                </a>
                                <button type="button" class="btn btn-outline-primary" onclick="copyReply()">
                                    <i class="fas fa-copy me-2"></i>Copy nội dung
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Thông tin tin nhắn
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Trạng thái hiện tại:</label>
                    <?php
                    $statusColors = [
                        'new' => 'warning',
                        'read' => 'info', 
                        'replied' => 'success'
                    ];
                    $statusLabels = [
                        'new' => 'Tin nhắn mới',
                        'read' => 'Đã đọc',
                        'replied' => 'Đã phản hồi'
                    ];
                    $color = $statusColors[$contact['status']] ?? 'secondary';
                    $label = $statusLabels[$contact['status']] ?? $contact['status'];
                    ?>
                    <br>
                    <span class="badge bg-<?= $color ?> fs-6"><?= $label ?></span>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Thay đổi trạng thái:</label>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-warning btn-sm" onclick="updateStatus('new')">
                            <i class="fas fa-envelope me-2"></i>Đánh dấu mới
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="updateStatus('read')">
                            <i class="fas fa-envelope-open me-2"></i>Đánh dấu đã đọc
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="updateStatus('replied')">
                            <i class="fas fa-reply me-2"></i>Đánh dấu đã phản hồi
                        </button>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <label class="form-label">Thông tin khách hàng:</label>
                    <div class="small">
                        <strong>Tên:</strong> <?= htmlspecialchars($contact['name']) ?><br>
                        <strong>Email:</strong> <?= htmlspecialchars($contact['email']) ?><br>
                        <strong>Ngày gửi:</strong> <?= date('d/m/Y H:i', strtotime($contact['created_at'])) ?><br>
                        <?php if ($contact['updated_at'] !== $contact['created_at']): ?>
                            <strong>Cập nhật:</strong> <?= date('d/m/Y H:i', strtotime($contact['updated_at'])) ?><br>
                        <?php endif; ?>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-grid">
                    <button class="btn btn-outline-danger" onclick="deleteContact()">
                        <i class="fas fa-trash me-2"></i>Xóa tin nhắn
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Thao tác nhanh
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="mailto:<?= htmlspecialchars($contact['email']) ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-envelope me-2"></i>Gửi email
                    </a>
                    <button class="btn btn-outline-info btn-sm" onclick="copyEmail()">
                        <i class="fas fa-copy me-2"></i>Copy email
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="printMessage()">
                        <i class="fas fa-print me-2"></i>In tin nhắn
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(status) {
    if (confirm('Bạn có chắc muốn thay đổi trạng thái tin nhắn này?')) {
        fetch('/admin/contacts/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `contact_id=<?= $contact['id'] ?>&status=${status}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                location.reload();
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            showAlert('danger', 'Có lỗi xảy ra, vui lòng thử lại');
        });
    }
}

function deleteContact() {
    if (confirm('Bạn có chắc muốn xóa tin nhắn này? Hành động này không thể hoàn tác.')) {
        fetch('/admin/contacts/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `contact_id=<?= $contact['id'] ?>`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => {
                    window.location.href = '/admin/contacts';
                }, 1500);
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            showAlert('danger', 'Có lỗi xảy ra, vui lòng thử lại');
        });
    }
}

function copyEmail() {
    navigator.clipboard.writeText('<?= htmlspecialchars($contact['email']) ?>').then(() => {
        showAlert('success', 'Đã copy email vào clipboard');
    });
}

function copyReply() {
    const replyText = document.getElementById('replyMessage').value;
    navigator.clipboard.writeText(replyText).then(() => {
        showAlert('success', 'Đã copy nội dung phản hồi vào clipboard');
    });
}

function printMessage() {
    const printContent = `
        <h3>Tin nhắn liên hệ #<?= $contact['id'] ?></h3>
        <p><strong>Từ:</strong> <?= htmlspecialchars($contact['name']) ?> (<?= htmlspecialchars($contact['email']) ?>)</p>
        <p><strong>Ngày gửi:</strong> <?= date('d/m/Y H:i:s', strtotime($contact['created_at'])) ?></p>
        <p><strong>Nội dung:</strong></p>
        <div style="border: 1px solid #ccc; padding: 10px; background: #f9f9f9;">
            <?= nl2br(htmlspecialchars($contact['message'])) ?>
        </div>
    `;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Tin nhắn #<?= $contact['id'] ?></title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    h3 { color: #333; }
                    p { margin: 10px 0; }
                </style>
            </head>
            <body>
                ${printContent}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}
</script>
