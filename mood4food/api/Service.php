<?php

interface Service
{
    public function request($join, $where, $having, $order, $limit, $offset);
}

?>