<?php

namespace Fallenangelbg\BGNCurrencyTool;

class currencyReadExternal
{
    /**
     * @var string
     */
    private $BNBPage;

    public function __construct()
    {
        // This is the current page with XML for the currencies
        $this->BNBPage = "https://www.bnb.bg/Statistics/StExternalSector/StExchangeRates/StERForeignCurrencies/index.htm?download=xml&amp;search=&amp;lang=BG";
    }

    /**
     * Read, parse and return currencies
     * @return array
     */
    function readCurrency(): array
    {
        /** Read the currencies. This method can be replaced with a Memcached/REDIS or SQL call. The return array should looks like this:
        $returnArray["EUR"]['name'] = "Euro";
        $returnArray["EUR"]['code'] = "EUR";
        $returnArray["EUR"]['quantity'] = "1";
        $returnArray["EUR"]['value'] = 0.51129;
         */
        $returnData = $this->get_currency_page($this->BNBPage);

        // This is a fix for EUR. Euro is not in the table of currencies in the BNB site
        $returnArray["EUR"]['name'] = "Euro";
        $returnArray["EUR"]['code'] = "EUR";
        $returnArray["EUR"]['quantity'] = "1";
        $returnArray["EUR"]['value'] = 0.51129;

        // Add BGN, which is 1:1 conversion ratio
        $returnArray["BGN"]['name'] = "Bulgarian Lev";
        $returnArray["BGN"]['code'] = "BGN";
        $returnArray["BGN"]['quantity'] = "1";
        $returnArray["BGN"]['value'] = 1;

        foreach ($returnData['ROW'] as $id => $data) {
            if ($id != 0) {
                if (!empty($data['REVERSERATE'])) {
                    $returnArray[$data['CODE']]['name'] = $data['NAME_'];
                    $returnArray[$data['CODE']]['code'] = $data['CODE'];
                    $returnArray[$data['CODE']]['quantity'] = $data['RATIO'];
                    $returnArray[$data['CODE']]['value'] = $data['REVERSERATE'];
                }
            }
        }

        return $returnArray ?? array();
    }

    /**
     * Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
     * array containing the HTTP server response header fields and content.
     *
     * @param $url string Web page to curl
     *
     * @return mixed
     */
    private function get_currency_page(string $url)
    {
        $user_agent = 'Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/81.0';

        $options = array(
          CURLOPT_CUSTOMREQUEST  => "GET", //set request type post or get
          CURLOPT_POST           => false, //set to GET
          CURLOPT_USERAGENT      => $user_agent, //set user agent
          CURLOPT_RETURNTRANSFER => true, // return web page
          CURLOPT_HEADER         => false, // don't return headers
          CURLOPT_FOLLOWLOCATION => true, // follow redirects
          CURLOPT_ENCODING       => "", // handle all encodings
          CURLOPT_AUTOREFERER    => true, // set referer on redirect
          CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
          CURLOPT_TIMEOUT        => 120, // timeout on response
          CURLOPT_MAXREDIRS      => 10, // stop after 10 redirects

          CURLOPT_SSL_VERIFYPEER => 0 // Skip SSL errors, we do not care about them

        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $result = curl_getinfo($ch);
        curl_close($ch);

        $result['errno'] = $err;
        $result['errmsg'] = $errmsg;
        $result['content'] = $content;

        if (!empty($result['errno'])) {
            die($result['errmsg']);
        }
        $resultCurrency = $result['content'];

        //parse the XML
        libxml_use_internal_errors(true);
        $returnData = simplexml_load_string($resultCurrency);

        return json_decode(json_encode((array)$returnData), 1);
    }

}