<?php
/**
 * IMAP_access. Utility class for processing a mailbox for applications that
 * want to grab the body text and do something with it e.g. create a new
 * todo list item. Enough clues in there that it can be expanded to do
 * a lot more if required - e.g. also grab file attachments.
 * 
 * Based on several part-working tutorials (fixed), the PHP manual examples 
 * and testing on real email samples from Gmail, Thunderbird and MS outlook 2010.
 * (flatten_parts is the tricky bit and was from here: 
 * https://www.electrictoolbox.com/php-imap-message-parts/ this is a nifty implementation
 * of an idea from the PHP manual user notes for imap_fetchstructure) 
 * 
 * Use like:
 * $im = new IMAP_access();
 * if(!$im->open(...parameters...)){
 *     //...failed
 * }
 * $overview = $im->get_overview();
 * foreach($overview as $msg){
 *      //...loop through doing stuff
 *      $im->get_body($msg);
 *      //e.g. do something with $im->body_plain
 * }
 * $im->close();
 * 
 */

class IMAP_access {

    protected $connection;
    protected $message_number;
    
    public $message_overview = [];
    
    public $body_HTML = '';
    public $body_HTML_charset = '';
    
    public $body_plain = '';
    public $body_plain_charset = '';
    
    public $attachments = [];
    public $get_attachments = false;
    
    public function __construct() {
        //...
    }

    public function open($server, $username, $password, $options = 0, $retries = 1){
    
        $this->connection = @imap_open($server, $username, $password, $options, $retries);

        return $this->connection !== false;
    }

    public function close($do_expunge = false){

        if($do_expunge){
            //delete any marked messages before closing
            imap_expunge($this->connection);
        }
        imap_close($this->connection);
    
    }

    public function get_overview(){

        $message_check = imap_check($this->connection);
        $message_total = $message_check->Nmsgs;
        $overview = imap_fetch_overview($this->connection, "1:$message_total", 0);
        $size = count($overview);
        
        for($i = $size - 1; $i >= 0; $i--){
            $val = $overview[$i];
            foreach($val as &$v){
                $v = $this->mime_header_decode($v);
            }
            
            $this->message_overview[] = $val;
        }
        
        return $this->message_overview;
     
    }

    public function delete_message($msg_obj){
        $this->message_number = $msg_obj->msgno;
        //could collect these up and do 1 delete multiple
        imap_delete($this->connection, $this->message_number);
    
    }

    public function get_body($msg_obj){

        $this->body_HTML = '';
        $this->body_HTML_charset = '';
        
        $this->body_plain = '';
        $this->body_plain_charset = '';
        
        $this->attachments = [];

        $this->message_number = $msg_obj->msgno;
        
        $structure = @imap_fetchstructure($this->connection, $this->message_number);
        //print_r($structure);    
        $flattened_parts = [];
        
        if($structure === false){
            return false;
        }
        
        if(isset($structure->parts)){
            $flattened_parts = $this->flatten_parts($structure->parts);
        }else{
            $flattened_parts['1'] = $structure;
        }
    
        //echo var_dump($flattened_parts, true);
    
        foreach($flattened_parts as $part_number => $part) {
            //print_r($part);
            switch($part->type){
             
                case TYPETEXT:
                    //check for a plain text attachment
                    $disposition = (isset($part->disposition) ? strtolower($part->disposition) : '');
                    
                    if($disposition == 'attachment'){
                        $filename = $this->get_filename_from_part($part);
                        
                        if($filename) {
                            // it's an attachment
                            $this->attachments[] = [
                              'type' => $part->type,
                              'subtype' => $part->subtype,
                              'filename' => $filename,
                              'data' => $this->get_attachments ? $this->get_part($part_number, $part->encoding) : '',
                              'inline' => false,
                              'charset' => $this->get_charset_from_part($part),
                              'part' => $part_number //saved for deferred fetch
                           ];

                        }
                        else {
                            // unknown - ignore
                        }
                    
                    }else{
                        // the HTML or plain text part of the email
                        $message = $this->get_part($part_number, $part->encoding);
                        
                        if(strtolower($part->subtype) == 'plain'){
                            //plain text
                            $this->body_plain .= $message;
                            $this->body_plain_charset = $this->get_charset_from_part($part);
                        }else{
                            //html
                            $this->body_HTML .= $message;
                            $this->body_HTML_charset = $this->get_charset_from_part($part);
                        }
                    }
                break;
                case TYPEMULTIPART:
                    // multi-part headers, can skip
                break;
                case TYPEMESSAGE:
                    // bounced message - just add this to plain text body
                    $this->body_plain .= $this->get_part($part_number, $part->encoding) . "\n";
                break;
                case TYPEAPPLICATION: // application
                case TYPEAUDIO: // audio
                case TYPEIMAGE: // image
                case TYPEVIDEO: // video
                case TYPEMODEL: // other
                case TYPEOTHER: // other
                default:
                    $filename = $this->get_filename_from_part($part);

                    if($filename) {
                        // it's an attachment - id holds the cid for embedded
                         if(isset($part->id)) {
                            $id = str_replace(array('<', '>'), '', $part->id);

                            $this->attachments[$id] = array(
                                'type' => $part->type,
                                'subtype' => $part->subtype,
                                'filename' => $filename,
                                'data' => $this->get_attachments ? $this->get_part($part_number, $part->encoding) : '',
                                'inline' => true,
                                'part' => $part_number //saved for deferred fetch
                            );
                        }else {
                            $this->attachments[] = array(
                                'type' => $part->type,
                                'subtype' => $part->subtype,
                                'filename' => $filename,
                                'data' => $this->get_attachments ? $this->get_part($part_number, $part->encoding) : '',
                                'inline' => false,
                                'part' => $part_number //saved for deferred fetch
                            );
                        }
                    } else {
                        // unknown- ignore
                    }
                break;
            }
        }
        
        //workround for open php iconv bug with //IGNORE
        ini_set('mbstring.substitute_character', "none"); //space as subst, "none" to remove  
        if($this->body_plain != ''){
            $this->body_plain = mb_convert_encoding($this->body_plain, 'UTF-8', 
                                    $this->body_plain_charset != '' ? $this->body_plain_charset : 'pass');
        }
        if($this->body_HTML != ''){
            $this->body_HTML  = mb_convert_encoding($this->body_HTML, 'UTF-8', 
                                    $this->body_HTML_charset != '' ? $this->body_HTML_charset : 'pass');
        }
        return true;
    
    }

    protected function flatten_parts($message_parts, $flattened_parts = [], $prefix = '', $index = 1, $full_prefix = true) {
        //recursive flatten
        foreach($message_parts as $part) {
            $flattened_parts[$prefix . $index] = $part;
            if(isset($part->parts)) {
                if($part->type == 2) {
                    $flattened_parts = $this->flatten_parts($part->parts, $flattened_parts, $prefix . $index . '.', 0, false);
                }elseif($full_prefix) {
                    $flattened_parts = $this->flatten_parts($part->parts, $flattened_parts, $prefix . $index . '.');
                }else {
                    $flattened_parts = $this->flatten_parts($part->parts, $flattened_parts, $prefix);
                }
                unset($flattened_parts[$prefix . $index]->parts);
            }
            $index++;
        }
    
        return $flattened_parts;
                
    }
    
    protected function get_part($part_number, $encoding) {
        //print_r($part_number);
        $data = imap_fetchbody($this->connection, $this->message_number, $part_number);
        
        switch($encoding) {
            case ENC7BIT: return $data; // 7BIT
            case ENC8BIT: return $data; // 8BIT
            case ENCBINARY: return $data; // BINARY
            case ENCBASE64: return base64_decode($data); // BASE64
            case ENCQUOTEDPRINTABLE: return quoted_printable_decode($data); // QUOTED_PRINTABLE
            case ENCOTHER: 
            default:
                return $data; // OTHER
        }
    }
    
    protected function get_filename_from_part($part) {
        $filename = '';

        if($part->ifdparameters) {
            foreach($part->dparameters as $object) {
                if(strtolower($object->attribute) == 'filename') {
                    $filename = $object->value;
                }
            }
        }

        if(!$filename && $part->ifparameters) {
            foreach($part->parameters as $object) {
                if(strtolower($object->attribute) == 'name') {
                    $filename = $object->value;
                }
            }
        }

        return $this->mime_header_decode($filename);

    }

    protected function get_charset_from_part($part) {
        $charset = '';

        if($part->ifparameters) {
            foreach($part->parameters as $object) {
                if(strtolower($object->attribute) == 'charset') {
                    $charset = $object->value;
                }
            }
        }

        return $charset;
    }

    protected function mime_header_decode($h){
        return iconv_mime_decode($h, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, 'UTF-8');
    }

}
/* end */