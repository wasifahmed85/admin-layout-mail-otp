<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

function admin()
{
    return Auth::guard('admin')->user();
}
function user()
{
    return Auth::guard('web')->user();
}
function timeFormat($time)
{
    return $time ? date(('d M, Y H:i A'), strtotime($time)) : 'N/A';
}

function dateFormat($time)
{
    return $time ? date(('d M, Y'), strtotime($time)) : 'N/A';
}
function timeFormatHuman($time)
{
    return Carbon::parse($time)->diffForHumans();
}
function creater_name($user)
{
    return $user->name ?? 'System';
}

function updater_name($user)
{
    return $user->name ?? 'Null';
}

function deleter_name($user)
{
    return $user->name ?? 'Null';
}

function isSuperAdmin()
{
    return auth()->guard('admin')->user()->role->name == 'Super Admin';
}
function slugToTitle($slug)
{
    return Str::replace('-', ' ', $slug);
}
function storage_url($urlOrArray)
{
    $image = asset('default_img/no_img.jpg');
    if (is_array($urlOrArray) || is_object($urlOrArray)) {
        $result = '';
        $count = 0;
        $itemCount = count($urlOrArray);
        foreach ($urlOrArray as $index => $url) {

            $result .= $url ? (Str::startsWith($url, 'https://') ? $url : asset('storage/' . $url)) : $image;


            if ($count === $itemCount - 1) {
                $result .= '';
            } else {
                $result .= ', ';
            }
            $count++;
        }
        return $result;
    } else {
        return $urlOrArray ? (Str::startsWith($urlOrArray, 'https://') ? $urlOrArray : asset('storage/' . $urlOrArray)) : $image;
    }
}

function auth_storage_url($url, $gender = false)
{
    $image = asset('default_img/other.png');
    if ($gender == 1) {
        $image = asset('default_img/male.jpeg');
    } elseif ($gender == 2) {
        $image = asset('default_img/female.jpg');
    }
    return $url ? asset('storage/' . $url) : $image;
}

function getSubmitterType($className)
{
    $className = basename(str_replace('\\', '/', $className));
    return trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $className));
}

function availableTimezones()
{
    $timezones = [];
    $timezoneIdentifiers = DateTimeZone::listIdentifiers();

    foreach ($timezoneIdentifiers as $timezoneIdentifier) {
        $timezone = new DateTimeZone($timezoneIdentifier);
        $offset = $timezone->getOffset(new DateTime());
        $offsetPrefix = $offset < 0 ? '-' : '+';
        $offsetFormatted = gmdate('H:i', abs($offset));

        $timezones[] = [
            'timezone' => $timezoneIdentifier,
            'name' => "(UTC $offsetPrefix$offsetFormatted) $timezoneIdentifier",
        ];
    }

    return $timezones;
}
function isImage($path)
{
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    return in_array($extension, $imageExtensions);
}


