<?php 
    $title = "Pyramid Chart By Age";
?>
<?php include "opener.php"; ?>

<!-- Example scripts go here -->
    <link class="include" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/themes/smoothness/jquery-ui.css" rel="Stylesheet" /> 
    <link href="colorpicker/jquery.colorpicker.css" rel="stylesheet" type="text/css"/>
    <style type="text/css">

        .quintile-outer-container {
            width: 97%;
            height: 97%;
            margin: auto;
        }

        .jqplot-chart {
            height: 600px;
        }

        .quintile-toolbar .ui-icon {
            float: right;
            margin: 3px 5px;
        }

        table.stats-table td, table.highlighted-stats-table td {
            background-color: rgb(230, 230, 230);
            padding: 0.5em;
        }

        col.label {
            width: 14em;
        }

        col.value {
            width: 7em;
        }

        td.quintile-value {
            width: 7em;
            text-align: right;
        }

        table.stats-table td.tooltip-header, table.highlighted-stats-table td.tooltip-header {
            background-color: rgb(200, 200, 200);
        }

        table.stats-table, table.highlighted-stats-table, td.contour-cell {
            font-size: 0.7em;
        }

        td.contour-cell {
            height: 1.5em;
            padding-left: 20px;
            padding-bottom: 1.5em;
        }

        table.highlighted-stats-table {
            margin-top: 15px;
        }

        div.stats-cell div.input {
            font-size: 0.7em;
            margin-top: 1.5em;
        }

        div.content-container {
            padding-left: 230px;   /* LC width */
            padding-right: 300px;  /* RC width */
            height: 100%;
        }

        div.content-container .column {
            position: relative;
            float: left;
        }

        div.controls {
            width: 170px;          /* LC width */
            right: 230px;          /* LC width */
            padding-left: 30px;
            padding-right: 30px;
            margin-left: -100%;
            margin-top: 30px;
        }

        div.chart-cell {
            width: 100%;
            height: 100%;
        }

        div.stats-cell {
            width: 270px;          /* RC width */
            margin-right: -300px;  /* RC width */
            padding-right: 30px;
            margin-top: 30px;
        }

        div.controls, div.controls select {
            font-size: 0.8em;
        }

        div.controls li {
            list-style-type: none;
        }

        div.controls ul {
            margin-top: 0.5em;
            padding-left: 0.2em;
        }

        div.overlay-chart-container {
            display: none;
            z-index: 11;
            position: fixed;
            width: 800px;
            left: 50%;
            margin-left: -400px;
            background-color: white;
        }

        div.overlay-chart-container div.ui-icon {
            float: right;
            margin: 3px 5px;
        }

        div.overlay-shadow {
            display: none;
            z-index: 10;
            background-color: rgba(0, 0, 0, 0.8);
            position: fixed;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
        }

        div.ui-colorpicker div.ui-dialog-titlebar {
            padding: 0.1em 0.3em;
        }

        input.color {
            display: none;
        }

        div.colorpicker-container span {
            padding: 3px;
        }

        div.quintile-content {
            width: 100%;
            height: 100%;
        }


        @media print {
            div.stats-cell {
                vertical-align: top;
                padding-top: 35px;
            }

            table.stats-table, table.stats-table td {
                 color: #aaaaaa;
                 border: 1px solid #bbbbbb;
                 border-collapse: collapse;
            }

            table.stats-table tr {
                font-family: Verdana,Arial,sans-serif;
                /*font-size: 0.7em;*/
            }
        }

    </style>
 
    <div class="overlay-shadow"></div>

    <div class="overlay-chart-container ui-corner-all">
        <div class="overlay-chart-container-header ui-widget-header ui-corner-top">Right click the image to Copy or Save As...<div class="ui-icon ui-icon-closethick"></div></div>
        <div class="overlay-chart-container-content ui-corner-bottom"></div>
    </div>

    <div class="quintile-outer-container ui-widget ui-corner-all">
        <div class="quintile-toolbar ui-widget-header  ui-corner-top">
            <span class="quintile-title">Income Level:</span>
        </div>
        <div class="quintile-content ui-widget-content ui-corner-bottom">

            <div class="content-container">


            <div class="chart-cell column">
                <div id="agesChart" class="jqplot-chart"></div>
            </div>

            <div class="controls column">
                <table>
                    <tr>
                        <td>
                            Axes:
                        </td>
                        <td>
                            <select name="axisPosition">
                                <option value="both">Left &amp; Right</option>
                                <option value = "left">Left</option>
                                <option value = "right">Right</option>
                                <option value = "mid">Mid</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Colors:
                        </td>
                        <td>
                            <ul>
                                <li><input class="color" type="color" id="colorMale" value="#526D2C" /> Male</li>
                                <li><input class="color" type="color" id="colorFemale" value="#77933C" /> Female</li>
                                <li><input class="color" type="color" id="colorBackground" value="#ffffff" /> Background</li>
                                <li><input class="color" type="color" id="colorPlotBands" value="f5ebd7" /> Plot Bands</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Grids:
                        </td>
                        <td>
                            <ul>
                                <li><input name="gridsVertical" value="vertical" type="checkbox" />Vertical</li>
                                <li><input name="gridsHorizontal" value="horizontal" type="checkbox" />Horizontal</li>
                                <li><input name="showMinorTicks" value="true" type="checkbox" checked />Only major</li>
                                <li><input name="plotBands" value="true" type="checkbox" checked />Plot Bands</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <ul>
                                <li><input name="barPadding" value="2" type="checkbox" checked />Gap between bars</li>
                                <!-- value for showContour is speed at which to fade lines in/out -->
                                <li><input name="showContour" value="500" type="checkbox" />Comparison Line</li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="stats-cell column">
                <table class="stats-table">
                <colgroup>
                    <col class="label">
                    <col class="value">
                </colgroup>
                <tbody>
                    <tr>
                        <td class="ui-corner-tl">Mean Age:</td>
                        <td class="quintile-value summary-meanAge ui-corner-tr"></td>
                    </tr>
                    <tr>
                        <td>Sex Ratio:</td>
                        <td class="quintile-value summary-sexRatio"></td>
                    </tr>
                    <tr>
                        <td>Age Dependency Ratio:</td>
                        <td class="quintile-value summary-ageDependencyRatio"></td>
                    </tr>
                    <tr>
                        <td>Population, Total:</td>
                        <td class="quintile-value summary-populationTotal"></td>
                    </tr>
                    <tr>
                        <td>Population, Male:</td>
                        <td class="quintile-value summary-populationMale"></td>
                    </tr>
                    <tr>
                        <td class="ui-corner-bl">Population, Female:</td>
                        <td class="quintile-value summary-populationFemale ui-corner-br"></td>
                    </tr>
                </tbody>
                </table>
                <table class="highlighted-stats-table">
                <colgroup>
                    <col class="label">
                    <col class="value">
                </colgroup>
                <tbody>
                    <tr class="tooltip-header">
                        <td class="tooltip-header ui-corner-top" colspan="2">Highlighted Age: <span class="tooltip-item tooltipAge">&nbsp;</span></td>
                    </tr>
                    <tr>
                        <td>Population, Male: </td>
                        <td class="quintile-value"><span class="tooltip-item tooltipMale">&nbsp;</span></td>
                    </tr>
                    <tr>
                        <td>Population, Female: </td>
                        <td class="quintile-value"><span class="tooltip-item tooltipFemale">&nbsp;</span></td>
                    </tr>
                    <tr>
                        <td class="ui-corner-bl">Sex Ratio: </td>
                        <td class="quintile-value ui-corner-br"><span class="tooltip-item tooltipRatio">&nbsp;</span></td>
                    </tr>
                <tbody>
                </table>
            </div>

            </div>

        </div>
    </div> 
  


    <script class="code" type="text/javascript">
    $(document).ready(function(){

        // if browser supports canvas, show additional toolbar icons
        if (!$.jqplot.use_excanvas) {
            $('div.quintile-toolbar').append('<div class="ui-icon ui-icon-image"></div><div class="ui-icon ui-icon-print"></div>');
        }

        // for this demo, all data is same for each quintile.
        // could do something like this to get the index of the quintile.
        // <!-- var quintileIndex = parseInt(< ? php echo $_GET["qidx"]; ? >); -->

        var male;
        var female;
        var summaryTable;
        var sexRatios;
        var jsondata = [[1043353182,539695335,503657847,28.24,27.51,29.01,51.78], 
          [0.0085318435343400800,0.0088774027062416400,0.0094714560875224100,0.0101640893891056000,0.0108249758696292000,0.0113773174183149000,0.0117954271696904000,0.0120868766491156000,0.0122726357410028000,0.0123620512208843000,0.0124092312631522000,0.0123929933989534000,0.0123170841477326000,0.0121788940682660000,0.0119848553256476000,0.0117367932349178000,0.0114334768233470000,0.0110840887113746000,0.0107005336950161000,0.0102889073661635000,0.0098746874267631800,0.0094727992165526500,0.0091066817836288100,0.0087853078056091400,0.0085176451289595700,0.0082955898249666400,0.0081021034227657000,0.0079410857437656400,0.0078129432771183200,0.0077041517746791700,0.0076076629996558600,0.0075063773837400400,0.0074212932416191700,0.0073577960797626100,0.0073199937766648000,0.0072872222696609900,0.0072249036551971000,0.0071436496521446000,0.0070535868312601700,0.0069442503777637000,0.0068032069975035000,0.0066093294501292200,0.0063936409488174700,0.0061768176372133200,0.0059737878831400300,0.0057724507581444500,0.0055456647375558400,0.0053139201135624000,0.0050952395722700100,0.0048873227886988200,0.0046839194356973000,0.0044709211871979300,0.0042683374557587400,0.0040886232834079100,0.0039394433696905200,0.0038097165950705600,0.0036797119436340500,0.0035595490798891500,0.0034559622921317600,0.0033586853733251200,0.0032515219944251700,0.0031141174480235800,0.0029601056879342400,0.0027997057390388600,0.0026388958299152400,0.0024706340765107900,0.0022829305753990900,0.0020900571013307600,0.0019037152224417700,0.0017251049256371200,0.0015499961243315000,0.0013709889555399900,0.0011982773790103000,0.0010401790931594200,0.0009015220565900700,0.0007812832602556460,0.0006749752626942340,0.0005833580512023270,0.0005061657847658260,0.0004399544894590220,0.0003808034210932470,0.0003273788998735520,0.0002794993115460400,0.0002369793671257210,0.0001995479850687680,0.0001668101805315270,0.0001383186716039340,0.0001138075253115040,0.0000929924552954457,0.0000755223736670678,0.0000610047525059652,0.0000490638302928227,0.0000396489252832302,0.0000330891826825378,0.0000281195284929135,0.0001070412478036410],
          [0.0071192629623232800,0.0072839317935624000,0.0075693293189514500,0.0079091875498352200,0.0082360857829773300,0.0084979038169712600,0.0086893986451354100,0.0088353986277422900,0.0089633895658643000,0.0090878796761182500,0.0092337667384807200,0.0093792123312149900,0.0095192745279032600,0.0096463104169553400,0.0097588790000607600,0.0098544841215461300,0.0099282199747865200,0.0099763812248601700,0.0099957597226035700,0.0099801585489242500,0.0099375446097938400,0.0098709094654945100,0.0097926096630396900,0.0097014318757340600,0.0095968423362564000,0.0094699215837397400,0.0093120985109391200,0.0091337644254482100,0.0089445678055607600,0.0087423328014590000,0.0085321350430430500,0.0083104164267343500,0.0081040369311665900,0.0079207770770160300,0.0077675098774536000,0.0076284130950824500,0.0074765938644635800,0.0073237591351693500,0.0071772210497332700,0.0070244435483804400,0.0068550745015274400,0.0066526057090785200,0.0064404461001358000,0.0062305720357510500,0.0060342707683446500,0.0058408705961082800,0.0056264339860478000,0.0054065565344313600,0.0051920462742280000,0.0049765895152515200,0.0047558832991186200,0.0045207003388048400,0.0042899070487080500,0.0040745089551306800,0.0038817655555785800,0.0037053224400247200,0.0035332134254902200,0.0033727187145046400,0.0032284905944481100,0.0030937679725170100,0.0029582187048090100,0.0028081834596732200,0.0026513461722362800,0.0024926069125082600,0.0023353006048059200,0.0021744630786167700,0.0020021420693961100,0.0018298895084829500,0.0016671380010344600,0.0015153637133401800,0.0013712248188900700,0.0012284616818527500,0.0010934572254154500,0.0009707133813897040,0.0008622603131043950,0.0007661899219731710,0.0006786114871838230,0.0006005435486257860,0.0005322956370150040,0.0004715229254612440,0.0004158721219136000,0.0003645948643149670,0.0003176904909297340,0.0002751152300590630,0.0002367021156783720,0.0002021700726755280,0.0001712214234345020,0.0001437557719387760,0.0001197138111336940,0.0000989925882290494,0.0000814263200020563,0.0000667991474109870,0.0000549164545704749,0.0000455518367647343,0.0000384480445034309,0.0001437755914949950],
          [1.284165231,1.3059697282,1.3408259576,1.3770498469,1.4083776842,1.4346340126,1.4545779876,1.4658887137,1.4671638972,1.457608962,1.4400548643,1.4158684686,1.3864911961,1.3528812542,1.3159697978,1.27622903,1.2340137083,1.1905291022,1.1471037258,1.1047013276,1.0647737249,1.0283340659,0.9964942039,0.9703629438,0.9510519527,0.9386721807,0.932316307,0.9316292996,0.9359838821,0.9443012277,0.9554470086,0.9678781103,0.9812761433,0.9953894103,1.0098153246,1.0236249732,1.035479081,1.0451994053,1.0530930686,1.0593183708,1.0634438538,1.0645809137,1.0637641578,1.0623066923,1.0608111193,1.0589993944,1.0561690641,1.0531914677,1.0515722425,1.052330754,1.0553372965,1.0597522653,1.0661637808,1.0752634417,1.0874733411,1.1017415355,1.1159815378,1.1309096828,1.1470504754,1.1633078085,1.1777943775,1.1882903501,1.1963378464,1.2035708256,1.2108560457,1.2175013424,1.2218302494,1.2239011525,1.223611334,1.2198647763,1.2112533925,1.1958739376,1.1742717733,1.1482333683,1.1203430352,1.0926602484,1.0658097999,1.040887396,1.0189501507,0.9998112064,0.9811922123,0.9621730704,0.9427349027,0.9230154312,0.903354613,0.8841353287,0.8656369091,0.8483181293,0.8323701879,0.8174967062,0.8028084248,0.7870522949,0.7736454722,0.7783827557,0.7836945697,0.7977724963],
          [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,"95+", ""]];


        // the "x" values from the data will go into the ticks array.  
        // ticks should be strings for this case where we have values like "75+"
        var ticks = jsondata[4];

        $('td.summary-meanAge').each(function(index) {
            $(this).html($.jqplot.sprintf('%5.2f', jsondata[0][3]));
        });

        $('td.summary-sexRatio').each(function(index) {
            $(this).html($.jqplot.sprintf('%5.2f', jsondata[3][0]));
        });

        $('td.summary-ageDependencyRatio').each(function(index) {
            $(this).html($.jqplot.sprintf('%5.2f', jsondata[0][6]));
        });

        $('td.summary-populationTotal').each(function(index) {
            $(this).html($.jqplot.sprintf("%'d", jsondata[0][0]));
        });

        $('td.summary-populationMale').each(function(index) {
            $(this).html($.jqplot.sprintf("%'d", jsondata[0][1]));
        });

        $('td.summary-populationFemale').each(function(index) {
            $(this).html($.jqplot.sprintf("%'d", jsondata[0][2]));
        });
        
        // These two variables should be removed outside of the jqplot.com example environment.
        $.jqplot._noToImageButton = true;
        $.jqplot._noCodeBlock = true;

        // Custom color arrays are set up for each series to get the look that is desired.
        // Two color arrays are created for the default and optional color which the user can pick.
        var greenColors = ["#526D2C", "#77933C", "#C57225", "#C57225"];
        var blueColors = ["#3F7492", "#4F9AB8", "#C57225", "#C57225"];

        // To accomodate changing y axis, need to keep track of plot options.
        // changing axes will require recreating the plot, so need to keep 
        // track of state changes.
        var plotOptions = {
            // We set up a customized title which acts as labels for the left and right sides of the pyramid.
            title: {
                text: '<span style="margin-left:25%;">Male</span><span style="margin-left:33%;">Female</span>',
                textAlign: 'left'
            },
            // by default, the series will use the green color scheme.
            seriesColors: greenColors,

            grid: {
                drawBorder: false,
                shadow: false,
                background: "#ffffff",
                rendererOptions: {
                    // plotBands is an option of the pyramidGridRenderer.
                    // it will put banding at starting at a specified value
                    // along the y axis with an adjustable interval.
                    plotBands: {
                        show: true,
                        interval: 10,
                        color: 'rgb(245, 235, 215)'
                    }
                }
            },

            // This makes the effective starting value of the axes 0 instead of 1.
            // For display, the y axis will use the ticks we supplied.
            defaultAxisStart: 0,
            seriesDefaults: {
                renderer: $.jqplot.PyramidRenderer,
                rendererOptions: {
                    barPadding: 1.5,
                    offsetBars: true
                },
                yaxis: "yaxis",
                shadow: false
            },

            // We have 4 series, the left and right pyramid bars and
            // the left and rigt overlay lines.
            series: [
                // For pyramid plots, the default side is right.
                // We want to override here to put first set of bars
                // on left.
                {
                    rendererOptions:{
                        side: "left",
                        synchronizeHighlight: 1
                    }
                },
                {
                    yaxis: "y2axis",
                    rendererOptions: {
                        synchronizeHighlight: 0
                    }
                },
                {
                    rendererOptions: {
                        fill: false,
                        side: 'left'
                    }
                },
                {
                    yaxis: 'y2axis',
                    rendererOptions: {
                        fill: false
                    }
                }
            ],
            axesDefaults: {
                tickOptions: {
                    showGridline: false
                },
                pad: 0,
                rendererOptions: {
                    baselineWidth: 2
                }
            },

            // Set up all the y axes, since users are allowed to switch between them.
            // The only axis that will show is the one that the series are "attached" to.
            // We need the appropriate options for the others for when the user switches.
            axes: {
                xaxis: {
                    tickOptions: {
                        formatter: $.jqplot.PercentTickFormatter,
                        formatString: '%.1f%%'
                    }
                },
                yaxis: {
                    label: "Age",
                    // Use canvas label renderer to get rotated labels.
                    labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                    // include empty tick options, they will be used
                    // as users set options with plot controls.
                    tickOptions: {},
                    showMinorTicks: false,
                    tickInterval: 5,
                    ticks: ticks,
                    rendererOptions: {
                        tickSpacingFactor: 15,
                        category: false
                    }
                },
                yMidAxis: {
                    label: "Age",
                    // include empty tick options, they will be used
                    // as users set options with plot controls.
                    tickOptions: {},
                    showMinorTicks: false,
                    tickInterval: 5,
                    ticks: ticks,
                    rendererOptions: {
                        tickSpacingFactor: 15,
                        category: false
                    }
                },
                y2axis: {
                    label: "Age",
                    // Use canvas label renderer to get rotated labels.
                    labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                    // include empty tick options, they will be used
                    // as users set options with plot controls.
                    tickOptions: {},
                    showMinorTicks: false,
                    tickInterval: 5,
                    ticks: ticks,
                    rendererOptions: {
                        tickSpacingFactor: 15,
                        category: false
                    }
                }
            }
        };

        // resize the chart container to fill the space
        $('#agesChart').height($('div.chart-cell').height()*0.96);
        $('#agesChart').width($('div.chart-cell').width()*0.97);

        // $('#agesChart').jqplot([jsondata[1], jsondata[2]], plotOptions);
        $.jqplot.config.addDomReference = true;
        var plot1 = $.jqplot('agesChart', [jsondata[1], jsondata[2]], plotOptions);

        $(window).resize (function(event, ui) {
            // pass in resetAxes: true option to get rid of old ticks and axis properties
            // which should be recomputed based on new plot size.
            $('#agesChart').height($('div.chart-cell').height()*0.96);
            $('#agesChart').width($('div.chart-cell').width()*0.97);
            plot1.replot( { resetAxes: true } );
        });

        // initialize form elements
        // set these before attaching event handlers.

        $("input[type=checkbox][name=gridsVertical]").attr("checked", false);
        $("input[type=checkbox][name=gridsHorizontal]").attr("checked", false);
        $("input[type=checkbox][name=showMinorTicks]").attr("checked", true);
        $("input[type=checkbox][name=plotBands]").attr("checked", true);
        $("input[type=checkbox][name=showContour]").attr("checked", true);
        $("input[type=checkbox][name=barPadding]").attr("checked", true);
        $("select[name=axisPosition]").val("both");

        //////
        // The followng functions use verbose css selectors to make
        // it clear exactly which elements they are binging to/operating on
        //////

        $("select[name=axisPosition]").change(function(){ 
            // this refers to the html element we are binding to.
            // $(this) is jQuery object on that element.

            var opts = {series:[{}, {}, {}, {}]};

            switch ($(this).val()) {
                case "both":
                    opts.series[0].yaxis = "yaxis";
                    opts.series[1].yaxis = "y2axis";
                    opts.series[2].yaxis = "yaxis";
                    opts.series[3].yaxis = "y2axis";
                    break;
                case "left":
                    opts.series[0].yaxis = "yaxis";
                    opts.series[1].yaxis = "yaxis";
                    opts.series[2].yaxis = "yaxis";
                    opts.series[3].yaxis = "yaxis";
                    break;
                case "right":
                    opts.series[0].yaxis = "y2axis";
                    opts.series[1].yaxis = "y2axis";
                    opts.series[2].yaxis = "y2axis";
                    opts.series[3].yaxis = "y2axis";
                    break;
                case "mid":
                    opts.series[0].yaxis = "yMidAxis";
                    opts.series[1].yaxis = "yMidAxis";
                    opts.series[2].yaxis = "yMidAxis";
                    opts.series[3].yaxis = "yMidAxis";
                    break;
                default:
                    break;
                    
            }

            plot1.replot(opts); 
        });

        // bind to the data highlighting event to make custom tooltip:
        $(".jqplot-target").each(function(index){
            $(this).bind("jqplotDataHighlight", function(evt, seriesIndex, pointIndex, data) {
                // Here, assume first series is male poulation and second series is female population.
                // Adjust series indices as appropriate.
                var plot = $(this).data('jqplot');
                var malePopulation = Math.abs(plot.series[0].data[pointIndex][1]) * jsondata[0][1];
                var femalePopulation = Math.abs(plot.series[1].data[pointIndex][1]) * jsondata[0][2];
                var malePopulation = jsondata[1][pointIndex] * jsondata[0][1];
                var femalePopulation = jsondata[2][pointIndex] * jsondata[0][2];
                // var ratio = femalePopulation / malePopulation * 100;
                var ratio = jsondata[3][pointIndex];

                $('.tooltipMale').stop(true, true).fadeIn(350).html($.jqplot.sprintf("%'d", malePopulation));
                $('.tooltipFemale').stop(true, true).fadeIn(350).html($.jqplot.sprintf("%'d", femalePopulation));
                $('.tooltipRatio').stop(true, true).fadeIn(350).html($.jqplot.sprintf('%5.2f', ratio));

                // Since we don't know which axis is rendererd and acive with out a little extra work,
                // just use the supplied ticks array to get the age label.
                $('.tooltipAge').stop(true, true).fadeIn(350).html(ticks[pointIndex]);
            });
        });

        // bind to the data highlighting event to make custom tooltip:
        $(".jqplot-target").each(function() {
            $(this).bind("jqplotDataUnhighlight", function(evt, seriesIndex, pointIndex, data) {
                // clear out all the tooltips.
                $(".tooltip-item").fadeOut(250);
            });
        });

        $('.ui-icon-print').click(function(){
            $(this).parent().next().print();
        });


        $("input[type=checkbox][name=gridsVertical]").change(function(){
            // this refers to the html element we are binding to.
            // $(this) is jQuery object on that element.
            var opts = {axes: {xaxis: {tickOptions: {showGridline: this.checked}}}};
            plot1.replot(opts);
        });


        $("input[type=checkbox][name=gridsHorizontal]").change(function(){
            // this refers to the html element we are binding to.
            // $(this) is jQuery object on that element.
            var opts = {
                axes: {
                    yaxis: {
                        tickOptions: {showGridline: this.checked}
                    },
                    y2axis: {
                        tickOptions: {showGridline: this.checked}
                    },
                    yMidAxis: {
                        tickOptions: {showGridline: this.checked}
                    }
                }
            };
            plot1.replot(opts);
        });

        $("input[type=checkbox][name=plotBands]").change(function(){
            // this refers to the html element we are binding to.
            // $(this) is jQuery object on that element.
            var opts = {grid:{ rendererOptions: {plotBands: { show: this.checked}}}};
            plot1.replot(opts);
        });

        ////
        // To-Do
        //
        // initialize form elements on reload.
        // figure out what overlay line would be.
        // have to adjust ticks to do show minor.
        // make like kcp_pyramid.php
        ////
        $("input[type=checkbox][name=showMinorTicks]").change(function(){
            // this refers to the html element we are binding to.
            // $(this) is jQuery object on that element.
            var opts = {
                axes: {
                    yaxis: {
                        showMinorTicks: !this.checked
                    },
                    y2axis: {
                        showMinorTicks: !this.checked
                    },
                    yMidAxis: {
                        showMinorTicks: !this.checked
                    }
                }
            };
            plot1.replot(opts);
        });

        $("input[type=checkbox][name=barPadding]").change(function(){
            // this refers to the html element we are binding to.
            // $(this) is jQuery object on that element.
            if (this.checked) {
                var val = parseFloat($(this).val());
                var opts = {
                    seriesDefaults: {
                        rendererOptions: {
                            barPadding: val
                        }
                    }
                };
            }
            else {
                var opts = {
                    seriesDefaults: {
                        rendererOptions: {
                            barPadding: 0
                        }
                    }
                };
            }
            plot1.replot(opts);
        });


        $('.ui-icon-image').each(function() {
            $(this).bind('click', function(evt) {
                var chart = $(this).closest('div.quintile-outer-container').find('div.jqplot-target');
                var imgelem = chart.jqplotToImageElem();
                var div = $('div.overlay-chart-container-content');
                div.empty();
                div.append(imgelem);
                $('div.overlay-shadow').fadeIn(600);
                div.parent().fadeIn(1000);
                div = null;
            });
        });

        $('div.overlay-chart-container-header div.ui-icon-closethick').click(function(){
            var div = $('div.overlay-chart-container-content');
            div.parent().fadeOut(600);
            $('div.overlay-shadow').fadeOut(1000);
        });

        function applyColors(maleColor, femaleColor, backgroundColor, bandColor) {
            var opts = {series:[{}, {}], grid:{rendererOptions:{plotBands:{}}}};
            opts.series[0].color = maleColor;
            opts.series[1].color = femaleColor;
            opts.grid.background = backgroundColor;
            opts.grid.rendererOptions.plotBands.color = bandColor;
            plot1.replot(opts);
        };

        $('#colorMale').colorpicker({
            colorFormat: '#HEX',
            showOn: 'button',
            buttonColorize: true,
            buttonImageOnly: true,
            parts: 'full',
            close: function(ui, color) {
                applyColors(color.formatted, plot1.series[1].color, plot1.grid.background, plot1.grid.plotBands.color);
            }
        });

        $('#colorFemale').colorpicker({
            colorFormat: '#HEX',
            showOn: 'button',
            buttonColorize: true,
            buttonImageOnly: true,
            parts: 'full',
            close: function(ui, color) {
                applyColors(plot1.series[0].color, color.formatted, plot1.grid.background, plot1.grid.plotBands.color);
            }
        });

        $('#colorBackground').colorpicker({
            colorFormat: '#HEX',
            showOn: 'button',
            buttonColorize: true,
            buttonImageOnly: true,
            parts: 'full',
            close: function(ui, color) {
                applyColors(plot1.series[0].color, plot1.series[1].color, color.formatted, plot1.grid.plotBands.color);
            }
        });

        $('#colorPlotBands').colorpicker({
            colorFormat: '#HEX',
            showOn: 'button',
            buttonColorize: true,
            buttonImageOnly: true,
            parts: 'full',
            close: function(ui, color) {
                applyColors(plot1.series[0].color, plot1.series[1].color, plot1.grid.background, color.formatted);
            }
        });

    });
    </script>

<!-- End example scripts -->

<!-- Don't touch this! -->

<?php include "commonScripts.html" ?>

<!-- End Don't touch this! -->

<!-- Additional plugins go here -->

    <script class="include" type="text/javascript" src="../plugins/jqplot.categoryAxisRenderer.js"></script>

    <!-- load the pyramidAxis and Grid renderers in production.  pyramidRenderer will try to load via ajax if not present, but that is not optimal and depends on paths being set. -->
    <script class="include" type="text/javascript" src="../plugins/jqplot.pyramidAxisRenderer.js"></script>
    <script class="include" type="text/javascript" src="../plugins/jqplot.pyramidGridRenderer.js"></script> 

    <script class="include" type="text/javascript" src="../plugins/jqplot.pyramidRenderer.js"></script>
    <script class="include" type="text/javascript" src="../plugins/jqplot.canvasTextRenderer.js"></script>
    <script class="include" type="text/javascript" src="../plugins/jqplot.canvasAxisLabelRenderer.js"></script>
    <script class="include" type="text/javascript" src="../plugins/jqplot.json2.js"></script>
    <script class="include" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js"></script>
    <script class="include" type="text/javascript" src="kcp.print.js"></script>

    <script src="colorpicker/jquery.colorpicker.js"></script>
 
<!-- End additional plugins -->

<?php include "closer.php"; ?>
