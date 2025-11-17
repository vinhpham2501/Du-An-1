<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Contact;

class ContactController extends Controller
{
    private $contactModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        
        $this->contactModel = new Contact();
    }

    public function index()
    {
        $filters = [
            'limit' => 20,
            'offset' => (($_GET['page'] ?? 1) - 1) * 20
        ];
        
        if (!empty($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        
        if (!empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        
        $contacts = $this->contactModel->getAll($filters);
        $totalContacts = $this->contactModel->count($filters);
        $statusCounts = $this->contactModel->getStatusCounts();
        
        return $this->render('admin/contacts/index', [
            'contacts' => $contacts,
            'totalContacts' => $totalContacts,
            'statusCounts' => $statusCounts,
            'filters' => $filters,
            'currentPage' => $_GET['page'] ?? 1,
            'totalPages' => ceil($totalContacts / 20)
        ]);
    }

    public function show($id)
    {
        $contact = $this->contactModel->findById($id);
        
        if (!$contact) {
            http_response_code(404);
            return $this->render('errors/404');
        }
        
        // Mark as read if it's new
        if ($contact['status'] === 'new') {
            $this->contactModel->updateStatus($id, 'read');
            $contact['status'] = 'read';
        }
        
        return $this->render('admin/contacts/show', [
            'contact' => $contact
        ]);
    }

    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        $contactId = $_POST['contact_id'] ?? 0;
        $status = $_POST['status'] ?? '';
        
        if (!$contactId || !in_array($status, ['new', 'read', 'replied'])) {
            return $this->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        }
        
        $contact = $this->contactModel->findById($contactId);
        if (!$contact) {
            return $this->json(['success' => false, 'message' => 'Tin nhắn không tồn tại']);
        }
        
        $result = $this->contactModel->updateStatus($contactId, $status);
        
        if ($result) {
            return $this->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
        } else {
            return $this->json(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        $contactId = $_POST['contact_id'] ?? 0;
        
        if (!$contactId) {
            return $this->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        }
        
        $contact = $this->contactModel->findById($contactId);
        if (!$contact) {
            return $this->json(['success' => false, 'message' => 'Tin nhắn không tồn tại']);
        }
        
        $result = $this->contactModel->delete($contactId);
        
        if ($result) {
            return $this->json(['success' => true, 'message' => 'Xóa tin nhắn thành công']);
        } else {
            return $this->json(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
    }
}
