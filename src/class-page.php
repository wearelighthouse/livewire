<?php

class Page
{

    /**
     * @return void
     */
    public function __construct($itemId)
    {

        global $apiUsername, $apiKey;

        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Accept: application/vnd.gathercontent.v0.5+json'));
        curl_setopt( $ch, CURLOPT_USERPWD, $apiUsername . ':' . $apiKey);
        curl_setopt( $ch, CURLOPT_URL, 'https://api.gathercontent.com/items/' . $itemId);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        $response = json_decode(curl_exec($ch));
        curl_close($ch);

        if (!isset($response->data->config[0])) {
          return;
        }

        foreach ($response->data->config[0]->elements as $element) {
            $field = $this->slugify($element->label);

            if ($field) {
                $this->{$field} = $element->value;
            }
        }
    }

    private function slugify($label)
    {
      // replace non letter or digits by -
      $label = preg_replace('~[^\pL\d]+~u', '_', $label);

      // transliterate
      $label = iconv('utf-8', 'us-ascii//TRANSLIT', $label);

      // remove unwanted characters
      $label = preg_replace('~[^_\w]+~', '', $label);

      // trim
      $label = trim($label, '_');

      // remove duplicate -
      $label = preg_replace('~_+~', '_', $label);

      // lowercase
      $label = strtolower($label);

      if (empty($label)) {
        return false;
      }

      return $label;
    }
}
