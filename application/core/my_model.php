<?php

/* 
 * Copyright (C) 2014 ibm
 *
 * File: my_model.php
 * Author: ibm Email: zhangbobell@163.com
 * createTime: 2014-6-19 16:11:14
 */

class MY_model extends CI_Model 
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function select_DB($databaseName)
    {
        
        /*$db_config['hostname'] = '192.168.1.90';
        $db_config['username'] = 'data';
        $db_config['password'] = 'data2123';*/
        $db_config['hostname'] = '10.76.3.53';
        $db_config['username'] = 'data';
        $db_config['password'] = 'data123';
        $db_config['database'] = $databaseName;
        $db_config['dbdriver'] = 'mysqli';
        $db_config['dbprefix'] = '';
        $db_config['pconnect'] = TRUE;
        $db_config['db_debug'] = TRUE;
        $db_config['cache_on'] = FALSE;
        $db_config['cachedir'] = '';
        $db_config['char_set'] = 'utf8';
        $db_config['dbcollat'] = 'utf8_bin';
        $db_config['swap_pre'] = '';
        $db_config['autoinit'] = TRUE;
        $db_config['stricton'] = FALSE;
        
        return $db_config;
    }
}