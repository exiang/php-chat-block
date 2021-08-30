<?php
/**
 * PHP Chat UI (0.0.1)
 * 
 * A UI component for conversational novel community
 * 
 * Date: 30 Aug, 2021
 * 
 * Author: Charlie Tang Hoong
 */
class ChatUI
{
    public $roles;
    public $lines;
    public $dialogue;
    function __construct($data='')
    {
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
            $tempArray = explode(":",$roleVal);
            list($name, ,$img) = $tempArray;
            $tempCast['name']  = $name;
            $tempCast['img']   = $img;
            array_push($chat['roles'],$tempCast);
        }
        // structure lines
        $linesArray = array_values(array_filter(explode(PHP_EOL,$linesData)));
        foreach($linesArray as $lineKey => $lineVal)
        {
            if($lineVal != '---')
            {
                $chat['dialog'] = $chat['dialog'] + 1;
                $tempLine = [];
                $tempArray = explode(":",$lineVal);
                list($name, $sentence) = $tempArray;
                $tempLine['name']  = $name;
                $tempLine['sentence']   = $sentence;
                array_push($chat['lines'],$tempLine);
            }
        }
        // var_dump($chat);
        $this->dialogue = $chat;
    }
    public function read(){
        $tempHtml = '';
        foreach($this->dialogue['lines'] as $dialogue)
        {
            switch($dialogue['name'])
            {
                case 'Narator': 
                    $tempHtml .= $this->role_narator($dialogue);
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
    private function role_narator($dialogue)
    {
        $tempHtml  = '<p class="comment">'.$dialogue['sentence'].'</p>';
        return $tempHtml;
    }
    private function role_leftSide($dialogue)
    {
        $tempHtml  = '<div class="imessage">';
        $tempHtml .= '<div class="chat-name chat-name-them">';
        $tempHtml .= '<img class="chat-header" src="'.$this->loadChatHeaderImg($dialogue['name']).'">'.$dialogue['name'];
        $tempHtml .= '</div>';
        $tempHtml .= '<p class="from-them">'.$dialogue['sentence'].'</p>';
        $tempHtml .= '</div>';
        return $tempHtml;
    }
    private function role_rightSide($dialogue)
    {
        $tempHtml  = '<div class="imessage">';
        $tempHtml .= '<div class="chat-name chat-name-me">';
        $tempHtml .= $dialogue['name'].'<img class="chat-header" src="'.$this->loadChatHeaderImg($dialogue['name']).'">';
        $tempHtml .= '</div>';
        $tempHtml .= '<p class="from-me">'.$dialogue['sentence'].'</p>';
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
    }
} // EOF
?>