<? if (!defined("_class_sendmail_inc")) : ?>
<? define("_class_sendmail_inc",1); ?>
<?

//base class for mail classes (a kind of an abstract class in C++)
class sendmail{
var $version;
var $to;		//email of destination
var $to_name;	//name of the destination
var $from;		//email of sender
var $from_name;	//name of the sender
var $subject;	//subject of the message
var $message;	//message body
var $c_type;	//content type of the message (ig text/plain, text/html)
var $path;		//path to sendmail programm at your server

  function sendmail($path=''){
    $this->version = '1.0';
    $this->to = '';
    $this->to_name = '';
    $this->from = '';
    $this->from_name = '';
    $this->subject = '';
    $this->message = '';
    $this->c_set = 'iso-8859-1';
    $this->c_type = 'text/plain';

//    if(!$path) $this->path = '/usr/sbin/sendmail -t -oi';
     if(!$path) $this->path = '/usr/sbin/sendmail';
      else $this->path = $path;
  }

  function set_version($version='1.0'){
    $this->version = $version;
  }

  function set_to($to, $to_name=''){
    $this->to = $to;
    $this->to_name = $to_name;
  }

  function set_from($from, $from_name=''){
    $this->from = $from;
    $this->from_name = $from_name;
  }

  function set_subject($subject=''){
    $this->subject = $subject;
  }

  function set_message($message=''){
    $this->message = $message;
  }

  function set_c_set($c_set='iso-8859-1'){
    $this->c_set = strtolower($c_set);
  }

  function set_c_type($c_type='text/plain'){
    if($c_type) $this->c_type = $c_type;
  }
}

//class to send plain text (could be html text) without attachements
class plainmail extends sendmail{

  function plainmail($path=''){
    $this->sendmail($path);
  }

  //send built message
  function send(){
    if(!$this->to)  return;

    $m  = "MIME-Version: $this->version\n";
    $m  .= "From:";
    if($this->from_name) $m .= $this->from_name;
    if($this->from) $m .= " <".$this->from.">";
    $m .= "\n";
    $m .= "To:";
    if($this->to_name)  $m .= $this->to_name;
    $m .= " <".$this->to.">\n";
    if($this->subject) $m .= "Subject: ".$this->subject."\n";
    $m .= "Content-Type: ".$this->c_type."; charset=".$this->c_set."\n\n";
    $m .= $this->message;

    $p = popen($this->path, "w");
    fputs($p, $m);
    pclose($p);

  }

}

//class to send text with attachements
class mimemail extends sendmail{
var $attachments;

  function mimemail($path=''){
    $this->sendmail($path);

    $this->attachments = array();
  }

  function add_attachment($body, $c_type='', $c_set='', $name='', $filename=''){
    $this->attachments[] = array(
      "body" => $body,
      "c_type" => $c_type,
      "c_set" => $c_set,
      "name" => $name,
      "filename" => $filename
    );
  }

  //send built message
  function send(){
    if(!$this->to)  return;

    //вставляем текст письма
    $this->add_attachment($this->message, $this->c_type, $this->c_set);

    $m  = "MIME-Version: ".$this->version."\n";
    $m  .= "From: ";
    if($this->from_name) $m .= $this->from_name;
    if($this->from) $m .= " <".$this->from.">";
    $m .= "\n";
    $m .= "To: ";
    if($this->to_name)  $m .= $this->to_name;
    $m .= " <".$this->to.">\n";
    if($this->subject) $m .= "Subject: ".$this->subject."\n";

    $boundary = md5(uniqid(time()));
    $m .= "Content-Type: multipart/mixed; boundary=".$boundary."\n\n";
    $m .= "--".$boundary;

    //insert attachements in the message
    $flag = 0;
    for($i=count($this->attachments)-1; $i>=0; $i--){
      $val = $this->attachments[$i];
      if($flag) $body = chunk_split(base64_encode($val['body']));
        else $body = $val['body'];

      $m .= "\nContent-Type: ".$val['c_type'];
      if($val['c_set']) $m .= "; charset=".$val['c_set'];
      if($val['name']) $m .= "; name=\"".$val['name']."\"";
      $m .= "\n";
      if($flag){
        $m .= "Content-Transfer-Encoding: base64\n";
        $m .= "Content-Disposition: attachment";
        if($val['filename']) $m .= "; filename=\"".$val['filename']."\"";
        $m .= "\n";
      }
      $m .= "\n".$body."\n--".$boundary;
      if(!$flag) $flag = 1;
    }

    $m .= "--\n";

    $p = popen($this->path, "w");
    fputs($p, $m);
    pclose($p);

  }

}

?>
<? endif; ?>