<?php
namespace CTH\ChatBlock;
/**
 * PHP Chat Block Component (0.0.1)
 * A dialogue chat block display component for php project.
 * A UI component for conversational novel community.
 * Date: 30 Aug, 2021
 * Author: Tang Hoong
 */
class ChatUI
{
    public $colonList;
    public $narratorList;
    public $roles;
    public $lines;
    public $dialogue;
    function __construct()
    {
        // 
    }
    public function feed($data='')
    {
        // $this->colonList = [':','：'];
        $chat['casts']  = 0;
        $chat['dialog'] = 0;
        $chat['roles']  = [];
        $chat['lines']  = [];
        $rolesData = strstr($data, "---", true);
        $linesData = strstr($data, "---");
        // structure roles
        $rolesArray = array_values(array_filter(explode(PHP_EOL,$rolesData)));
        $chat['casts'] = count($rolesArray);
        foreach($rolesArray as $roleKey => $roleVal)
        {
            $tempCast = [];
            // foreach($this->colonList as $colon)
            // {
                $tempArray = explode("@",$roleVal);
                if(isset($tempArray))
                {
                    // var_dump($tempArray);
                    list($name, $img) = $tempArray;
                    $tempCast['name']  = $name;
                    $tempCast['img']   = $img;
                    array_push($chat['roles'],$tempCast);
                }
            // }
        }
        // structure lines
        $linesArray = array_values(array_filter(explode(PHP_EOL,$linesData)));
        foreach($linesArray as $lineKey => $lineVal)
        {
            if($lineVal != '---')
            {
                $chat['dialog'] = $chat['dialog'] + 1;
                $tempLine = [];
                // foreach($this->colonList as $colon)
                // {
                    // var_dump($colon);
                    // $tempArray = explode($colon,$lineVal);
                    $tempArray = explode(":",$lineVal);
                    if(isset($tempArray))
                    {
                        // var_dump($tempArray);
                        list($name, $sentence) = $tempArray;
                        $tempLine['name']  = $name;
                        $tempLine['sentence']   = $sentence;
                        array_push($chat['lines'],$tempLine);
                    }
                // }
            }
        }
        $this->dialogue = $chat;
    }
    /**
     * To allow using as Json format for frontend rendering
     */
    public function json(){
        return json_encode($this->dialogue);
    }
    /**
     * Set Colon
     */
    public function setColon($colonArray = []){
        $this->colonList = $colonArray;
    }
    /**
     * Set Colon
     */
    public function setNarrator($narratorArray = []){
        $this->narratorList = $narratorArray;
    }
    /**
     * Using default html rendered chat blocks
     */
    public function render(){
        $tempHtml = '';
        foreach($this->dialogue['lines'] as $dialogue)
        {
            switch($dialogue['name'])
            {
                case 'Narator': 
                    $tempHtml .= $this->role_narator($dialogue);
                break;
                case 'Flipcard': 
                    $tempHtml .= $this->render_flipcard_holder($dialogue);
                break;
                case 'Image': 
                    $tempHtml .= $this->render_image_holder($dialogue);
                break;
                case 'MP3': 
                case 'Background': 
                    $tempHtml .= $this->render_sound_holder($dialogue);
                break;
                case 'Youtube': 
                    $tempHtml .= $this->render_video_holder($dialogue);
                break;
                case 'Decision': 
                    $tempHtml .= $this->render_decisions_holder($dialogue);
                break;
                case $this->dialogue['roles'][0]['name']: 
                    $tempHtml .= $this->role_rightSide($dialogue);
                break;
                default: 
                    $tempHtml .= $this->role_leftSide($dialogue);
                break;
            }
        }
        return $tempHtml;
    }
    public static function renderCss()
    {
        return file_get_contents('../imessage.css');
    }
    // Multimedia
    private function render_flipcard_holder($dialogue)
    {
        $link = $this->fn_valid_link($dialogue['sentence']);
        $url_components = parse_url($link);
        parse_str($url_components['query'], $params);
        $title = (isset($params['title'])?str_replace('+',' ',$params['title']):null);
        $desc = (isset($params['desc'])?str_replace('+',' ',$params['desc']):null);
        $tempHtml   = '';
        $tempHtml  .= '<div class="flip-card">';
        $tempHtml  .= '<div class="flip-card-inner">';
        $tempHtml  .= '<div class="flip-card-front">';
        $tempHtml  .= '<img src="'.$link.'" alt="Flipcard" style="width:100%;height:100%;">';
        $tempHtml  .= '</div>';
        $tempHtml  .= '<div class="flip-card-back">';
        if($title)
        {
            $tempHtml  .= '<h1>'.$title.'</h1>';
        }
        if($desc)
        {
            $tempHtml  .= '<p>'.$desc.'</p>';
        }
        $tempHtml  .= '</div>';
        $tempHtml  .= '</div>';
        $tempHtml  .= '</div>';
        return $tempHtml;
    }
    private function render_image_holder($dialogue)
    {
        $link = $this->fn_valid_link($dialogue['sentence']);
        $tempHtml   = '<div class="container-image">';
        $tempHtml  .= '<img src="'.$link.'" alt="Image" style="width:100%;height:100%;">';
        $tempHtml  .= '</div>';
        return $tempHtml;
    }
    private function render_sound_holder($dialogue)
    {
        $link = $this->fn_valid_link($dialogue['sentence']);
        $tempHtml   = '<div class="container-mp3">';
        $tempHtml  .= '<audio controls loop style="width:100%;">';
        $tempHtml  .= '<source src="'.$link.'" type="audio/mpeg">';
        $tempHtml  .= 'Your browser does not support the audio element.';
        $tempHtml  .= '</audio>';
        if($dialogue['name'] == 'Background')
        {
        $tempHtml  .= '<div class="text-muted text-bold text-center">Background Music</div>';
        }
        $tempHtml  .= '</div>';
        return $tempHtml;
    }
    private function render_video_holder($dialogue)
    {
        $link = $this->fn_valid_link($dialogue['sentence']);
        $tempHtml   = '<div class="container-youtube">';
        $tempHtml  .= '<iframe frameborder="0" width="100%" height="90%" src="'.$link.'"></iframe>';
        $tempHtml  .= '</div>';
        return $tempHtml;
    }
    private function render_decisions_holder($dialogue)
    {
        $optionList = explode(',',$dialogue['sentence']);
        $tempHtml   = '<p class="text-center comment">Your decisions are</p>';
        $tempHtml  .= '<div class="container-decision">';
        foreach($optionList as $option)
        {
            $tempHtml  .= '<div class="decision-option" data-choose="'.$option.'">'.$option.'</div>';
        }
        $tempHtml  .= '</div>';
        return $tempHtml;
    }
    // Misc
    private function fn_filter($dialogue)
    {
        $newStr = strip_tags($dialogue,"<b><i><u>");
        return trim($newStr);
    }
    private function fn_valid_link($dialogue)
    {
        return $dialogue;
        // $url = filter_var($dialogue, FILTER_SANITIZE_URL);
        // if (filter_var($url, FILTER_VALIDATE_URL)) {
        //   return $url;
        // }
        // return false;
    }
    // Chat Blocks
    private function role_narator($dialogue)
    {
        $sentence  = $this->fn_filter($dialogue['sentence']);
        $tempHtml  = '<p class="comment">'.$sentence.'</p>';
        return $tempHtml;
    }
    private function role_leftSide($dialogue)
    {
        $sentence = $this->fn_filter($dialogue['sentence']);
        $tempHtml  = '<div class="imessage">';
        $tempHtml .= '<div class="chat-name chat-name-them">';
        $chatHeaderImg = $this->loadChatHeaderImg($dialogue['name']);
        if($chatHeaderImg == false)
        {
            $tempHtml .= $dialogue['name'];
        }else{
            $tempHtml .= '<img class="chat-header" src="'.$this->loadChatHeaderImg($dialogue['name']).'">'.$dialogue['name'];
        }
        $tempHtml .= '</div>';
        $tempHtml .= '<p class="from-them">'.$sentence.'</p>';
        $tempHtml .= '</div>';
        return $tempHtml;
    }
    private function role_rightSide($dialogue)
    {
        $sentence  = $this->fn_filter($dialogue['sentence']);
        $tempHtml  = '<div class="imessage">';
        $tempHtml .= '<div class="chat-name chat-name-me">';
        $chatHeaderImg = $this->loadChatHeaderImg($dialogue['name']);
        if($chatHeaderImg == false)
        {
            $tempHtml .= $dialogue['name'];
        }else{
            $tempHtml .= '<img class="chat-header" src="'.$this->loadChatHeaderImg($dialogue['name']).'">'.$dialogue['name'];
        }
        $tempHtml .= '</div>';
        $tempHtml .= '<p class="from-me">'.$sentence.'</p>';
        $tempHtml .= '</div>';
        return $tempHtml;
    }
    private function loadChatHeaderImg($castName)
    {
        foreach($this->dialogue['roles'] as $cast)
        {
            if($cast['name'] == $castName)
            {
                return $cast['img'];
            }
        }
        return false; // If not match
    }
} // EOF
?>
