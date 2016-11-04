<?php

namespace Newance\Teamleader;

use Newance\Teamleader\Exceptions\BadResponse;

class Teamleader
{
    /** @var string */
    protected $apiGroup;

    /** @var string */
    protected $apiSecret;

    public function __construct($apiGroup, $apiSecret)
    {
        $this->apiGroup = $apiGroup;
        $this->apiSecret = $apiSecret;
    }

    /**
     * Get tickets
     * types = new, open, waiting_for_client, escalated_thirdparty, not_closed, closed
     *
     * @param array $type
     * @return array
     */
    public function getTickets(array $type = [])
    {
        $params = $type;
        $teamleaderResponse = $this->makeRequest('https://www.teamleader.be/api/getTickets.php', $params);

        return $teamleaderResponse;
    }

    /*
     * Get all Tasks
     *
     * @return array
     */
    public function getAllTasks()
    {
        $params = [
            'amount'    => 100,
            'pageno'    => 1
        ];

        $teamleaderResponse = $this->makeRequest('https://www.teamleader.be/api/getTasks.php', $params);

        return $teamleaderResponse;
    }

    /**
     * Get Meetings
     *
     * @param array $params
     * @return array
     */
    public function getMeetings(array $params = [])
    {
        $teamleaderResponse = $this->makeRequest('https://www.teamleader.be/api/getMeetings.php', $params);

        return $teamleaderResponse;
    }

    /**
     * @param int $meetingId
     */
    public function getMeetingInformation(int $meetingId)
    {
        $params = [
            'meeting_id' => $meetingId
        ];

        $teamleaderResponse = $this->makeRequest('https://www.teamleader.be/api/getMeeting.php', $params);

        dd($teamleaderResponse);

        return $teamleaderResponse;
    }

    /**
     * @param string $url
     *
     * @return array
     */
    public function makeRequest($url, $params)
    {
        $fields = [
            "api_group"=> $this->apiGroup,
            "api_secret"=> $this->apiSecret
        ];

        $fields = array_merge($fields, $params);

        // Make the POST request using Curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        // Decode and display the output
        $apiOutput =  curl_exec($ch);
        $jsonOutput = json_decode($apiOutput, true);
        $output = ($jsonOutput ? $jsonOutput : $apiOutput);

        // Clean up
        curl_close($ch);

        return $output;
    }
}
