<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use kit\Users;
use kit\memory\SessionMemory;

class Links extends CI_Controller
{
    private $user;
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('links_model');
        $this->load->helper('form');
        $memory = new SessionMemory();
        $this->user = new Users($memory);
        $this->checkAuth();
    }

    public function index()
    {
        $data = [];
        $queryRes = $this->links_model->qLinks();
        $data['count'] = $queryRes;
        $this->load->view('links/menu', $data);
    }
    public function showLink()
    {
        $data = [];
        $queryRes = $this->links_model->getLink();
        if ($queryRes === false) redirect('/links/');
        $id  = $queryRes["id"];
        $link = $queryRes["link"];
        // сразу удаляем запись, которую только что извлекли
        $this->links_model->deleteLink($id);
        $data['link'] = $link;
        $this->load->view('links/out_link', $data);
    }
    public function addLinks()
    {
        $post =[];
        $links = [];
        $post = $this->input->post(null, true);
        $_POST = null;
        if ($post === []) {
                $this->viewForm();
        } elseif ($post['submit'] === 'Ввод') {
            // есть ли данные в textarea?
            if ($post["links"] === '') {
                $this->viewForm();
            } else {
                $post["links"] = str_replace(["\r\n", "\n\r", "\n", "\r"], ["~~~", "~~~", "~~~", "~~~"], $post["links"]);
                $links = explode('~~~', $post["links"]);
                // пишем ссылки по одной в БД
                foreach ($links as $link) {
                    if ($link !== '' && filter_var($link, FILTER_VALIDATE_URL)) {    
                        $this->links_model->addLink($link);
                    }
                }
                redirect('/links/');
            }
        }

    }
    public function viewForm()
    {
        $this->load->view('links/add_links');
    }
    private function checkAuth()
    {
        $flag = $this->user->loginFact();
        if (!$flag) show_error("Сейчас доступ к этому адресу запрещён. Авторизируйтесь.", 403, 'Ограничения в доступе для клиента');
    }
}