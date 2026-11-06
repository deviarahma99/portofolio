<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Portfolio_model', 'portfolio');
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
    }

    public function index()
    {
        if (!$this->is_logged_in()) {
            redirect('admin/login');
        }
        $this->load->view('admin/dashboard', array(
            'profile' => $this->portfolio->get_profile(),
            'skills' => $this->portfolio->get_skills(),
            'projects' => $this->portfolio->get_projects(),
            'messages' => $this->portfolio->get_messages(),
            'flash' => $this->session->flashdata('flash'),
            'flash_error' => $this->session->flashdata('flash_error')
        ));
    }

    public function login()
    {
        if ($this->is_logged_in()) {
            redirect('admin');
        }
        $error = NULL;
        if ($this->input->method() === 'post') {
            $username = trim($this->input->post('username', TRUE));
            $password = $this->input->post('password', TRUE);
            $admin = $this->db->get_where('admin_users', array('username' => $username, 'is_active' => 1))->row_array();
            if ($admin && password_verify($password, $admin['password'])) {
                $this->session->set_userdata(array('admin_id' => $admin['id'], 'admin_name' => $admin['name']));
                redirect('admin');
            }
            $error = 'Username atau password belum sesuai.';
        }
        $this->load->view('admin/login', array('error' => $error));
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('admin/login');
    }

    public function profile()
    {
        $this->require_login();

        if ($this->input->method() !== 'post') {
            redirect('admin');
        }

        $current = $this->portfolio->get_profile();
        $avatar_url = isset($current['avatar_url']) ? $current['avatar_url'] : '';

        if (!empty($_FILES['avatar']['name'])) {
            $upload = $this->handle_avatar_upload();
            if (!$upload['success']) {
                $this->session->set_flashdata('flash_error', $upload['error']);
                redirect('admin#profile');
            }
            $this->delete_avatar_file($avatar_url);
            $avatar_url = $upload['path'];
        } elseif ($this->input->post('remove_avatar')) {
            $this->delete_avatar_file($avatar_url);
            $avatar_url = '';
        } else {
            $avatar_url = $this->input->post('avatar_url', TRUE);
        }

        $data = array(
            'name' => $this->input->post('name', TRUE),
            'role' => $this->input->post('role', TRUE),
            'headline' => $this->input->post('headline', TRUE),
            'bio' => $this->input->post('bio', TRUE),
            'location' => $this->input->post('location', TRUE),
            'email' => $this->input->post('email', TRUE),
            'availability' => $this->input->post('availability', TRUE),
            'linkedin_url' => $this->input->post('linkedin_url', TRUE),
            'github_url' => $this->input->post('github_url', TRUE),
            'avatar_url' => $avatar_url
        );

        $this->portfolio->update_profile($data);
        $this->session->set_flashdata('flash', 'Profile berhasil diperbarui.');
        redirect('admin');
    }

    /**
     * Validates and stores an uploaded profile photo under assets/uploads/profile/.
     * The stored filename is always system-generated so the original filename
     * from the visitor's machine never reaches the filesystem or the database.
     */
    private function handle_avatar_upload()
    {
        $upload_dir = FCPATH . 'assets/uploads/profile/';

        if (!is_dir($upload_dir) || !is_writable($upload_dir)) {
            return array('success' => FALSE, 'error' => 'Folder upload tidak tersedia. Hubungi developer.');
        }

        $file = $_FILES['avatar'];
        $max_bytes = 2 * 1024 * 1024;

        if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
            return array('success' => FALSE, 'error' => 'Ukuran file melebihi batas maksimal 2 MB.');
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return array('success' => FALSE, 'error' => 'Upload foto gagal. Silakan coba lagi.');
        }
        if ($file['size'] > $max_bytes) {
            return array('success' => FALSE, 'error' => 'Ukuran file melebihi batas maksimal 2 MB.');
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_ext = array('jpg', 'jpeg', 'png', 'webp');
        if (!in_array($ext, $allowed_ext, TRUE)) {
            return array('success' => FALSE, 'error' => 'Format file tidak didukung. Gunakan JPG, PNG, atau WEBP.');
        }

        $allowed_mimes = array('image/jpeg', 'image/png', 'image/webp');
        $image_info = @getimagesize($file['tmp_name']);
        if ($image_info === FALSE || !in_array($image_info['mime'], $allowed_mimes, TRUE)) {
            return array('success' => FALSE, 'error' => 'File yang diunggah bukan gambar yang valid.');
        }

        $generated_name = 'profile_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

        $config = array(
            'upload_path' => $upload_dir,
            'allowed_types' => implode('|', $allowed_ext),
            'max_size' => 2048,
            'file_name' => $generated_name,
            'overwrite' => FALSE,
            'detect_mime' => TRUE,
            'remove_spaces' => TRUE
        );

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('avatar')) {
            return array('success' => FALSE, 'error' => strip_tags($this->upload->display_errors('', '')));
        }

        $uploaded = $this->upload->data();

        return array('success' => TRUE, 'path' => 'assets/uploads/profile/' . $uploaded['file_name']);
    }

    /**
     * Only deletes files this app itself put in assets/uploads/profile/ —
     * never touches an externally hosted avatar_url.
     */
    private function delete_avatar_file($avatar_url)
    {
        if (empty($avatar_url) || strpos($avatar_url, 'assets/uploads/profile/') !== 0) {
            return;
        }
        $full_path = FCPATH . $avatar_url;
        if (is_file($full_path)) {
            @unlink($full_path);
        }
    }

    public function skill($action = 'save', $id = NULL)
    {
        $this->require_login();
        if ($action === 'delete' && $id) {
            $this->portfolio->delete_skill($id);
            $this->session->set_flashdata('flash', 'Skill berhasil dihapus.');
        } elseif ($this->input->method() === 'post') {
            $data = array(
                'name' => $this->input->post('name', TRUE),
                'category' => $this->input->post('category', TRUE),
                'level' => (int) $this->input->post('level'),
                'sort_order' => (int) $this->input->post('sort_order')
            );
            $this->portfolio->save_skill($data, $id);
            $this->session->set_flashdata('flash', 'Skill berhasil disimpan.');
        }
        redirect('admin');
    }

    public function project($action = 'save', $id = NULL)
    {
        $this->require_login();
        if ($action === 'delete' && $id) {
            $this->portfolio->delete_project($id);
            $this->session->set_flashdata('flash', 'Project berhasil dihapus.');
        } elseif ($this->input->method() === 'post') {
            $data = array(
                'title' => $this->input->post('title', TRUE),
                'slug' => url_title($this->input->post('title', TRUE), 'dash', TRUE),
                'summary' => $this->input->post('summary', TRUE),
                'tech_stack' => $this->input->post('tech_stack', TRUE),
                'project_url' => $this->input->post('project_url', TRUE),
                'image_url' => $this->input->post('image_url', TRUE),
                'year' => (int) $this->input->post('year'),
                'featured' => $this->input->post('featured') ? 1 : 0
            );
            $this->portfolio->save_project($data, $id);
            $this->session->set_flashdata('flash', 'Project berhasil disimpan.');
        }
        redirect('admin');
    }

    private function is_logged_in()
    {
        return (bool) $this->session->userdata('admin_id');
    }

    private function require_login()
    {
        if (!$this->is_logged_in()) {
            redirect('admin/login');
        }
    }
}
