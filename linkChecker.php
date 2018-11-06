<html>
 <head>
  <title>Link Checker</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">

<style>

.container {
        max-width: 500px;
    margin: auto;
}

</style>
  
</head>
 <body>



<h2>URL search</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  <input type="text" name="urlInput">
  <input type="submit" name="submit" value="Search!">  
</form>

<?php

$website = $_POST['urlInput'];

$html = file_get_contents($website);

$website = preg_replace('{/$}', '', $website);

$dom = new DOMDocument();
@$dom->loadHTML($html);

$xpath = new DOMXPath($dom);
$hrefs = $xpath->evaluate("/html/body//a");

for ($i = 0; $i < $hrefs->length; $i++) {
        $href = $hrefs->item($i);
        $url = $href->getAttribute('href');
        $url = ltrim($url, '/');
        $url = isAbsoluteUrl($url) ? $url : $website.'/'.$url;
        get_http_response_code($url);
}



function get_http_response_code($url) {

        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

        /* Get the HTML or whatever is linked in $url. */
        $response = curl_exec($handle);

        /* Check for 404 (file not found). */
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if($httpCode == 200) {
                echo $url . ": <span style='color: green'>ok => $httpCode</span> <br>";
        } else if($httpCode == 301 || $httpCode == 302) {
                echo $url . ": <span style='color: orange'>Redirected => $httpCode</span> <br>";
        } else {
                echo $url . ": <span style='color: red'>Not working => $httpCode</span> <br>";
        }



        curl_close($handle);
}

function isAbsoluteUrl($url)
    {
        if (strpos($url, 'http://') !== false || strpos($url, 'https://') !== false) {
                return true;
              }
              
              return false;
    }

?>

<?php
echo $result;
?>




 </body>
</html>
