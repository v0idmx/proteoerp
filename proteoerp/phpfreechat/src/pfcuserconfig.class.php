<?php

require_once dirname(__FILE__)."/pfcglobalconfig.class.php";

class pfcUserConfig
{
  var $nick;
  var $channels;
  var $privmsg;
  var $active;
  
  var $timeout;
  var $nickid;
  var $serverid;
  var $CI;
  
  //  var $is_init = false; // used internaly to know if the chat config is initialized
  //  var $errors = array();
  
  function pfcUserConfig()
  {
  	$this->CI = & get_instance();
    $c =& pfcGlobalConfig::Instance();

    // start the session : session is used for locking purpose and cache purpose
    $this->nickid = $this->CI->session->userdata('session_id');

    // user parameters are cached in sessions
    $this->_getParam("nick");
    if (!isset($this->nick)) $this->_setParam("nick",""); // setup a blank nick if it is not yet in session
    $this->_getParam("active");
    if (!isset($this->active)) $this->_setParam("active",false);   
    $this->_getParam("channels");
    if (!isset($this->channels)) $this->_setParam("channels",array());
    $this->_getParam("privmsg");
    if (!isset($this->privmsg)) $this->_setParam("privmsg",array());
    $this->_getParam("serverid");
    if (!isset($this->privmsg)) $this->_setParam("serverid",$c->serverid);
  }

  function &Instance()
  {
    static $i;
    
    if (!isset($i))
    {
      $i = new pfcUserConfig();
    }
    return $i;
  }

  function &_getParam($p)
  {
    if (!isset($this->$p))
    {
      $c =& pfcGlobalConfig::Instance();
      $nickid       = "pfcuserconfig_".$c->getId();
      $nickid_param = $nickid."_".$p;
      if ($this->CI->session->userdata($nickid_param))
        $this->$p = $this->CI->session->userdata($nickid_param);
    }
    return $this->$p;
  }

  function _setParam($p, $v)
  {
    $c =& pfcGlobalConfig::Instance();
    $nickid_param = "pfcuserconfig_".$c->getId()."_".$p;
    $array[$nickid_param] = $v;
    $this->CI->session->set_userdata($array);
    $this->$p = $v;
  }

  function _rmParam($p)
  {
    $c =& pfcGlobalConfig::Instance();
    $nickid_param = "pfcuserconfig_".$c->getId()."_".$p;   
    $this->CI->session->unset_userdata($nickid_param); 
    unset($this->$p);
    if ($p == 'active') $this->active = false;
  }
  

  function destroy()
  {
    $this->_rmParam("nick");
    $this->_rmParam("active");
    $this->_rmParam("channels");
    $this->_rmParam("privmsg");
    $this->_rmParam("serverid");
  }
  
  function saveInCache()
  {
    //    echo "saveInCache()<br>";
    $c =& pfcGlobalConfig::Instance();

    // do not save anything as long as nickname is not assigned
    //if ($this->active && $this->nick != "")
    {
      $this->_setParam("nick",     $this->nick);
      $this->_setParam("active",   $this->active);
      $this->_setParam("channels", $this->channels);
      $this->_setParam("privmsg",  $this->privmsg);
      $this->_setParam("serverid", $this->serverid);
    }
  }

  function isOnline()
  {
    $ct =& pfcContainer::Instance();
    $online = $ct->isNickOnline(NULL, $this->nickid);
    return $online;
  }

  function getNickname()
  {
    if ($this->nick != '') return $this->nick;
    $ct =& pfcContainer::Instance();
    return $ct->getNickname($this->nickid);
  }

  function getChannelNames()
  {
    $list = array();
    foreach( $this->channels as $v )
      $list[] = $v["name"];
    return $list;
  }
  function getPrivMsgNames()
  {
    $list = array();
    foreach( $this->privmsg as $v )
      $list[] = $v["name"];
    return $list;
  }  
}

?>