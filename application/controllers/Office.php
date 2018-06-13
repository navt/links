<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use kit\Users;
use kit\memory\SessionMemory;

class Office extends CI_Controller
{
    private $user;
    public  $data;
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('workers_model');
        $this->load->helper('form');
        $memory = new SessionMemory();
        $this->user = new Users($memory);
        $this->checkAuth();
    }
    public function index() 
    {
        redirect('/office/viewAdminArea/');
    }
    // показ меню/форм
    public function viewCreateForm() 
    {
        $this->checkAdmin();
        $this->data['title'] = 'Создать нового пользователя';
        $this->load->view('office/create', $this->data);
    }
    public function viewPasswordForm() 
    {
        $this->data['title'] = 'Сменить пароль';
        $this->data['user'] = $this->user->item('email');
        $this->load->view('office/password', $this->data);
    }
    public function viewEditForm($id) 
    {
        $this->checkAdmin();
        $this->data['title'] = 'Редактировать данные пользователя';
        if (is_numeric($id) === true) {
            $this->data['worker'] = $this->workers_model->workersData('id', $id);
            $this->load->view('office/edit_worker', $this->data);
        }
    }
    public function viewAdminArea() 
    {
        $this->checkAdmin();
        $workers = $this->workers_model->allWorkers();
        $this->data['title'] = 'Функциональность администратора';
        $this->data['workers'] = $workers;
        $this->load->view('office/admin_area', $this->data);
    }
    // приём форм
    public function takeCreateForm() 
    {
        $this->checkAdmin();
        $get =[];
        $get = $this->input->get(null, true);
        $_GET = [];
        if ($get !== [] && $get['submit'] === 'Новый пользователь') {
            $this->session->err_msg = '';
            $userName = trim($get['userName']);
            $passWord = trim($get['passWord']);

            $this->session->userName = $userName;
            $this->session->passWord = $passWord;
            
            $this->create($userName, $passWord, $get['role']);
            deleteSI(['userName', 'passWord']);
            
            redirect('/office/viewAdminArea/');
        }
        redirect('/links/');
    }
    public function takePasswordForm() 
    {
        $get =[];
        $get = $this->input->get(null, true);
        $_GET = [];
        if ($get !== [] && $get['submit'] === 'Сменить пароль') {
            $this->session->err_msg = '';
            $oldPass  = trim($get['oldPass']);
            $passWord = trim($get['passWord']);

            $this->session->oldPass = $oldPass;
            $this->session->passWord = $passWord;
            
            $this->changePassword($oldPass, $passWord);
            deleteSI(['oldPass', 'passWord']);
        } 
        redirect('/links/');
    }
    public function takeEditForm($id) 
    {
        if ($this->user->isAdmin() === false || is_numeric($id) === false) {
            redirect('/links/');
        }
        $get =[];
        $get = $this->input->get(null, true);
        $_GET = [];
        if ($get !== [] && $get['submit'] === 'Изменить роль') {
            $this->edit($id,$get);
        } else {
            redirect('/office/viewAdminArea/');
        }
    }
    // работа с принятыми данными и моделью
    public function delete($id) 
    {
        if ($this->user->isAdmin() === false || is_numeric($id) === false) {
            redirect('/links/');
        }
        // запрос модели на предмет наличия юзера с соответствующим id
        $qr = $this->workers_model->workersData('id', $id);
        if ($qr !== false) {
            $this->workers_model->delete($id);
        }
        redirect('/office/viewAdminArea/');
    }
    private function create($userName, $passWord, $role)
    {
    	$this->checkAdmin();
        $noError = true;
        $error = emailValidate($userName, true);
        if ($error != false) {
            $this->session->err_msg = "E-mail не прошёл проверки - {$error} ".__METHOD__;
            $noError = false;
        }
        if ($noError) {
            $qty = mb_strlen($passWord);
            $confQty = $this->config->item('pass_length');
            if ($qty < $confQty) {
                $this->session->err_msg = "Длина пароля должна быть {$confQty} или более символов ".__METHOD__;
                $noError = false;
            }
        }
        if ($noError) {
            $filter ='~^[a-zA-Z0-9_-]+$~u';
            $flag = filter_var($passWord, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>$filter]]);
            if ($flag === false) {
                $this->session->err_msg = 'Пароль должен состоять из латинских букв, цифр, - и _ . '.__METHOD__;
                $noError = false;
            }
        }
        if ($noError) {
            $qr = $this->workers_model->workersData('email', $userName);
            if ($qr !== false) {
                $this->session->err_msg = "Пользователь {$userName} уже существует ".__METHOD__;
                $noError = false;
            }
        }
        if ($noError) {
            if (in_array($role, $this->config->item('role'), true) === false) {
                $this->session->err_msg = 'Попытка записи нового пользователя в БД с неизвестной ролью '.__METHOD__;
                $noError = false;
            } 
        }
        if ($noError) {
            $hash = genHash($userName, $passWord);
            $qr = $this->workers_model->addWorker($userName, $hash, $role);
            if ($qr !== true) {
                $this->session->err_msg = 'Ошибка записи нового пользователя в БД '.__METHOD__;
                $noError = false;
            }
        }
        
        if ($noError === false) {
            redirect('/office/viewCreateForm');
        } else {
            return $this;
        }
    }
    private function edit($id, $get) {
        $noError = true;
        $role = $get['role'];
        if (in_array($role, $this->config->item('role'), true) === false) {
            $this->session->err_msg = "Такой роли - {$role} нет. ".__METHOD__;
            $noError = false;
        } 
        if ($noError) {
            $qr = $this->workers_model->updateField($id, 'role', $role);
            if ($qr === true) {
                redirect("/office/viewEditForm/{$id}");
            } else {
                $this->session->err_msg = "Ошибка БД при обновлении роли. ".__METHOD__;
                $noError = false;
            }
        }
        redirect('/office/viewAdminArea/');
    }
    private function changePassword($oldPass, $passWord) 
    {
        $oldHesh = genHash($this->user->item('email'), $oldPass);
        $noError = true;
        // правильно ли введён старый пароль
        if ($oldHesh === $this->user->item('hash')) {
            $qty = mb_strlen($passWord);
            $confQty = $this->config->item('pass_length');
            if ($qty < $confQty) {
                $this->session->err_msg = "Длина пароля должна быть {$confQty} или более символов ".__METHOD__;
                $noError = false;
            }
            if ($noError) {
                $filter ='~^[a-zA-Z0-9_-]+$~u';
                $flag = filter_var($passWord, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>$filter]]);
                if ($flag === false) {
                    $this->session->err_msg = 'Пароль должен состоять из латинских букв, цифр, - и _ . '.__METHOD__;
                    $noError = false;
                }
            }
            // если новый пароль "правильный", обновляем `hesh` в БД
            if ($noError) {
                $hash = genHash($this->user->item('email'), $passWord);
                $qr = $this->workers_model->updateField($this->user->item('id'), 'hash', $hash);
                if ($qr !== true) {
                    $this->session->err_msg = 'Ошибка БД при обновлении нового пароля  '.__METHOD__;
                    $noError = false;
                }
            }
        } else {
            $this->session->err_msg = "Прежний пароль введён неверно. ".__METHOD__;
            $noError = false;
        }
        // что в итоге?
        if ($noError === false) {
            redirect('/office/viewPasswordForm/');
        } else {
            return $this;
        }
    }

    private function checkAuth()
    {
        $flag = $this->user->loginFact();
        if (!$flag) {
            show_error("Сейчас доступ к этому адресу запрещён. Авторизируйтесь.", 403, 'Ограничения в доступе для клиента');
        }
    }
    private function checkAdmin() 
    {
        if ($this->user->isAdmin() === false) {
            redirect('/links/');
        }
    }
}