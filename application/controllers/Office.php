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
            try {
                $this->create($userName, $passWord, $get['role']);
            } catch (Exception $e) {
                $this->session->err_msg = $e->getMessage().' Код ошибки: '.$e->getCode();
                redirect('/office/viewCreateForm/');
            } 
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
            try {
               $this->changePassword($oldPass, $passWord); 
            } catch (Exception $e) {
                $this->session->err_msg = $e->getMessage().' Код ошибки: '.$e->getCode();
                redirect('/office/viewPasswordForm/');
            }

            
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
            try {
                $this->edit($id,$get);
            } catch (Exception $e) {
                $this->session->err_msg = $e->getMessage().' Код ошибки: '.$e->getCode();
                redirect('/office/viewAdminArea/');
            }
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
        $error = emailValidate($userName, true);
        if ($error != false) {
            throw new Exception("E-mail не прошёл проверки - {$error} ", 201);
        }
        $qty = mb_strlen($passWord);
        $confQty = $this->config->item('pass_length');
        if ($qty < $confQty) {
            throw new Exception("Длина пароля должна быть {$confQty} или более символов ", 202);
        }
        $filter ='~^[a-zA-Z0-9_-]+$~u';
        $flag = filter_var($passWord, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>$filter]]);
        if ($flag === false) {
            throw new Exception('Пароль должен состоять из латинских букв, цифр, - и _ . ', 203);
        }
        $qr = $this->workers_model->workersData('email', $userName);
        if ($qr !== false) {
            throw new Exception("Пользователь {$userName} уже существует ", 204);
        }
        if (in_array($role, $this->config->item('role'), true) === false) {
            throw new Exception('Попытка записи нового пользователя в БД с неизвестной ролью ', 205);
        } 
        $hash = genHash($userName, $passWord);
        $qr = $this->workers_model->addWorker($userName, $hash, $role);
        if ($qr !== true) {
            throw new Exception('Ошибка записи нового пользователя в БД ', 206);
        }

        return $this;
    }
    private function edit($id, $get) {
        $role = $get['role'];
        if (in_array($role, $this->config->item('role'), true) === false) {
            throw new Exception("Такой роли - {$role} нет. ", 207);
        } 
        $qr = $this->workers_model->updateField($id, 'role', $role);
        if ($qr !== true) {
            throw new Exception("Ошибка БД при обновлении роли. ", 208);
        } else {
            redirect("/office/viewEditForm/{$id}");
        }
    }
    private function changePassword($oldPass, $passWord) 
    {
        $oldHesh = genHash($this->user->item('email'), $oldPass);
        // правильно ли введён старый пароль
        if ($oldHesh === $this->user->item('hash')) {
            $qty = mb_strlen($passWord);
            $confQty = $this->config->item('pass_length');
            if ($qty < $confQty) {
                throw new Exception("Длина пароля должна быть {$confQty} или более символов ", 209);
            }
            $filter ='~^[a-zA-Z0-9_-]+$~u';
            $flag = filter_var($passWord, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>$filter]]);
            if ($flag === false) {
                throw new Exception('Пароль должен состоять из латинских букв, цифр, - и _ . ', 210);
            }
            // если новый пароль "правильный", обновляем `hesh` в БД
            $hash = genHash($this->user->item('email'), $passWord);
            $qr = $this->workers_model->updateField($this->user->item('id'), 'hash', $hash);
            if ($qr !== true) {
                throw new Exception('Ошибка БД при обновлении нового пароля  ', 211);
            }
        } else {
            throw new Exception("Прежний пароль введён неверно. ", 212);
        }
        return $this;
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