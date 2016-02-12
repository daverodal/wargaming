<?php
namespace Wargame;

use App\Services\AdminService;

$dir = dirname(__DIR__);

/**
 *
 * Copyright 2011-2015 David Rodal
 *
 *  This program is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * User: David M. Rodal
 * Date: 4/25/12
 * Time: 5:03 PM
 * Link: http://davidrodal.com
 * */

define("WARGAMES", "$dir");
set_include_path(WARGAMES . PATH_SEPARATOR . get_include_path());

class Battle
{

    static $forceName = [];
    static $deployName = [];

    public function __construct(AdminService $ad)
    {
        Battle::$ad = $ad;

    }

    public static function register($forceName, $deployName)
    {
        static::$forceName = $forceName;
        static::$deployName = $deployName;
        return compact("forceName", "deployName");
    }

    public static function pokePlayer($player)
    {
//        $CI =& get_instance();
//
//        $CI->load->database();
//
//        $que = 'SELECT count(*) as COUNT FROM  `ci_sessions` WHERE user_data LIKE  "%\"'.$player.'\"%" LIMIT 0 , 30';
//        $query = $CI->db->query($que);
//        foreach ($query->result() as $row)
//        {
//            if(!$row->COUNT){
//                $CI->load->model('users/users_model');
//                $userObj = $CI->users_model->getUserByUsername($player);
//                echo "$player ";
//                die($row->COUNT);
//                if($userObj){
//                    Battle::sendReminder($userObj->email);
//                }
//            }
//        }
    }

    public static function sendReminder($emailAddr)
    {
        $CI =& get_instance();
        $poke_user = $CI->config->item('poke_users');

        if (!$poke_user) {
            return;
        }
        $CI->load->library('email');

        $CI->email->from('gameBot@davidrodal.com', 'GameBot ');
        $CI->email->to('dave.rodal@gmail.com');

        $CI->email->subject('Email Test sending to ' . $emailAddr);
        $CI->email->message('Your turn.');

        $CI->email->send();

        echo $CI->email->print_debugger();


    }

    private static $theBattle;
    private static $isLoaded = false;
    private static $game;
    public static $ad;


    public static function battleFromDoc($doc)
    {
        self::$theBattle = new $doc->className($doc->wargame, $doc->wargame->arg, $doc->wargame->scenario);
        return self::$theBattle;
    }

    public static function battleFromName($name, $arg, $options = false)
    {

        try {
            if (self::$theBattle) {
                return self::$theBattle;
            }

            $game = self::loadGame($name, $arg);

            if ($game !== false && $arg !== false) {
                $scenarios = $game->scenarios->$arg;
                if (!$scenarios) {
                    $scenarios = new stdClass();
                }
                $className = $game->className;
                $params = isset($game->params) ? $game->params : new \stdClass();
                foreach ($params as $pKey => $pValue) {
                    if (!isset($scenarios->$pKey)) {
                        $scenarios->$pKey = $pValue;
                    }
                }
                if (!empty($options)) {
                    foreach ($options as $name) {
                        foreach ($game->options as $gameOption) {
                            if ($gameOption->keyName === $name) {
                                if (!empty($gameOption->extra)) {
                                    foreach ($gameOption->extra as $k => $v) {
                                        $scenarios->$k = $v;
                                    }
                                }
                                $scenarios->$name = true;
                            }
                        }
                    }
                }
                $thisBattle = new $className(null, $arg, $scenarios);
            } else {
                $className = $game->className;

                $thisBattle = new $className(null);
            }
            self::$theBattle = $thisBattle;
            return self::$theBattle;

        } catch (\Exception $e) {
            echo $e->getMessage() . " " . $e->getFile() . " " . $e->getLine();
        }
    }

    public static function getBattle()
    {

        if (self::$theBattle) {
            return self::$theBattle;
        }
        throw(new \Exception("No Object Found getBattle"));
    }


    public static function loadGame($name, $arg = false)
    {
        if (self::$isLoaded) {
            return self::$game;
        }
        if ($arg === false) {
            var_dump(debug_backtrace());
            die("loadGame no arg");
        }

        try {
            $game = self::$ad->getGame($name);

            if ($game !== false) {
                self::$isLoaded = true;
                self::$game = $game;
                $path = $game->path;
                $argTwo = $game->scenarios->$arg;
                set_include_path(WARGAMES . $path . PATH_SEPARATOR . get_include_path());
                $className = preg_replace("/.php$/", "", $game->fileName);

                $matches = [];

                preg_match("/([^\\\\]*)\\\\[^\\\\]*$/", $className, $matches);
                set_include_path(WARGAMES . "$path/" . $matches[1] . PATH_SEPARATOR . get_include_path());

                $game->className = $className;


                return $game;
            }

            throw(new \Exception("Bad Class in loadGame '$name'"));

        } catch (\Exception $e) {
            echo $e->getMessage() . " " . $e->getFile() . " " . $e->getLine();
        }
        return false;
    }

}
