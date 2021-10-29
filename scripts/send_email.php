<?php

  $content = "";
  $interestsArray = [];
  $interests = "Kiinnostus:\r\n";
  $contact = "Yhteystiedot:\r\n";
  $company = "";
  $email = "";
  $additional = "Lisätietoa:\r\n";

  $swap_int = array(
    "interestGoogleAds" => "Google Ads",
    "interestSome" => "Some",
    "interestNative" => "Natiivimainonta",
    "interestWebsite" => "Verkkosivut"
  );

  if ($_POST) {
    foreach($_POST as $key => $val) {
      if (strpos($key, "interest") !== false && $val == 1) {
        $tmp = $key;
        foreach($swap_int as $keyInt => $valInt) {
          $tmp = str_replace($keyInt, $valInt, $tmp);
        }

        array_push($interestsArray, $tmp);
      }
      elseif (strpos($key, "contact") !== false) {
        if ((strpos($key, "contactName") !== false)) {
          $contact .= "Nimi: " . $val . "\r\n";
        }
        if ((strpos($key, "contactCompany") !== false)) {
          $company = $val;
          $contact .= "Yritys: " . $val . "\r\n";
        }
        if (strpos($key, "contactPhone") !== false) {
          $contact .= "Puhelin: " . $val . "\r\n";
        }
        if (strpos($key, "contactEmail") !== false) {
          $email = $val;
          $contact .= "Sähköposti: " . $val . "\r\n";
        }
      }
      elseif (strpos($key, "additional") !== false) {
        $additional .= $val . "\r\n";
      }
    }

    foreach($interestsArray as $interest) {
      $interests .= "- " . $interest . "\r\n";
    }

    $content .= $interests . "\r\n";
    $content .= $contact . "\r\n";
    $content .= $additional . "\r\n";
  }

  else
    die("no data");

  // create a demo mode option
  define("DEMO", false);

  $template_file = "./email_template.php";

  // basic email info to send
  $email_to = "info@markkinointisuunta.fi";
  $subject = "Yhteydenotto: " . $company . " (" . implode(", ", $interestsArray) . ")";

  // create the swap variables array
  $swap_var = array(
    "{SITE_ADDR}" => "https://www.markkinointisuunta.fi",
    "{EMAIL_TITLE}" => "Kiitos yhteydenotostanne!",
    "{TEXT}" => "Olemme teihin yhteydessä pikaisesti, tyypillisesti viimeistään seuraavan työpäivän aikana.<br>
    <br>
    Tähän sähköpostiviestiin ei tarvitse vastata.<br>
    <br>
    Ystävällisin terveisin,<br>
    Markkinointi Suunta"
  );

  $swap_var_summary = array(
    "{SITE_ADDR}" => "",
    "{EMAIL_TITLE}" => "Yhteydenotto verkkosivuilta",
    "{TEXT}" => "<pre>" . $content . "</pre>"
  );

  // email headers
  $headers = "From: Markkinointi Suunta <info@markkinointisuunta.fi>\r\n";
  $headers .= "Reply-To: info@markkinointisuunta.fi\r\n";
  $headers .= "Return-Path: info@markkinointisuunta.fi\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=ISO-8895-1\r\n";

  // create the html message
  if (file_exists($template_file)) {
    $message = file_get_contents($template_file);
    $summary = file_get_contents($template_file);
  } else
    die("unable to locate the template file");

  // search replace all the swap_vars
  foreach(array_keys($swap_var) as $key) {
    if (strlen($key) > 2 && trim($key) != "")
      $message = str_replace($key, $swap_var[$key], $message);
  }

  foreach(array_keys($swap_var_summary) as $key) {
    if (strlen($key) > 2 && trim($key) != "")
      $summary = str_replace($key, $swap_var_summary[$key], $summary);
  }

  if (DEMO)
    die("no email was sent on purpose");

  // send email
  if ( mail($email_to, $subject, $summary, $headers) && mail($email, "Kiitos yhteydenotostanne!", $message, $headers) )
    echo "success";
  else
    echo "not sent";
?>