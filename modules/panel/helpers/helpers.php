<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

function getChar($number) {
    if ($number > 0 && $number <= 26) {
        return chr(64 + $number);
    } else if ($number > 26) {
        $first = chr(64 + floor($number / 26));
        $second = chr(64 + ($number % 26));
        return $first . $second;
    } else {
        return 'ND';
    }
}

function computeBreadcrumbs() : Array
{
    $breadcrumbs = [];
    $segments = request()->segments();
    $url = '';

    // dd($segments);
    // http://192.168.1.183:8000/purchase_returns/create
    // array:2 [
    //     0 => "purchase_returns"
    //     1 => "create"
    // ]
        
    foreach ($segments as $segment) {
        $url .= '/' . $segment;
        $breadcrumbs[] = [
            'name' => ucwords(str_replace(['-', '_'], ' ', $segment)),
            'url' => $url
        ];
    }
    
    return $breadcrumbs;
}

function getTableRowId($currentPage, $pageLength, $serial)
{
    return (($currentPage * $pageLength) - $pageLength) + $serial;
}

function checkCategoryLevel()
{
    $categoryLevel = 10;
    if ($categoryLevel == 1) {
        return false;
    } else if (session()->get('category_level') < $categoryLevel) {
        return true;
    }

    return false;
}

function changeDateFormate($value)
{
    return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d-m-Y h:i:s A');
}

function changeDateFormat($value, $format = 'd-m-Y h:i:s A')
{
    return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->format($format);
}

function replaceKeyName($array, $oldKey, $newKey) {
    $array[$newKey] = $array[$oldKey];
    unset($array[$oldKey]);
    return $array;
}

// 
function getParentName($model, $key)
{
    return $model->{__FUNCTION__}($key);
}

// to be deleted
function getUserData($model, $key)
{
    return $model->{__FUNCTION__}($key);
}


function getTableData($model, $key)
{
    return $model->{__FUNCTION__}($key);
}

function getModelName($model, $key)
{
    return $model->{__FUNCTION__}($key);
}

function getFamilyName($model)
{
    $array_of_names = array();
    array_push($array_of_names, $model->name);
    if (!empty($model->parent_id)) {
        array_push($array_of_names, implode("&nbsp;|&nbsp;", getParent($model)));
    }
    return implode("&nbsp;|&nbsp;", $array_of_names);
}

function getParent($parentModel)
{
    $names = [];
    if ($parentModel->parentWithTrashed) {
        $names[] = $parentModel->parentWithTrashed->name;
        if (!empty($parentModel->parentWithTrashed->parent_id)) {
            $names = array_merge($names, getParent($parentModel->parentWithTrashed));
        }
    }
    return $names;
}

function checkCurrentRoute($name)
{   
    // $allRoutes = getRoutesArray();
    // if (in_array(Route::currentRouteName(), $allRoutes[$name] ?? [])) {
    //     return 'active';
    // }
    return '';
}

function isLinkActive($name)
{   
    $allRoutes = getRoutesArray();
    $currentRouteName = Route::currentRouteName();
    $currentRoutePrefix = Str::before($currentRouteName, '.');
    $name = (string) Str::of($name)->singular()->lower();

    if (array_key_exists($name, $allRoutes)) {
        if (in_array($currentRoutePrefix, $allRoutes[$name])) {
            return 'active';
        }
    }
    return '';
}

function isChildLinkActive($route)
{
    $currentRouteName = Route::currentRouteName();
    // dd($currentRouteName, $route);

    $currentRoutePrefix = Str::before($currentRouteName, '.');
    $routePrefix = Str::before($route, '.');

    if ($currentRoutePrefix == 'archived') {
        $currentRoutePrefix = Str::after(Str::beforeLast($currentRouteName, '.'), '.');
        $routePrefix = Str::after(Str::beforeLast($route, '.'), '.');
    }

    // dd($currentRoutePrefix, $routePrefix);
    if ($currentRoutePrefix === $routePrefix) {
        return 'active';
    }

    return '';
}

function getRoutesArray(): array
{
    // Initialize an empty array to hold the routes
    $allRoutes = [
        'dashboard' => [
            'panel.dashboard'
        ],
    ];

    // Loop through each module in the configuration
    foreach (config('panel.modulelinks') as $module) {

        // Check if the 'child' key exists and is an array
        if (isset($module['child']) && is_array($module['child'])) {

            // Initialize an empty array to hold the child routes
            $childRoutes = [];

            // Loop through each child route in the module
            foreach ($module['child'] as $child) {

                // Check if the 'route' key exists
                if (isset($child['route'])) {

                    // Append the child route to the array
                    $childRoutes[] = Str::before($child['route'], '.');
                }
            }

            // if (isset($module['name']) && $module['name'] == 'Orders') {
            //     dd($childRoutes);
            // }

            // Add the child routes array to the allRoutes array
            // Use strtolower and singular to match your existing function
            $name = (string) \Illuminate\Support\Str::of($module['name'] ?? '')->singular()->lower();
            if (!empty($childRoutes)) {
                $allRoutes[$name] = $childRoutes;
            }
        }
    }

    return $allRoutes;
}

/**
 * Convert db timestamp in to string m-d-y
 */
function getDatestamp($value) {
    return date('m-d-Y',strtotime($value));
}

function getTimestamp($value) {
    return date('m-d-Y h:i A', strtotime($value));
}

function getAddressableType($value)
{
    return Str::afterLast($value->addressable_type, '\\') . ' | ' . $value->addressable->name;
}
