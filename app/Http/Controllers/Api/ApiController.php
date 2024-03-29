<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ApiController extends Controller
{
    public function getExternalApi() {
        // ambil data menggunakan curl
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://ogienurdiana.com/career/ecc694ce4e7f6e45a5a7912cde9fe131',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        $response = json_decode($response);

        curl_close($curl);

        // Ubah string response menjadi array
        $data = $this->parseApiResponse($response->DATA);

        return $data;
    }

    public function parseApiResponse($response)
    {
        $lines = explode("\n", $response);

        $data = [];
        foreach ($lines as $line) {
            if (!$line) {
                continue;
            }
            $key = explode('|', $lines[0]);
            $fields = explode('|', $line);
            $data[] = [
                $key[0] => $fields[0],
                $key[1] => $fields[1],
                $key[2] => $fields[2],
            ];
        }

        return $data;
    }

    public function searchNIM(Request $request)
    {
        $external_api = $this->getExternalApi();

        // Cari NIM 9352078461
        $result = $this->searchByField($external_api, 'NIM', '9352078461');

        return response()->json($result);
    }

    public function searchName(Request $request)
    {
        $external_api = $this->getExternalApi();

        // Cari nama Turner Mia
        $result = $this->searchByField($external_api, 'NAMA', 'Turner Mia');

        return response()->json($result);
    }

    public function searchYMD(Request $request)
    {
        $external_api = $this->getExternalApi();

        // Cari YMD 20230405
        $result = $this->searchByField($external_api, 'YMD', '20230405');

        return response()->json($result);
    }

    public function searchByField($data, $fields, $value)
    {
        return collect($data)
        ->first(function ($item) use ($fields, $value) {
            return stripos($item[$fields], $value) !== false;
        });
    }
}
