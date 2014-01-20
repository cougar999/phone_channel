<?php
class smtp

{

	/* public variables */
	
	var $smtp_port;
	
	var $time_out;
	
	var $host_name;
	
	var $log_file;
	
	var $relay_host;
	
	var $debug;
	
	var $auth;
	
	var $user;
	
	var $pass;
	
	/* private variables */ 
	var $sock;
	
	/* constractor */
	
	function smtp($relay_host = "", $smtp_port = 25,$auth = false,$user,$pass)
	
	{
	
		$this->debug = false;
		
		$this->smtp_port = $smtp_port;
		
		$this->relay_host = $relay_host;
		
		$this->time_out = 30; //is used in fsockopen() 
		#
		
		$this->auth = $auth;//auth
		
		$this->user = $user;
		
		$this->pass = $pass;
		
		#
		
		$this->host_name = "localhost"; //is used in helo command 
		$this->log_file = "";
		
		 
		
		$this->sock = false;
	
	}
	
	/* main function */
	
	function sendmail($to, $from, $subject = "", $body = "", $mailtype, $cc = "", $bcc = "", $additional_headers = "")
	
	{
	
		$mail_from = $this->get_address($this->strip_comment($from));
		
		$body = ereg_replace("(^|(\r\n))(\.)", "\1.\3", $body);
		
		$header .= "mime-version:1.0\r\n";
		
		if($mailtype=="html"){
		
			$header .= "content-type:text/html\r\n";
		
		}
		
		$header .= "to: ".$to."\r\n";
		
		if ($cc != "") {
		
			$header .= "cc: ".$cc."\r\n";
		
		}
		
		//$header .= "from: $from<".$from.">\r\n";
		$header .= "from: °®Æ¤¿Æ¼¼<".$from.">\r\n";
		
		$header .= "subject: ".$subject."\r\n";
		
		$header .= $additional_headers;
		
		$header .= "date: ".date("r")."\r\n";
		
		//$header .= "x-mailer:by redhat (php/".phpversion().")\r\n";
		
		list($msec, $sec) = explode(" ", microtime());
		
		$header .= "message-id: <".date("ymdhis", $sec).".".($msec*1000000).".".$mail_from.">\r\n";
		
		$to = explode(",", $this->strip_comment($to));
		
		if ($cc != "") {
		
			$to = array_merge($to, explode(",", $this->strip_comment($cc)));
		
		}
		
		if ($bcc != "") {
		
			$to = array_merge($to, explode(",", $this->strip_comment($bcc)));
		
		}
		
		$sent = true;
		
		foreach ($to as $rcpt_to) {
		
			$rcpt_to = $this->get_address($rcpt_to);
			
			if (!$this->smtp_sockopen($rcpt_to)) {
			
				$this->log_write("error: cannot send email to ".$rcpt_to."\n");
				
				$sent = false;
				
				continue;
			
			}
			
			if ($this->smtp_send($this->host_name, $mail_from, $rcpt_to, $header, $body)) {
			
				$this->log_write("e-mail has been sent to <".$rcpt_to.">\n");
			
			} else {
			
				$this->log_write("error: cannot send email to <".$rcpt_to.">\n");
				
				$sent = false;
			
			}
			
			fclose($this->sock);
			
			$this->log_write("disconnected from remote host\n");
		
		}
		
		return $sent;
	
	}
	
	 
	
	/* private functions */
	
	 
	
	function smtp_send($helo, $from, $to, $header, $body = "")
	
	{
	
		if (!$this->smtp_putcmd("helo", $helo)) {
		
			return $this->smtp_error("sending helo command");
		
		}
		
		#auth
		
		if($this->auth){
		
			if (!$this->smtp_putcmd("auth login", base64_encode($this->user))) {
			
				return $this->smtp_error("sending helo command");
			
			}
			
			if (!$this->smtp_putcmd("", base64_encode($this->pass))) {
			
				return $this->smtp_error("sending helo command");
			
			}
		
		}
		
		#
		
		if (!$this->smtp_putcmd("mail", "from:<".$from.">")) {
		
			return $this->smtp_error("sending mail from command");
		
		}
		
		if (!$this->smtp_putcmd("rcpt", "to:<".$to.">")) {
		
			return $this->smtp_error("sending rcpt to command");
		
		}
		
		if (!$this->smtp_putcmd("data")) {
		
			return $this->smtp_error("sending data command");
		
		}
		
		if (!$this->smtp_message($header, $body)) {
		
			return $this->smtp_error("sending message");
		
		}
		
		if (!$this->smtp_eom()) {
		
			return $this->smtp_error("sending <cr><lf>.<cr><lf> [eom]");
		
		}
		
		if (!$this->smtp_putcmd("quit")) {
		
			return $this->smtp_error("sending quit command");
		
		}
		
		return true;
	
	}
	
	function smtp_sockopen($address)
	
	{
	
		if ($this->relay_host == "") {
		
			return $this->smtp_sockopen_mx($address);
		
		} else {
		
			return $this->smtp_sockopen_relay();
		
		}
	
	}
	
	function smtp_sockopen_relay()
	
	{
	
		$this->log_write("trying to ".$this->relay_host.":".$this->smtp_port."\n");
		
		$this->sock = @fsockopen($this->relay_host, $this->smtp_port, $errno, $errstr, $this->time_out);
	
		if (!($this->sock && $this->smtp_ok())) {
		
			$this->log_write("error: cannot connenct to relay host ".$this->relay_host."\n");
			
			$this->log_write("error: ".$errstr." (".$errno.")\n");
			
			return false;
		
		}
		
		$this->log_write("connected to relay host ".$this->relay_host."\n");
		
		return true;;
	
	}
	
	 
	
	function smtp_sockopen_mx($address)
	
	{
	
		$domain = ereg_replace("^.+@([^@]+)$", "\1", $address);
		
		if (!@getmxrr($domain, $mxhosts)) {
		
			$this->log_write("error: cannot resolve mx \"".$domain."\"\n");
			
			return false;
		
		}
		
		foreach ($mxhosts as $host) {
		
			$this->log_write("trying to ".$host.":".$this->smtp_port."\n");
			
			$this->sock = @fsockopen($host, $this->smtp_port, $errno, $errstr, $this->time_out);
			
			if (!($this->sock && $this->smtp_ok())) {
			
				$this->log_write("warning: cannot connect to mx host ".$host."\n");
				
				$this->log_write("error: ".$errstr." (".$errno.")\n");
				
				continue;
			
			}
			
			$this->log_write("connected to mx host ".$host."\n");
			
			return true;
		
		}
		
		$this->log_write("error: cannot connect to any mx hosts (".implode(", ", $mxhosts).")\n");
		
		return false;
	
	}
	
	 
	
	function smtp_message($header, $body)
	
	{
	
		fputs($this->sock, $header."\r\n".$body);
		
		$this->smtp_debug("> ".str_replace("\r\n", "\n"."> ", $header."\n> ".$body."\n> "));
		
		 
		
		return true;
	
	}
	
	 
	
	function smtp_eom()
	
	{
	
		fputs($this->sock, "\r\n.\r\n");
		
		$this->smtp_debug(". [eom]\n");
		
		 
		
		return $this->smtp_ok();
	
	}
	
	 
	
	function smtp_ok()
	
	{
	
		$response = str_replace("\r\n", "", fgets($this->sock, 512));
		
		$this->smtp_debug($response."\n");
		
		 
		
		if (!ereg("^[23]", $response)) {
		
			fputs($this->sock, "quit\r\n");
			
			fgets($this->sock, 512);
			
			$this->log_write("error: remote host returned \"".$response."\"\n");
			
			return false;
		
		}
		
		return true;
	
	}
	
	function smtp_putcmd($cmd, $arg = "")
	
	{
	
		if ($arg != "") {
		
			if($cmd=="") $cmd = $arg;
			
			else $cmd = $cmd." ".$arg;
		
		}
		
		fputs($this->sock, $cmd."\r\n");
		
		$this->smtp_debug("> ".$cmd."\n");
		
		return $this->smtp_ok();
	
	}
	
	function smtp_error($string)
	
	{
	
		$this->log_write("error: error occurred while ".$string.".\n");
		
		return false;
	
	}
	
	function log_write($message)
	
	{
	
		$this->smtp_debug($message);
		
		if ($this->log_file == "") {
		
			return true;
		
		}
		
		$message = date("m d h:i:s ").get_current_user()."[".getmypid()."]: ".$message;
		
		if (!@file_exists($this->log_file) || !($fp = @fopen($this->log_file, "a"))) {
		
			$this->smtp_debug("warning: cannot open log file \"".$this->log_file."\"\n");
			
			return false;;
		
		}
		
		flock($fp, lock_ex);
		
		fputs($fp, $message);
		
		fclose($fp);
		
		
		return true;
	
	}
	
	
	function strip_comment($address)
	
	{
	
		$comment = "\([^()]*\)";
		
		while (ereg($comment, $address)) {
		
			$address = ereg_replace($comment, "", $address);
		
		}
		
		
		return $address;
	
	}
	
	
	function get_address($address)
	
	{
	
		$address = ereg_replace("([ \t\r\n])+", "", $address);
		
		$address = ereg_replace("^.*<(.+)>.*$", "\1", $address);
		
		return $address;
	
	}
	
	function smtp_debug($message)
	
	{
	
		if ($this->debug) {
		
			echo $message;
		
		}
	
	}

}

?>