<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */

//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

// use namespace
// use Restserver\Libraries\REST_Controller;


class Sample extends CI_Controller
{
    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }
    
    public function __construct()
    {
        parent::__construct();
        $this->__resTraitConstruct();
        $this->methods['index_get']['limit'] = 12;
        
        $this->load->model('local/sample_model', 'sample');                
    }
    
    // ---------------------------------------------------------------------------
    /* API untuk GET / READ data                                                */
    // ---------------------------------------------------------------------------    
    public function index_get()
    {
        // Cek parameter ID yang dikirim sender
        $id = $this->get('id');
                
        if ($id === null) {
            // Jika tidak ada ID data yang dikirimkan, tampilkan semua data
            $sample = $this->sample->getSample();    
        } else {
            // Jika ada ID, maka parsing ID kedalama MODEL untuk filter data
            $sample = $this->sample->getSample($id);    
        }
                       
                
        // Tampilkan RESPON sesuai format REST
        if ($sample) {
                // Success
                $this->set_response([
                    'status' => true,
                    'message' => 'Request Success',
                    'data' => $sample
                    ], 
                    200); 
                /*
                $this->response([
                    'status' => true,
                    'data' => $sample
                ], REST_Controller::HTTP_OK); 
                */
        } else {
                // Data Not Found
                /*
                $this->response([
                    'status' => false,
                    'message' => 'ID Not Found'
                ], REST_Controller::HTTP_NOT_FOUND);       
                */
                $this->set_response([
                    'status' => false,
                    'message' => 'ID Not Found !!'
                ], 404); // NOT_FOUND (404) being the HTTP response code            
        }        
    }
    
    // ---------------------------------------------------------------------------
    /* API untuk DELETE data                                                    */
    // ---------------------------------------------------------------------------       
    public function index_delete()
    {
        $id = $this->delete('id');
        
        if ($id === NULL) 
        {
            // Data Not Found
            $this->set_response([
                'status' => false,
                'message' => 'Provide an ID'
            ], 400);              
        } else {
            if ($this->sample->deleteSample($id) > 0) {
                // SUKSES DELETE DATA
                $this->response([
                    'status' => true,
                    'message' => 'Delete Success ..'
                ], 204);                  
            } else {
                // GAGAL DELETE DATA
                $this->set_response([
                    'status' => false,
                    'message' => 'ID Not Found !!'
                ], 400);                  
            }
        }
    }
    
    // ---------------------------------------------------------------------------
    /* API untuk CREATE / ADD data                                              */
    // ---------------------------------------------------------------------------       
    public function index_post()
    {
        $data = [
            'sample' => $this->post('sample'),
            'description' => $this->post('description'),
            'email' => $this->post('email'),
            'telp' => $this->post('telp')
        ];
        
        if ($this->sample->createSample($data) > 0)
        {
            // SUKSES CREATE DATA
            $this->set_response([
                'status' => true,
                'message' => 'New Data Success Created ..'
            ], 201);               
        } else {
            // GAGAL CREATE DATA
            $this->set_response([
                'status' => false,
                'message' => 'Failed create data ..'
            ], 400);               
        }
    }
    
    // ---------------------------------------------------------------------------
    /* API untuk UPDATE data                                                     */
    // ---------------------------------------------------------------------------       
    public function index_put()
    {
        $id = $this->put('id');
        $data = [
            'sample' => $this->put('sample'),
            'description' => $this->put('description'),
            'email' => $this->put('email'),
            'telp' => $this->put('telp')
        ];
        
        
        if ($this->sample->updateSample($data, $id) > 0)
        {
            // SUKSES CREATE DATA
            $this->set_response([
                'status' => true,
                'message' => 'Success Update data ..'
            ], 201);               
        } else {
            // GAGAL CREATE DATA
            $this->set_response([
                'status' => false,
                'message' => 'Failed update data ..'
            ], 400);               
        }        
    }
    
}