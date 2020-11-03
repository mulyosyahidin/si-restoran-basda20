<?php

use App\Models\Setting;
use Illuminate\Support\Facades\DB;

if (!function_exists('getSetting')) {
    function getSetting($key)
    {
        $setting = Setting::select('value')
            ->where('key', $key)
            ->first();

        return $setting->value;
    }
}

if (!function_exists('getSiteName')) {
    function getSiteName()
    {
        return getSetting('siteName');
    }
}

if ( ! function_exists('updateSetting'))
{
    function updateSetting($key, $newValue = '')
    {
        DB::table('settings')
            ->where('key', $key)
            ->update([
                'value' => $newValue
            ]);
    }
}

if (!function_exists('getController')) {
    function getController()
    {
        $action = app('request')->route()->getAction();
        $route = class_basename($action['controller']);

        list($controller, $action) = explode('@', $route);

        return $controller;
    }
}

if (!function_exists('getAction')) {
    function getAction()
    {
        $action = app('request')->route()->getAction();
        $route = class_basename($action['controller']);

        list($controller, $action) = explode('@', $route);

        return $action;
    }
}

if (!function_exists('isController')) {
    function isController($controller)
    {
        return ($controller === getController());
    }
}

if (!function_exists('isAction')) {
    function isAction($action)
    {
        return ($action === getAction());
    }
}

if (!function_exists('__active')) {
    function __active($controller = '', $action = '', $param = '')
    {
        $phpSelf = $_SERVER['PHP_SELF'];

        if ($controller === '' && $action === '') {
            return ' active';
        }
        else if ($param !== '') {
            if (isController($controller) && isAction($action)) {
                if (strpos($phpSelf, $param) !== FALSE) {
                    return ' active';
                }
            }
        }
        else if (is_array($controller) && count($controller)) {
            foreach ($controller as $c) {
                if (isController($c)) {
                    return ' active';
                    break;
                }
            }
        }
        else if (is_array($action) && count($action) > 0) {
            foreach ($action as $method) {
                if (isController($controller) && isAction($method)) {
                    return ' active';
                    break;
                }
            }
        }
        else if (isController($controller) && isAction($action)) {
            return ' active';
        }
    }
}

if ( ! function_exists('createAcronym'))
{
    function createAcronym($words)
    {
        $acronym = '';
        $words = explode(' ', $words);
        foreach ($words as $word)
        {
            $first_letter = str_split($word);

            $acronym .= $first_letter[0];
        }

        return $acronym;
    }
}

if ( ! function_exists('getProfilePicture'))
{
    function getProfilePicture()
    {
        if (isset(auth()->user()->media[0])) {
            return auth()->user()->media[0]->getFullUrl();
        }

        return asset('assets/themes/stisla/img/avatar/avatar-1.png');
    }
}

if ( ! function_exists('createOrderNumber'))
{
    function createOrderNumber($waiter_id, $customerName, $totalItem, $tableID)
    {
        $number = '';
        $random = bin2hex(random_bytes(3));
        
        //ORD|TANGGAL|BULAN|TAHUN|WAITER ID|CUSTOMER_ACR|TOTAL_ITEM|TABLE_ID

        $number = $random.$waiter_id.createAcronym($customerName).$totalItem.$tableID;

        return strtoupper($number);

    }
}