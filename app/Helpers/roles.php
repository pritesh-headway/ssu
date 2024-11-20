<?php

if (!function_exists('myRolesFunction')) {
    function myRolesFunction($role)
    {
        if($role == 'Administrator'){
            $role_id = '1';
        } else if ($role == 'Accountant') {
            $role_id = '2';
        } else if ($role == 'Verifier') {
            $role_id = '3';
        } else if ($role == 'followers') {
            $role_id = '4';
        } else if ($role == 'Logistics') {
            $role_id = '5';
        }
        return $role_id;
    }
}