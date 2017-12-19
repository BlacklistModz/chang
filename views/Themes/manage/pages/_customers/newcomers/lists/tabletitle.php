<?php

$th = "";
$tr = array();
$cal = 0;
$this->titleStyle = !empty($this->titleStyle) ? $this->titleStyle: '';

function dataTH($value, $cal) {

    if( !empty($value['subtext']) ){
        $value['key'] .= ' sub';
    }

    $attr = ' class="'.$value['key'].'"';
    if( !empty($value['colspan']) ){
        $attr .= ' colspan="'.$value['colspan'].'"';
    }
    else{
        $attr .= ' data-col="'.$cal.'"';
    }

    if( !empty($value['rowspan']) ){
        $attr .= ' rowspan="'.$value['rowspan'].'"';
    }

    $th = '<th'.$attr.'>'.

        ( !empty($value['sort'])
            ? '<span class="hdr-text sorttable"><a class="link-sort mrs" data-sort-val="'.$value['sort'].'">'.$value['text'].'</a><i class="icon-long-arrow-up up"></i><i class="icon-long-arrow-down down"></i></span>'
            : '<span class="hdr-text">'.$value['text'].'</span>'
        ).

        ( !empty($value['subtext'])
            ? '<span class="hdr-subtext">('.$value['subtext'].')</span>'
            : ''
        ).

    '</th>';

    return $th;
}

if( $this->titleStyle=='row-2' ){

    $tr = '';
    foreach ($this->tabletitle as $key => $rows) {

        if( isset($next) ){
            $cal = $next-1;
            unset($next);
        }
        
        foreach ($rows as $i => $cell) {

            
            $th .= dataTH($cell, $cal);
            $cal++;

            $colspan = isset($cell['colspan']) ? $cell['colspan'] : 1;

            if( $colspan > 1 ){
                $next = $cal;
                $cal+=$cell['colspan']-1;
            }
        }

        $tr .= "<tr>{$th}</tr>";
        $th = '';
    }
}
else{
    foreach ($this->tabletitle as $key => $value) {
        $th .= dataTH($value, $cal);
        $cal++;
    }

    $tr = "<tr>{$th}</tr>";
}

$cls = !empty($this->titleStyle) ? ' class="'.$this->titleStyle.'"':'';
$tabletitle = "<table{$cls}><thead>{$tr}</thead></table>";