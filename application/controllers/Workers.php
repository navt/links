<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use kit\Users;
use kit\memory\SessionMemory;

class Workers extends CI_Controller
{
    private $user;
    private $queryRes;


    public function __construct() {
        parent::__construct();
        $this->load->model('workers_model');
        $this->load->helper('form');
        $memory = new SessionMemory();
        $this->user = new Users($memory);
    }

    public function index()	
    {
        $get =[];
        $get = $this->input->get(null, true);
        $_GET = [];
        if ($get === []) {
            if ($this->user->loginFact()) {
                // если уже есть факт авторизации, то редирект
                redirect('/links/');
            } else {
                // смотрим, есть ли куки
                $cookie = $this->input->cookie('user');
                if ($cookie !== null) {
                    $this->cookieAuth($cookie);
                } else {
                    // иначе, показываем форму авторизации
                    $this->viewForm();
                }
            }
        } elseif ($get['submit'] === 'Вход') {
            $this->session->err_msg = '';
            $userName = trim($get['userName']);
            $passWord = trim($get['passWord']);

            $this->session->userName = $userName;
            $this->session->passWord = $passWord;

            $error = emailValidate($userName, true);
            if ($error != false) {
                $this->session->err_msg = "Логин не прошёл проверки - {$error} ".__METHOD__;
                refresh('/workers/viewForm/', 1);
            }
            $filter ='~^[a-zA-Z0-9_-]+$~u';
            $flag = filter_var($passWord, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>$filter]]);
            if ($flag === false) {
                $this->session->err_msg = 'В пароле использован неверный набор символов . '.__METHOD__;
                refresh('/workers/', 1);
            }
            // если всё прошло, то запрос к БД
            $this->queryRes = $this->workers_model->workersData('email', $userName);

            if ($this->queryRes === false) {
                $this->session->err_msg = "Не нашлось пары (# 1) {$userName} / {$passWord} в БД. ".__METHOD__;
                refresh('/workers/', 2);
            } else {
                $hash = genHash($userName, $passWord);
                if ($hash === $this->queryRes['hash']) {
                    // логиним этого пользователя
                    if (isset($get['remember']) && $get['remember'] === 'yes') {
                        $remember = true;
                    } else {
                        $remember = false;
                    }
                    $this->login($remember);
                    redirect('/links/');
                } else {
                    $this->session->err_msg = "Не нашлось пары (# 2) {$userName} / {$passWord} в БД. ".__METHOD__;
                    refresh('/workers/viewForm/', 2);
                }
            }
        }
    }

    public function viewForm()
    {
        $this->load->view('workers/login');
    }
    
    private function login($remember) {
        $this->user->saveItems($this->queryRes);
        // если есть чекбокс Запомнить меня - пишем куки, текущее время жизни - неделя (604800)
        if ($remember === true) {
            $this->input->set_cookie('user', $this->queryRes['id'].'|'.$this->queryRes['mix'], 604800);
        }
        $this->deleteSessionVars();
        return $this;
    }
    
    private function cookieAuth($cookie) {
        list($id, $mix) = explode('|', $cookie);
        if (is_numeric($id)) {
            // запрос модели на предмет наличия юзера с соответствующим id
            $this->queryRes = $this->workers_model->workersData('id', $id);
            if ($this->queryRes === false) {
                // нет такого пользователя - стираем куки, показываем форму авторизации
                $this->deleteCookie();
            } else {
                // если юзер есть в БД, то  проверяем md5(микст) на соответствие 
                if ($this->queryRes['mix'] === $mix) {
                    // md5() совпал - логиним этого пользователя
                    $this->user->saveItems($this->queryRes);
                    $this->deleteSessionVars();
                    redirect('/links/');
                } else {
                    // mix не совпал - стираем куки, показываем форму авторизации
                    $this->deleteCookie(); 
                }
            }
        } else {
            $this->deleteCookie();
        }
    }
    
    private function deleteCookie() 
    {
        $this->input->set_cookie('user', '', 0);
        $this->viewForm();
    }
    
    private function deleteSessionVars() 
    {
        $this->session->userName = null;
        $this->session->passWord = null;
        return $this;
    }
    
    public function deleteAuth()
    {
        $this->user->clear();
        $this->deleteSessionVars();
        $this->input->set_cookie('user', '', 0);
        redirect('/workers/index/');
    }
    public function generate($userName, $passWord)
    {
    	//$userName = 'w-navt@yandex.ru';
    	//$passWord = '';
    	echo genHash($userName, $passWord);
    }
    public function userGen($userName, $passWord)
    {
    	if ($this->user->loginFact() === false) {
            redirect('/workers/viewForm/');
    	}
    	$noError = true;
        $error = emailValidate($userName, true);
        if ($error != false) {
            $err_msg = "E-mail не прошёл проверки - {$error} ".__METHOD__;
            $noError = false;
        }
        if ($noError) {
            $qty = mb_strlen($passWord);
            $confQty = $this->config->item('pass_length');
            if ($qty < $confQty) {
                $err_msg = "Длина пароля должна быть {$confQty} или более символов ".__METHOD__;
                $noError = false;
            }
        }
        if ($noError) {
            $filter ='~^[a-zA-Z0-9_-]+$~u';
            $flag = filter_var($passWord, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>$filter]]);
            if ($flag === false) {
                $err_msg = 'Пароль должен состоять из латинских букв, цифр, - и _ . '.__METHOD__;
                $noError = false;
            }
        }
        if ($noError) {
            $qr = $this->workers_model->workersData($userName);
            if ($qr !== false) {
                $err_msg = "Пользователь {$userName} уже существует ".__METHOD__;
                $noError = false;
            }
        }
        if ($noError) {
            $hash = genHash($userName, $passWord);
            $qr = $this->workers_model->addWorker($userName, $hash);
            if ($qr !== true) {
                $err_msg = 'Ошибка записи нового пользователя в БД '.__METHOD__;
                $noError = false;
            }
        }
        if ($noError === false) {
            echo $err_msg;
        }
        exit('<br>'.__METHOD__.' отработал.');
    }
}
