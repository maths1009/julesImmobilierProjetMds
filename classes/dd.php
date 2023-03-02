<?php

class DdService
{
    public function show($datas)
    {
        echo ("<br>");
        echo ("<pre>");
        echo ("<code>");
        var_dump($datas);
        echo ("</code>");
        echo ("</pre>");
    }
}
