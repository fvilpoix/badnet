<?php

require_once __DIR__.DIRECTORY_SEPARATOR.'common.php';

$match = getLastMatch();

if (!$match) {
  exit('Pas de match');
}

// ------ PARSE URL PARAMETERS TO KNOW WHAT WE SHOW ------ \\

$show_match = true;
$show_court = true;
$compression = '0.8';

if (array_key_exists('match', $_GET)) {
  $show_court = false;
  $compression = '0.2';
}
if (array_key_exists('court', $_GET)) {
  $show_match = false;
  $compression = '0.2';
}

?><!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="refresh" content="5">
  <title>Match en cours</title>
  <style type="text/css">
    #content {
      width: 100%;
      display: table;
      text-align: center;
    }
    #content span {
      border-left: #000 solid 2px;
    }
    #content .panel {
      display: table-cell;
    }
    #content .panel h2 {
      font-size: 80%;
      font-weight: normal;
      margin: 0;
    }
    #content .panel div {
      font-size: 240%;
    }
  </style>
</head>
<body>
  <div id="content">
    <?php if ($show_match): ?>
      <div class="panel">
        <h2>Match</h2>
        <div>
          <?php echo $match['num']; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if ($show_court): ?>
      <div class="panel">
        <h2>Terrain</h2>
        <div>
          <?php echo $match['court']; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

<script>
 /*!
  * FitText.js 1.0 jQuery free version
  * https://github.com/adactio/FitText.js
  *
  * Copyright 2011, Dave Rupert http://daverupert.com
  * Released under the WTFPL license
  * http://sam.zoy.org/wtfpl/
  * Modified by Slawomir Kolodziej http://slawekk.info
  *
  * Date: Tue Aug 09 2011 10:45:54 GMT+0200 (CEST)
  */
  (function(){
    var css = function (el, prop) {
      return window.getComputedStyle ? getComputedStyle(el).getPropertyValue(prop) : el.currentStyle[prop];
    };

    var addEvent = function (el, type, fn) {
      if (el.addEventListener)
        el.addEventListener(type, fn, false);
      else
        el.attachEvent('on'+type, fn);
    };

    var extend = function(obj,ext){
      for(var key in ext)
        if(ext.hasOwnProperty(key))
          obj[key] = ext[key];
      return obj;
    };

    window.fitText = function (el, kompressor, options) {

      var settings = extend({
        'minFontSize' : -1/0,
        'maxFontSize' : 1/0
      },options);

      var fit = function (el) {
        var compressor = kompressor || 1;

        var resizer = function () {
          el.style.fontSize = Math.max(Math.min(el.clientWidth / (compressor*10), parseFloat(settings.maxFontSize)), parseFloat(settings.minFontSize)) + 'px';
        };

        // Call once to set.
        resizer();

        // Bind events
        // If you have any js library which support Events, replace this part
        // and remove addEvent function (or use original jQuery version)
        addEvent(window, 'resize', resizer);
      };

      if (el.length)
        for(var i=0; i<el.length; i++)
          fit(el[i]);
      else
        fit(el);

      // return set of elements
      return el;
    };
  })();

  window.fitText( document.getElementById("content"), <?php echo $compression; ?> );
</script>
</body>
</html>