<?php

namespace Dtmcli;

use Psr\Http\Message\ResponseInterface;

class Tcc
{
    public IdGenerator $idGen;
    public string $dtm;
    public string $gid;

    public function __construct(string $dtmUrl, string $gid)
    {
        $this->dtm = $dtmUrl;
        $this->gid = $gid;
        $this->idGen = new IdGenerator();
    }

    /**
     * @param array $body
     * @param string $tryUrl
     * @param string $confirmUrl
     * @param string $cancelUrl
     * @return ResponseInterface
     * @throws \Exception
     */
    public function callBranch(array $body, string $tryUrl, string $confirmUrl, string $cancelUrl): ResponseInterface
    {
        $branchId = $this->idGen->newBranchId();
        $client   = new \GuzzleHttp\Client();
        $response = $client->post($this->dtm . '/registerTccBranch', [
            'json' => [
                'gid' => $this->gid,
                'branch_id' => $branchId,
                'trans_type' => 'tcc',
                'status' => 'prepared',
                'data' => json_encode($body, JSON_UNESCAPED_UNICODE),
                'try' => $tryUrl,
                'confirm' => $confirmUrl,
                'cancel' => $cancelUrl,
            ],
        ]);
        checkStatus($response->getStatusCode());
        return $client->post($tryUrl, [
            'json' => $body,
            'query' => [
                'gid' => $this->gid,
                'trans_type' => 'tcc',
                'branch_id' => $branchId,
                'branch_type' => 'try',
            ],
        ]);
    }
}
