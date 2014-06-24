<?php

/* 
 * Copyright (C) 2014 ibm
 *
 * File: login.php
 * Author: ibm Email: zhangbobell@163.com
 * createTime: 2014-6-19 16:20:53
 */

class Clogin extends CI_Controller
{    
    function __construct()
    {
        parent::__construct();
        
        $this->load->helper('captcha');
        $this->load->library('session');
    }
    
    /*
     *  index : 用户登录界面函数
     *  @$page='index' : 默认调用视图页面
     */
    public function index($page = 'index')
    {
        if ( ! file_exists('application/views/login/'.$page.'.php'))
        {
          show_404();
        }

        $data['title'] = "用户登录";

        $this->load->view('login/'.$page, $data);
        $this->load->view('templates/login-footer');

    }
    
    /*
     * getCaptcha : 生成验证码函数
     * @param : null
     * @return : $cap['image'] - 生成的验证码图像代码
     */
    public function getCaptcha()
    {
        if($this->session->userdata('captcha') != "")
        {
                $this->delCaptcha();
        }

        $vals = array(
          'word' => rand(1000, 10000),
          'img_path' => IMG_DIR.'/captcha/',
          'img_url' => IMG_DIR.'/captcha/',
          'img_width' => '100',
          'img_height' => '30',
          'font_path' => PUB_DIR.'/fonts/texb.ttf'
           );

        $cap = create_captcha($vals); 
        $this->session->set_userdata('captcha',$cap['word']);
        $this->session->set_userdata('captcha_url',$cap['time']);

        echo $cap['image'];
    }
    
    /*
     * delCaptcha : 删除验证码文件，用于登录成功以后，以便及时清理空间
     * param : null
     * return : null
     */
    public function delCaptcha() 
    {
        $path = IMG_DIR.'/captcha/'.$this->session->userdata('captcha_url').'.jpg';
        $this->load->helper('file');
        unlink($path);
    }
    
    /*
     * validate : 登录ajax操作目的函数，验证输入的验证码，用户名和密码，并echo出不同类型的值表示不同的状态。
     *            若登录成功，则还要进行删除验证码和设置session的操作。
     * param : none
     * return : none
     */
    public function validate()
    {
        define("CAPTCHA_ERROR", "2");
        define("PASSWORD_ERROR", "1");
        define("LOGIN_SUCCESS", "0");
        
        $captcha = $this->input->post('captcha', true);
        $username = $this->input->post('username', true);
        $password =  $this->input->post("password", true);
        
        //验证输入的验证码字段
        if($captcha != $this->session->userdata('captcha') )
        {
            echo CAPTCHA_ERROR;
            return;
        }

        
        $this->load->model("login/mlogin","mlogin");

        //验证用户名密码
        $record = $this->mlogin->validateUser($username, $password);
        if($record==-1)
        {
            echo PASSWORD_ERROR;
            return;
        }
        else
        {
            echo LOGIN_SUCCESS;
            
            //删除验证码
            $this->delCaptcha();
            $this->session->unset_userdata('captcha');
            $this->session->unset_userdata('captcha_url');
            
            //设置session数据
            $authDB =  $this->mlogin->getAuthDB($record['userid']);
            $userdata = array(
               'username'  => $username,
               'authDB'    => $authDB,
               'groupID'   => $record['groupid'],
               'logged_in' => TRUE
            );
            $this->session->set_userdata($userdata);
            
            //插入日志文件
            $this->mlogin->insertLogMessage($username, "login", $this->input->ip_address());
        }

    }
    
}

