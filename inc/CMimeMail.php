<?php

class CMimeMail {

	var $parts;

   	var $to;

   	var $from;

   	var $cc;

   	var $bcc;

   	var $headers;

   	var $subject;

   	var $body;

   	var $priority;

   	var $charset;
    
    var $check;

	

	function __construct() {

      		$this->parts = array();

      		$this->html_images = array();

      		$this->to =  "";

     		$this->from =  "";

      		$this->cc =  "";

      		$this->bcc =  "";

      		$this->subject =  "";

      		$this->body =  "";

      		$this->headers =  "";

      		$this->priority= 1;

      		$this->message_all = "";
            
            $this->check = 0;

      		

		if(!$this->charset) $this->charset="big5";

      		

   	}

	

	function add_html_image($message, $name = '', $ctype='application/octet-stream'){

                $this->html_images[] = array( 'message'   => $message,

                                              'name'   => $name,

                                              'ctype' => $ctype,

                                              'cid'    => md5(uniqid(time())) );

        }

	

   	function add_attachment($message, $name, $ctype) {

      		$this->parts[] = array ("ctype"   => $ctype,

                              "message" => $message,

                              "encode"  => $encode,

                              "name"    => $name);

   	}

		

   	function build_message($part) {

      		$message = $part["message"];

      		$message = chunk_split(base64_encode($message));

      		$encoding =  "base64";

      		if($part["cid"]) return  "Content-Type: ".$part["ctype"].($part["name"]? ";\n\tname=\"".$part["name"]."\"" :  "")."\nContent-Transfer-Encoding: $encoding\nContent-ID: <".$part['cid'].">\n\n$message\n";

      		else  return  "Content-Type: ".$part["ctype"].($part["name"]? ";\n\tname=\"".$part["name"]."\"" :  "")."\nContent-Transfer-Encoding: $encoding\nContent-Disposition: attachment;\n\tfilename=\"".$part["name"]."\"\n\n$message\n";

   	}



   	function build_multipart() {

      		$boundary =  "b".md5(uniqid(time()));

      		$multipart = "Content-Type: multipart/mixed; boundary=\"$boundary\"\n\nThis is a MIME encoded message.\n\n";

		if (!empty($this->body)) $multipart.=$this->build_html($boundary);

		for($i = sizeof($this->parts)-1; $i >= 0; $i--){

         		$multipart .= "--$boundary"."\n".$this->build_message($this->parts[$i]);

      		}

      		return $multipart.=  "--$boundary--\n";

      		

   	}

   	

   	function build_html($orig_boundary){

                $sec_boundary = '=_'.md5(uniqid(time()));

                $thr_boundary = '=_'.md5(uniqid(time()));



                if(count($this->html_images) == 0 || count($this->parts) ==0){

                        $htmlpart.= '--'.$orig_boundary."\n";

                        $htmlpart.= 'Content-Type: multipart/alternative;'.chr(13).chr(10).chr(9).'boundary="'.$sec_boundary."\"\n\n\n";

                        

                       /* $htmlpart.= '--'.$sec_boundary."\n";

                        $htmlpart.= 'Content-Type: text/plain'."\n";

                        $htmlpart.= 'Content-Transfer-Encoding: base64'."\n\n";

                        $htmlpart.= chunk_split(base64_encode($this->html_text))."\n\n";*/

                        

                        $htmlpart.= '--'.$sec_boundary."\n";

                        $htmlpart.= 'Content-Type: text/html; charset="'.$this->charset.'"'."\n";

                       // $htmlpart.= 'Content-Transfer-Encoding: base64'."\n\n";

                       // $htmlpart.= chunk_split(base64_encode($this->body))."\n\n";

                        $htmlpart.= 'Content-Transfer-Encoding: quoted-printable'."\n\n";

                        $htmlpart.= $this->quoted_printable_encode($this->body)."\n\n";

                        $htmlpart.= '--'.$sec_boundary."--\n\n";

                }else{

                        $htmlpart.= '--'.$orig_boundary."\n";

                        $htmlpart.= 'Content-Type: multipart/related;'.chr(13).chr(10).chr(9).'boundary="'.$sec_boundary."\"\n\n\n";



                        $htmlpart.= '--'.$sec_boundary."\n";

                        $htmlpart.= 'Content-Type: multipart/alternative;'.chr(13).chr(10).chr(9).'boundary="'.$thr_boundary."\"\n\n\n";



                        /*$htmlpart.= '--'.$thr_boundary."\n";

                        $htmlpart.= 'Content-Type: text/plain'."\n";

                        $htmlpart.= 'Content-Transfer-Encoding: base64'."\n\n";

                        $htmlpart.= chunk_split(base64_encode($this->html_text))."\n\n";*/

                        

                        $htmlpart.= '--'.$thr_boundary."\n";

                        $htmlpart.= 'Content-Type: text/html; charset="'.$this->charset.'"'."\n";

                       // $htmlpart.= 'Content-Transfer-Encoding: base64'."\n\n";

                       // $htmlpart.= chunk_split(base64_encode($this->body))."\n\n";

                       $htmlpart.= 'Content-Transfer-Encoding: quoted-printable'."\n\n";

                        $htmlpart.= $this->quoted_printable_encode($this->body)."\n\n";

                        $htmlpart.= '--'.$thr_boundary."--\n\n";



                        for($i=0; $i<count($this->html_images); $i++){

                                $htmlpart.= '--'.$sec_boundary."\n";

                                $htmlpart.= $this->build_message($this->html_images[$i]);

                        }



                        $htmlpart.= "--".$sec_boundary."--\n\n";

                }

                return $htmlpart;

        }

	function quoted_printable_encode($input = "quoted-printable encoding test string", $line_max = 76) {

		$hex = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');

		$lines = preg_split("/(?:\n|\r|\n)/", $input);

		$eol = "\n";

		$escape = "=";

		$output = "";

		while( list(, $line) = each($lines) ) {

			//$line = rtrim($line); // remove trailing white space -> no =20\n	necessary

			$linlen = strlen($line);

			$newline = "";

			for($i = 0; $i < $linlen; $i++) {

				$c = substr($line, $i, 1);

				$dec = ord($c);

				if ( ($dec == 32) && ($i == ($linlen - 1)) ) { // convert space at eol only

					$c = "=20"; 

				} elseif ( ($dec == 61) || ($dec < 32 ) || ($dec > 126) ) { //always encode "\t", which is *not* required

					$h2 = floor($dec/16); $h1 = floor($dec%16); 

					$c = $escape.$hex["$h2"].$hex["$h1"]; 

				}

				if ( (strlen($newline) + strlen($c)) >= $line_max ) { // CRLF is not counted

					$output .= $newline.$escape.$eol; // soft line break; "=\n" is okay

					$newline = "";

				}

				$newline .= $c;

			} // end of for

			$output .= $newline.$eol;

		}

		return trim($output);

	}

   	function get_mail($complete = true) {

		global $remote_ip;

		//replace imagename to cid  

		if(is_array($this->html_images) AND count($this->html_images) > 0){

			for($i=0; $i<count($this->html_images); $i++) $this->body = ereg_replace($this->html_images[$i]['name'], 'cid:'.$this->html_images[$i]['cid'], $this->body);

		}

		$mime =  "";

		$this->message_all = $mime;

		$this->headers .= "Subject: ".$this->subject."\n";

		$this->headers .= "To: ".$this->to. "\n";

		if (!empty($this->from)) $this->headers .= "From: ".$this->from. "\n";

		if (!empty($this->cc))   $this->headers .= "Cc: ".$this->cc. "\n";

		if (!empty($this->bcc))  $this->headers .= "Bcc: ".$this->bcc. "\n";

		$mime.="X-Mailer:Power jay mailer\n";

		$mime.="X-Comment: This message was sent from" . $remote_ip . "\n";

		$mime.="X-Priority: ".$this->priority."\n";

		$mime.="MIME-Version: 1.0\n".$this->build_multipart();

		$this->message_all = $this->headers . $mime;

		return $mime;

   	}



   	function send($rc=1) {
      		global $username,$password;

      		if ($rc) {
				$aBcc = explode(",", $this->bcc);
               
				if (!$aBcc) $aBcc = array();
				$aBcc[] = $this->to;
			//echo print_r($aBcc);exit;  	
				foreach($aBcc as $sMailTo) {
                //echo 222;exit;    
					$this->to = $sMailTo;
					$this->bcc = '';
					$mime = $this->get_mail(false);
					$fp = fsockopen(_SYS_SMTP_HOST,_SYS_SMTP_PORT);
					$data = substr(fgets($fp,1024),0,3);

					if ($data != "220") {

						echo "<div align='center'>&nbsp;<p>&nbsp;<p>smtp error!</div>";

						die;

					}
					fputs($fp,"HELO "._SYS_SMTP_HOST."\n");

					$data = fgets($fp,1024);
                    
                    if ($this->check){
                        fputs($fp, "AUTH LOGIN"."\n");
                        fputs($fp, base64_encode(_SYS_SMTPUSER)."\n");
                        fputs($fp, base64_encode(_SYS_SMTPPASS)."\n");
                    }
                    
                    // FROM
					fputs($fp, "MAIL FROM:<" . $this->from . ">\n");

					$data = fgets($fp,1024);
					//fputs($fp,"RCPT TO: <" . $this->to . ">\n");
					fputs($fp,"RCPT TO: <" . $sMailTo . ">\n");

					$data = fgets($fp,1024);

					fputs($fp,"DATA\n");

					$data = fgets($fp,1024);
					fputs($fp, "From:".$this->from."\n"); 

			
					//fputs($fp, "To:".$this->to."\n"); 
					fputs($fp, "To:".$sMailTo."\n"); 
				
					fputs($fp, "Subject:".$this->subject."\n"); 

					fputs($fp, $mime . "\n.\n");

					$data = fgets($fp,1024);
  
					if (ereg("^250", $data)) {
						continue;
						//return true; 
					} else {
						return false; 
					}
					fputs($fp,"QUIT\n");

					$data = fgets($fp,1024);
    
					sleep(1);
					fclose($fp);
				}
      		} else  mail($this->to, $this->subject, "", $mime);

  		return true;

   	}
	
	/**
	* @desc 將信件寄件者收件者中文字編碼
	* @usage sChgCharSet("諾亞"); or sChgCharSet(iconv("UTF-8","Big5","諾亞"),"Big5");
	* @created 2012/04/11
	*/
	function sChgCharSet($msg="",$charset="UTF-8") {
		if (!$msg) return "";
		return "=?".$charset."?B?".base64_encode($msg)."?=";
	}


}

?>