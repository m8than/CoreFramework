<?php

//Model::load('User'); //Loads the user model

Router::add('GET','','Home','All');
Router::add('GET','{*}','Home','All');

/*
if(User::isLoggedIn())
{
    Router::add('GET','d','Panel', 'Redirect:d/dashboard');
    Router::add('GET','d/dashboard','Panel', 'Dashboard');
    Router::add('GET','d/project/{int}','Project', 'Manage');
    Router::add('GET','d/logout','Panel', 'Logout');
}
else
{
    Router::add('GET','d','Panel', 'Redirect:d/login');
    Router::add('GET','d/{*}','Panel', 'Redirect:d/login');

    Router::add('GET','d/login','Panel', 'Login');
    Router::add('POST','d/login','Panel', 'Login_action');

    Router::add('GET','d/register','Panel', 'Register');
    Router::add('POST','d/register','Panel', 'Register_action');
}
*/
