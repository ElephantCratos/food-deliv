
var $ = jQuery = require('jquery');
var Chart = require('chart.js');
require('jquery-ui-browserify');
require("chartjs-plugin-zoom");
var moment = require("moment");

function init() {
    document.getElementById('fileinput').addEventListener('change', readMultipleFiles, false);
    $('#charts').sortable();
}

var datasets = [];
var TS_MASK = "HH:mm:ss,SSS";
var tsAlign = {
    min: 0,
    max: 0
};

/**
 * Align datasets, convert ENUM datasets, draw charts
 * @param data datasets
 */
function onDatasetsLoaded(data) {
    if (!data) {
        data = datasets;
    }
    alignTime(data);
    for (var prop in data) {
        if (data.hasOwnProperty(prop)) {
            var drawing = [];
            var yLabels = {};
            var converted = enumToInt(data[prop], yLabels);
            drawing.push(converted);
            draw(drawing, prop, [prop], yLabels);
        }
    }
}

/**
 * Convert ENUM dataset to integer dataset, ENUM labels will be recorded to labels object
 * @param data dataset to convert
 * @param labels object labels will be recorded to
 * @returns converted dataset
 */
function enumToInt(data, labels) {
    if (isNaN(parseInt(data[0].y)) && isNaN(parseFloat(data[0].y))) {
        var converted = [];
        var c = 0;
        for (var i = 0; i < data.length; i++) {
            if (!labels.hasOwnProperty(data[i].y)) {
                labels[data[i].y] = c++;
            }
            converted.push({
                x: data[i].x,
                y: labels[data[i].y]
            });
        }
        return converted;
    }
    return data;
}

/**
 * filter datasets using time boundaries and redraw charts
 */
function filter() {
    var from = $('#from').val();
    var to = $('#to').val();
    console.log("filter from " + from + " to " + to);
    from = moment(from, TS_MASK);
    to = moment(to, TS_MASK);
    var filtered = {};
    for (var prop in datasets) {
        if (datasets.hasOwnProperty(prop)) {
            var toFilter = datasets[prop];
            var filteredSet = [];
            for (var i = 0; i < toFilter.length; i++) {
                var row = toFilter[i];
                var time = moment(row.x, TS_MASK);
                if (time > from && time < to) {
                    filteredSet.push(row);
                } else if (time > to) {
                    break;
                }
            }
            if (filteredSet.length > 0) {
                filtered[prop] = filteredSet;
            }
        }
    }
    onDatasetsLoaded(filtered);
}

/**
 * Replace existing canvas with a fresh one
 * @param name Canvas id
 */
var resetCanvas = function (name) {
    var container = $('#'+name+'-container');
    if (container.length == 0) {
        container = document.createElement("div");
        container.id = name + "-container";
        $("#charts").append(container);
        resetCanvas(name);
    }
    $('#'+name).remove();
    $('#'+name+'-container iframe').remove();
    $('#'+name+'-container').append('<canvas id="'+name+'"></canvas>');
};

/**
 * Parse raw log file from WCS
 * @param file
 */
function parseLogFile(file) {
    var r = new FileReader();
    r.onload = (function(file) {
        return function(e) {
            var contents = e.target.result;
            var raw = contents.split("\n");
            var ret = {};
            for (var i = 0; i < raw.length; i++) {
                var str = raw[i];
                var words = str.split(" ");
                console.dir(words);
                if (words.length < 7) {
                    console.log("Failed to parse string: " + str);
                    continue;
                }
                if (!ret.hasOwnProperty(words[6])) {
                    ret[words[6]] = [];
                }
                ret[words[6]].push({
                    x: words[0],
                    y: words[7]
                });
            }
            console.log("Sets: " + Object.keys(ret));
            datasets = ret;
            onDatasetsLoaded(ret);
        };
    })(file);
    r.readAsText(file);
}

function readMultipleFiles(evt) {
    datasets = [];
    //Retrieve all the files from the FileList object
    var files = evt.target.files;
    if (files) {
        var count = files.length;
        for (var i=0, f; f=files[i]; i++) {
            parseLogFile(f);
        }
    } else {
        alert("Failed to load files");
    }
}

/**
 * Find max and min time values across datasets
 * @param data datasets
 */
function alignTime(data) {
    var min = null;
    var max = null;
    for (var prop in data) {
        if (data.hasOwnProperty(prop)) {
            var min_ = moment(data[prop][0].x, TS_MASK);
            var max_ = moment(data[prop][(data[prop].length - 1)].x, TS_MASK);
            min = min == null ? min_ : min;
            min = min_ < min ? min_ : min;
            max = max == null ? max_ : max;
            max = max_ > max ? max_ : max;
        }
    }
    tsAlign.min = min;
    tsAlign.max = max;
    $("#from").val(min._i);
    $("#to").val(max._i);
}

function readCsv(csv) {
    return $.csv.toArrays(csv, {separator: ";"});
}

/**
 *
 * @param input Array containing datasets
 * @param canvas Id of output canvas
 * @param labels Array with labels
 * @param yLabels Map of name -> value labels for y axis, e.g INCREASE -> 1, DECREASE -> 2
 */
function draw(input, canvas, labels, yLabels) {
    //set filter helpers
    resetCanvas(canvas);
    var ctx = document.getElementById(canvas);
    var config = {
        type: 'line',
        data: {
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    type: 'time',
                    time: {
                        parser: TS_MASK,
                        unit: 'minute',
                        min: tsAlign.min,
                        max: tsAlign.max,
                        displayFormats: {
                            'second': "HH:mm"
                        }
                    }
                }]
            },
            zoom: {
                // Boolean to enable zooming
                enabled: true,

                // Zooming directions. Remove the appropriate direction to disable
                // Eg. 'y' would only allow zooming in the y direction
                mode: 'x'
            }
        }
    };
    //add custom y labeling for enums
    if (yLabels && Object.keys(yLabels).length > 0) {
        var mapping = invertObject(yLabels);
        config.options.scales.yAxes = [
            {
                ticks: {
                    beginAtZero: true,
                    stepSize: 1,
                    callback: function(value, index, values) {
                        return mapping[value];
                    }
                }
            }
        ];
    }
    config.data.datasets = [];
    for (var i = 0; i < input.length; i++) {
        var dataset = {
            label: labels[i],
            fill: false,
            borderColor: getRgba(),
            data: input[i]
        };
        config.data.datasets.push(dataset);
    }
    var myChart = new Chart(ctx, config);
}

//onedimensional object, use only to invert map with simple keys/values
function invertObject(obj) {
    var ret = {};
    for (var prop in obj) {
        if(obj.hasOwnProperty(prop)) {
            ret[obj[prop]] = prop;
        }
    }

    return ret;
}

//get random rgba colour
function getRgba() {
    return "rgba("
        + getRandomInt(1,255)
        + ","
        + getRandomInt(1,255)
        + ","
        + getRandomInt(1,255)
        + ",1)";
}

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

module.exports = {
    init: init,
    filter: filter
};