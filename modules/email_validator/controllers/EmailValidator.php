<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EmailValidator extends CI_Controller {
    public function cron_task() {
        // Carrega a biblioteca de banco de dados
        $this->load->database();
        
        // Obtém o email do administrador (assumindo que o staffid 1 é o administrador)
        $this->db->select('email');
        $this->db->where('staffid', 1);
        $staff = $this->db->get('tblstaff')->row();
        $admin_email = $staff ? $staff->email : 'admin@example.com'; // Fallback se não houver staff
        
        // Obtém todos os contatos
        $this->db->select('firstname, lastname, email');
        $query = $this->db->get('tblcontacts');
        $contacts = $query->result();
        
        // Inicializa um array para armazenar contatos com emails inválidos
        $invalid_contacts = [];
        
        // Valida cada email
        foreach ($contacts as $contact) {
            if (!filter_var($contact->email, FILTER_VALIDATE_EMAIL)) {
                $invalid_contacts[] = $contact->firstname . ' ' . $contact->lastname . ' (' . $contact->email . ')';
            }
        }
        
        // Se houver emails inválidos, envia um alerta por email
        if (!empty($invalid_contacts)) {
            // Carrega a biblioteca de email
            $this->load->library('email');
            
            // Configura o email
            $this->email->from('noreply@dominiodesejado.com', 'Perfex CRM - Validação de Emails');
            $this->email->to($admin_email);
            $this->email->subject('Relatório Diário de Emails Inválidos');
            $this->email->message('Os seguintes contatos possuem emails inválidos:<br>' . implode('<br>', $invalid_contacts));
            $this->email->set_mailtype('html'); // Define o tipo de email como HTML
            
            // Envia o email
            $this->email->send();
        }
    }
}