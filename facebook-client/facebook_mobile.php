<?php
class FacebookMobile extends Facebook {
  // the application secret, which differs from the session secret

  public function __construct($api_key, $secret, $generate_session_secret=false) {
    parent::__construct($api_key, $secret, $generate_session_secret);
  }

  public function redirect($url) {
    header('Location: '. $url);
  }

  public function get_m_url($action, $params) {
    $page = parent::get_facebook_url('m'). '/' .$action;
    foreach($params as $key => $val) {
      if (!$val) {
        unset($params[$key]);
      }
    }
    return $page . '?' . http_build_query($params);
  }

  public function get_www_url($action, $params) {
    $page = parent::get_facebook_url('www'). '/' .$action;
    foreach($params as $key => $val) {
      if (!$val) {
        unset($params[$key]);
      }
    }
    return $page . '?' . http_build_query($params);
  }

  public function get_add_url($next=null) {

    return $this->get_m_url('add.php', array('api_key' => $this->api_key,
                                             'next'    => $next));
  }

  public function get_tos_url($next=null, $cancel = null, $canvas=null,$perms=null) {
    return $this->get_m_url('tos.php', array('api_key' => $this->api_key,
                                             'v'       => '1.0',
                                             'next'    => $next,
                                             'canvas'  => $canvas,
                                             'cancel'   => $cancel));
  }

  public function get_logout_url($next=null) {
    $params = array('api_key'     => $this->api_key,
                    'session_key' => $this->api_client->session_key,
                   );

    if ($next) {
      $params['connect_next'] = 1;
      $params['next'] = $next;
    }

    return $this->get_m_url('logout.php', $params);
  }
  public function get_register_url($next=null, $cancel_url=null) {
    return $this->get_m_url('r.php',
      array('fbconnect' => 1,
            'api_key' => $this->api_key,
            'next' => $next ? $next : parent::current_url(),
            'cancel_url' => $cancel_url ? $cancel_url : parent::current_url()));
  }
  /**
   * These set of fbconnect style url redirect back to the application current
   * page when the action is done. Developer can also use the non fbconnect
   * style url and provide their own redirect link by giving the right parameter
   * to $next and/or $cancel_url
   */
  public function get_fbconnect_register_url() {
    return $this->get_register_url(parent::current_url(), parent::current_url());
  }
  public function get_fbconnect_tos_url() {
    return $this->get_tos_url(parent::current_url(), parent::current_url(), $this->in_frame());
  }

  public function get_fbconnect_logout_url() {
    return $this->get_logout_url(parent::current_url());
  }

  public function logout_user() {
    $this->user = null;
  }

  public function get_prompt_permissions_url($ext_perm,
                                             $next=null,
                                             $cancel_url=null) {

    return $this->get_www_url('connect/prompt_permissions.php',
      array('api_key' => $this->api_key,
            'ext_perm' => $ext_perm,
            'next' => $next ? $next : parent::current_url(),
            'cancel' => $cancel_url ? $cancel_url : parent::current_url(),
            'display' => 'wap'));

  }

  /**
   * support both prompt_permissions.php and authorize.php for now.
   * authorized.php is to be deprecate though.
   */
  public function get_extended_permission_url($ext_perm,
                                              $next=null,
                                              $cancel_url=null) {
    $next = $next ? $next : parent::current_url();
    $cancel_url = $cancel_url ? $cancel_url : parent::current_url();

    return $this->get_m_url('authorize.php',
                      array('api_key' => $this->api_key,
                            'ext_perm' => $ext_perm,
                            'next' => $next,
                            'cancel_url' => $cancel_url));

  }

  public function render_prompt_feed_url($action_links=NULL,
                                         $target_id=NULL,
                                         $message='',
                                         $user_message_prompt='',
                                         $caption=NULL,
                                         $callback ='',
                                         $cancel='',
                                         $attachment=NULL,
                                         $preview=true) {

    $params = array('api_key'     => $this->api_key,
                    'session_key' => $this->api_client->session_key,
                   );
    if (!empty($attachment)) {
      $params['attachment'] = urlencode(json_encode($attachment));
    } else {
      $attachment = new stdClass();
      $app_display_info = $this->api_client->admin_getAppProperties(array('application_name',
                                                                          'callback_url',
                                                                          'description',
                                                                          'logo_url'));
      $app_display_info = $app_display_info;
      $attachment->name = $app_display_info['application_name'];
      $attachment->caption = !empty($caption) ? $caption : 'Just see what\'s new!';
      $attachment->description = $app_display_info['description'];
      $attachment->href = $app_display_info['callback_url'];
      if (!empty($app_display_info['logo_url'])) {
        $logo = new stdClass();
        $logo->type = 'image';
        $logo->src = $app_display_info['logo_url'];
        $logo->href = $app_display_info['callback_url'];
        $attachment->media = array($logo);
      }
      $params['attachment'] = urlencode(json_encode($attachment));
    }
    $params['preview'] = $preview;
    $params['message'] = $message;
    $params['user_message_prompt'] = $user_message_prompt;
    if (!empty($callback)) {
      $params['callback'] = $callback;
    } else {
      $params['callback'] = $this->current_url();
    }
    if (!empty($cancel)) {
      $params['cancel'] = $cancel;
    } else {
      $params['cancel'] = $this->current_url();
    }

    if (!empty($target_id)) {
      $params['target_id'] = $target_id;
    }
    if (!empty($action_links)) {
      $params['action_links'] = urlencode(json_encode($action_links));
    }

    $params['display'] = 'wap';
    header('Location: '. $this->get_www_url('connect/prompt_feed.php', $params));
  }

//use template_id
  public function render_feed_form_url($template_id=NULL,
                                       $template_data=NULL,
                                       $user_message=NULL,
                                       $body_general=NULL,
                                       $user_message_prompt=NULL,
                                       $target_id=NULL,
                                       $callback=NULL,
                                       $cancel=NULL,
                                       $preview=true) {

    $params = array('api_key' => $this->api_key);
    $params['preview'] = $preview;
    if (isset($template_id) && $template_id) {
      $params['template_id'] = $template_id;
    }
    $params['message'] = $user_message ? $user_message['value'] : '';
    if (isset($body_general) && $body_general) {
      $params['body_general'] = $body_general;
    }
    if (isset($user_message_prompt) && $user_message_prompt) {
      $params['user_message_prompt'] = $user_message_prompt;
    }
    if (isset($callback) && $callback) {
      $params['callback'] = $callback;
    } else {
      $params['callback'] = $this->current_url();
    }
    if (isset($cancel) && $cancel) {
      $params['cancel'] = $cancel;
    } else {
      $params['cancel'] = $this->current_url();
    }
    if (isset($template_data) && $template_data) {
      $params['template_data'] = $template_data;
    }
    if (isset($target_id) && $target_id) {
      $params['to_ids'] = $target_id;
    }
    $params['display'] = 'wap';
    header('Location: '. $this->get_www_url('connect/prompt_feed.php', $params));
  }
}
