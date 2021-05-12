<?php

require_once "../service.php";

class Proxy implements Service
{
    private $realService;

    public function __construct(Service $realService)
    {
        $this->realService = $realService;
    }

    public function request($join, $where, $having, $order, $limit, $offset)
    {
        $this->logAccess();
        return $this->realService->request($join, $where, $having, $order, $limit, $offset);
    }

    private function logAccess(): void
    {
        $line = date("Y-m-d H:i:s", time())." | ".$_SERVER["REQUEST_URI"];
        file_put_contents("../../logs/requests.txt", $line.PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}

?>