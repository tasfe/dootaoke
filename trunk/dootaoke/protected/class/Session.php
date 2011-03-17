<?php
/**
 * Session handler
 *
 * @author Milos Kovacki <kovacki@gmail.com>
 * @copyright Milos Kovacki 2010 <kovacki@gmail.com>
 */
 class Session
 {

 public static $_sessionId = NULL;
 public static $session = array();

 /**
 * Start session
 */
 public static function startSession() {
 self::$_sessionId = (isset($_COOKIE['session_id']) ? $_COOKIE['session_id'] : NULL);
 if ((!self::$_sessionId)||(!(self::$session=Doo::cache(Doo::conf()->sessionCacheType)->get('session_'.self::$_sessionId)))) {
 // Create new session
 self::$_sessionId = md5($_SERVER['REMOTE_ADDR'] . time() . rand(0,128));
 self::$session['ip'] = $_SERVER['REMOTE_ADDR'];
 self::$session['created'] = time();
 }
 setcookie('session_id', self::$_sessionId, (time()+3600*24*90), '/');
 }

 /**
 * End session
 */
 public static function endSession() {
 $sessionStored = Doo::cache(Doo::conf()->sessionCacheType)->set('session_'.self::$_sessionId, self::$session);
 }

 }
