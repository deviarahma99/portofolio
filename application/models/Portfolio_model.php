<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portfolio_model extends CI_Model
{
    public function get_profile()
    {
        return $this->db->get_where('portfolio_profile', array('id' => 1))->row_array();
    }

    public function update_profile($data)
    {
        return $this->db->where('id', 1)->update('portfolio_profile', $data);
    }

    public function get_skills($limit = NULL)
    {
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('id', 'DESC');
        if ($limit) {
            $this->db->limit($limit);
        }
        return $this->db->get('portfolio_skills')->result_array();
    }

    public function get_skill($id)
    {
        return $this->db->get_where('portfolio_skills', array('id' => (int) $id))->row_array();
    }

    public function save_skill($data, $id = NULL)
    {
        if ($id) {
            return $this->db->where('id', (int) $id)->update('portfolio_skills', $data);
        }
        return $this->db->insert('portfolio_skills', $data);
    }

    public function delete_skill($id)
    {
        return $this->db->delete('portfolio_skills', array('id' => (int) $id));
    }

    public function get_projects($limit = NULL)
    {
        $this->db->order_by('featured', 'DESC');
        $this->db->order_by('year', 'DESC');
        $this->db->order_by('id', 'DESC');
        if ($limit) {
            $this->db->limit($limit);
        }
        return $this->db->get('portfolio_projects')->result_array();
    }

    public function get_project($id)
    {
        return $this->db->get_where('portfolio_projects', array('id' => (int) $id))->row_array();
    }

    public function save_project($data, $id = NULL)
    {
        if ($id) {
            return $this->db->where('id', (int) $id)->update('portfolio_projects', $data);
        }
        return $this->db->insert('portfolio_projects', $data);
    }

    public function delete_project($id)
    {
        return $this->db->delete('portfolio_projects', array('id' => (int) $id));
    }

    public function get_messages()
    {
        return $this->db->order_by('created_at', 'DESC')->get('portfolio_messages')->result_array();
    }

    public function save_message($data)
    {
        return $this->db->insert('portfolio_messages', $data);
    }

    public function count($table)
    {
        return $this->db->count_all($table);
    }
}
