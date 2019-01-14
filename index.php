<?php

require_once('vendor/autoload.php');

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();


$xmltime = XmlTime\XmlTime::create(
    getenv('accesskey'),
    getenv('secretkey'),
    new XmlTime\XmlTimeParser
);


$placeid = $_GET['placeid'] ?? 'norway/oslo';



$response = $xmltime->servicecall(
    'timeservice',
    array('placeid' => $placeid)
);

$locationData = $xmltime->getLocationData($response);

$places = [
    'australia/lord-howe-island' => 'Lord Howe Island',
    'mozambique/maputo' => 'Maputo',
    'netherlands/amsterdam' => 'Amsterdam',
    'norway/oslo' =>'Oslo',
    'sint-maarten/philipsburg' => 'Philipsburg'
];
?>

<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        body {
            font-family: sans-serif;
            color: #aaf;
            background: #559;
        }

        #container {
            width: 780px;
            min-height: 500px;
            margin: 0 auto;
            border: 1px solid #77a;
            text-align: center;
            padding-left: 30px;
        }

        .title {
            border-bottom: 1px solid;
        }

        .details {
            text-align: left;
        }
    </style>
    <title></title>
</head>
<body>
    <div id="container">
        <h2>
            <span class="title">Astro Guide</span>
        </h2>

        <form method="get">
            <select name="placeid">
            <?php
                foreach ($places as $id => $place) {
                    if ($id === $placeid) {
                        echo "<option value='$id' selected>$place</option>";
                    } else {
                        echo "<option value='$id'>$place</option>";
                    }                    
                    
                }
            ?>
            </select>

            <input type="submit" value="view">
        </form>

        <?php
            foreach ($locationData as $detail => $value) {
                echo "<p class='details'><span class='left'>" . ucfirst($detail) . "</span>:   " . $value . "</p>";
            }
        ?>
    </div>
</body>
</html>
