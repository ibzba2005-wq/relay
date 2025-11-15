<?php

$filepath = 'stats.ini';
$data = @parse_ini_file($filepath);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noimageindex, noarchive, nocache, nosnippet">
    <title>STATS | VAMPIRE</title>
    <link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon">
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background: #0a0a0a;
            color: #fff;
            font-family: 'Roboto', 'Orbitron', Arial, sans-serif;
            overflow-x: hidden;
        }
        .nav {
            width: 100vw;
            height: 120px;
            background: linear-gradient(90deg, #1a0000 0%, #ff003c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 32px #ff003c44;
            border-bottom: 2px solid #ff003c;
        }
        .vampire-logo {
            font-family: 'Orbitron', 'Roboto', Arial, sans-serif;
            font-size: 2.8rem;
            font-weight: 900;
            letter-spacing: 8px;
            user-select: none;
            display: inline-block;
            vertical-align: middle;
            margin-left: 18px;
            margin-right: 18px;
            background: linear-gradient(270deg, #ff003c, #fffb00, #00ffea, #ff003c, #fffb00, #00ffea);
            background-size: 1200% 1200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
            animation: rgbglow 4s linear infinite;
            text-shadow: 0 0 24px #fff, 0 0 32px #ff003c99, 0 0 2px #fff2;
            filter: brightness(1.2) drop-shadow(0 0 8px #fff) drop-shadow(0 0 16px #ff003c);
        }
        @keyframes rgbglow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        main {
            min-height: calc(100vh - 120px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
            position: relative;
        }
        .content {
            background: rgba(20,0,0,0.92);
            border: 2px solid #ff003c;
            border-radius: 18px;
            box-shadow: 0 0 32px #ff003c55, 0 0 4px #000;
            padding: 40px 32px 32px 32px;
            margin: 0 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 320px;
            max-width: 90vw;
        }
        .rst-btn {
            text-decoration: none;
            border-radius: 3px;
            background-color: #000;
            color: #ff003c;
            border: 1px solid #ff003c;
            padding: 10px 20px;
            margin-bottom: 24px;
            margin-top: 8px;
            font-family: 'Orbitron', monospace;
            font-size: 1.1rem;
            box-shadow: 0 0 8px #ff003c99;
            transition: background 0.2s, color 0.2s;
        }
        .rst-btn:hover {
            background: #ff003c;
            color: #fff;
        }
        #chartdiv {
            width: 100%;
            height: 60vh;
            margin: 0;
            border-radius: 12px;
            box-shadow: 0 0 16px #ff003c55;
            background: #181818;
        }
        #legend {
            width: 100%;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
        }
        .legend-item {
            width: 250px;
            font-size: 15px;
            cursor: pointer;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            color: #ff003c;
            background: #181818;
            border-radius: 6px;
            margin-bottom: 6px;
            box-shadow: 0 0 8px #ff003c44;
            padding: 6px 12px;
        }
        .legend-marker {
            width: 12px;
            height: 12px;            
        }
        .legend-value {
            width: 50%;
            display: flex!important;
            flex-direction: row!important;
            align-items: center!important;
            justify-content: space-between!important;
        }
        @media (max-width: 600px) {
            .content { padding: 18px 4vw; min-width: 0; }
            .vampire-logo { font-size: 1.3rem; letter-spacing: 3px; margin-left: 6px; margin-right: 6px; }
            .legend-item { width: 98vw; font-size: 13px; }
        }
    </style>
</head>

<body>
    <div class="nav">
        <span class="vampire-logo">VAMPIRE</span>
    </div><br><br><br><br><br><br>
    <main>
        <div class="content">
            <a class="rst-btn" href="./reset.php">reset</a>
            <div id="chartdiv"></div>
            <div id="legend"></div>
        </div>
    </main>
    <script>
        am4core.useTheme(am4themes_animated);

        var chart = am4core.create("chartdiv", am4charts.PieChart);
        chart.data = [
            {
                "country": "Clicks",
                "litres": <?= $data['clicks'] ?>
            },
            {
                "country": "Bot",
                "litres": <?= $data['bots'] ?>
            },
            {
                "country": "Cards",
                "litres": <?= $data['cards'] ?>
            }
            ,
            {
                "country": "Infoz",
                "litres": <?= $data['infos'] ?>
            }
            ,
            {
                "country": "Otps",
                "litres": <?= $data['otps'] ?>
            }
            ,
            {
                "country": "VBV",
                "litres": <?= $data['pins'] ?>
            }
            ,
            {
                "country": "Logs",
                "litres": <?= $data['logs'] ?>
            }

        ];

        var pieSeries = chart.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "litres";
        pieSeries.dataFields.category = "country";
        pieSeries.labels.template.disabled = true;
        chart.radius = am4core.percent(95);

        chart.events.on("ready", function (event) {
            var legend = document.getElementById('legend');
            pieSeries.dataItems.each(function (row, i) {
                var color = chart.colors.getIndex(i);
                var percent = Math.round(row.values.value.percent * 100) / 100;
                var value = row.value;
                legend.innerHTML += '<div class="legend-item" style="color: ' + color + '"><div class="legend-marker" style="background: ' + color + '"></div>' + row.category + '<div class="legend-value">' + value + ' | ' + percent + '%</div></div>';
            });
        });
    </script>
</body>
</html>

