<?php

class Sample_model extends CI_Model
{
    
    public function __construct(){
        parent::__construct();
        
        // Load setting DB EREMS
        $this->local = $this->load->database('local', TRUE);
    }
    
    public function getSample($id = null)
    {
        if ($id === null) 
        {
            // -- Pakai Query Builder CodeIgniter  --  
            // return $this->erems->limit(0,100)->get('m_customer', 0, 100)->result_array();  -- MYSQL            
            // return $this->erems->get_where('th_sample', ['deleted' => '0', ])->result_array();  
            
            // -- Pakai Query langsung di Code Igniter --
            // $query = "select customer_id, code, name, address, home_phone, office_phone, mobile_phone, ktp_number, " .             
            //         "ktp_name, ktp_address, npwp, birthplace, birthdate, email, project_id, pt_id " . 
            //         "from erems..m_customer where Deleted = 0";
            // return $this->erems->query($query)->result_array();                        
            
            // -- PAKAI STORE PROCEDURE --
            $stmt = "EXECUTE sp_api_getSample";
            return $this->local->query($stmt)->result_array();
        } 
        else
        {            
            return $this->local->get_where('m_sample', ['sample_id' => $id])->result_array(); 
        }         
    }
    
    public function deleteSample($id = null) {
        // --- DELETE dengan status, jangan delete fisik
        // $this->local->delete('m_sample',['sample_id' => $id]);
        // return $this->local->affected_rows();
        $data = [
            'is_deleted' => '1'
        ];
                
        $this->local->update('m_sample', $data, ['sample_id' => $id]);
        return $this->local->affected_rows();          
    }
    
    public function createSample ($data) {
        $this->local->insert('m_sample', $data);
        return $this->local->affected_rows();
    }
    
    public function updateSample($data, $id) {
        $this->local->update('m_sample', $data, ['sample_id' => $id]);
        return $this->local->affected_rows();        
    }
}