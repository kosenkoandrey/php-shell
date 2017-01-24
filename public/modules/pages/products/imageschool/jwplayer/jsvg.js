
var jsvg_animation = function() {

    function animate(block_id, newLeft, newTop, newWidth, newHeight, time, callback) {
        block = document.getElementById(block_id);
        if (block == null)
            return;

        if (block.busy != null && block.busy)
            return;

        block.busy = true;

        newLeft = parseInt(newLeft);
        newTop = parseInt(newTop);
        newWidth = parseInt(newWidth);
        newHeight = parseInt(newHeight);

        var cLeft = parseInt(0);
        var cTop = parseInt(0);
        var cWidth = parseInt( block.clientWidth);
        var cHeight = parseInt(0);

        var totalFrames = 1;
        if (time> 0)
            totalFrames = time/40;

        var fLeft = newLeft - cLeft;
        if (fLeft != 0)
            fLeft /= totalFrames;

        var fTop = newTop - cTop;
        if (fTop != 0)
            fTop /= totalFrames;

        var fWidth = newWidth - cWidth;
        if (fWidth != 0)
            fWidth /= totalFrames;

        var fHeight = newHeight - cHeight;
        if (fHeight != 0)
            fHeight /= totalFrames;

        jsvg_animation.doFrame(block_id, cLeft, newLeft, fLeft, cTop, newTop, fTop, cWidth, newWidth, fWidth, cHeight, newHeight, fHeight, callback);
    }

    function doFrame(block_id, cLeft, nLeft, fLeft, cTop, nTop, fTop, cWidth, nWidth, fWidth, cHeight, nHeight, fHeight, callback) {
        block = document.getElementById(block_id);
        if (block == null)
            return;

        cLeft = jsvg_animation.moveSingleVal(cLeft, nLeft, fLeft);
        cTop = jsvg_animation.moveSingleVal(cTop, nTop, fTop);
        cWidth = jsvg_animation.moveSingleVal(cWidth, nWidth, fWidth);
        cHeight = jsvg_animation.moveSingleVal(cHeight, nHeight, fHeight);

        block.style.left = Math.round(cLeft) + 'px';
        block.style.top = Math.round(cTop) + 'px';
        block.style.width = Math.round(cWidth) + 'px';
        block.style.height = Math.round(cHeight) + 'px';
         block.busy = false;

       if (cLeft == nLeft && cTop == nTop && cHeight == nHeight && cWidth == nWidth) {
            if (callback != null)
                callback(block_id);
            block.busy = false;
            return;
        }

        window.setTimeout('jsvg_animation.doFrame("'+block_id+'",'+cLeft+','+nLeft+','+fLeft+','+cTop+','+nTop+','+fTop+','+cWidth+','+nWidth+','+fWidth+','+cHeight+','+nHeight+','+fHeight+','+callback+')', 40);

 }

    function moveSingleVal(currentVal, finalVal, frameAmt) {
        if (frameAmt == 0 || currentVal == finalVal)
            return finalVal;

        currentVal += frameAmt;
        if ((frameAmt> 0 && currentVal>= finalVal) || (frameAmt <0 && currentVal <= finalVal)) {
            return finalVal;
        }
        return currentVal;
    }

    function slide_right(block_id) {
        var block = document.getElementById(block_id);
        if (block == null)
            return;

        if (block.opened == null || !block.opened) {
            block.style.visibility = "hidden";
            block.style.display = "block";
            block.saved_width = block.clientWidth;
            block.style.width = "0px";
            block.style.visibility = "visible";
            block.opened = true;
            animate(block_id, block.clientLeft, block.clientTop, block.saved_width, block.clientHeight, 250, null);
        } else {
            block.saved_width = block.clientWidth;
            animate(block_id, block.clientLeft, block.clientTop, "0", block.clientHeight, 250, function(block_id) {
                var block = document.getElementById(block_id);
                block.style.display = "none";
                block.style.width = block.saved_width+"px";
                block.opened = false;
            });
        }
    }

    function slide_down(block_id) {
        var block = document.getElementById(block_id);
        if (block == null)
            return;

        if (block.opened == null || !block.opened) {

            block.style.visibility = "hidden";
            block.style.display = "block";
            block.saved_height = block.clientHeight;
            block.style.height = "0px";
            block.style.visibility = "visible";
            block.opened = true;
            animate(block_id, block.clientLeft, block.clientTop, block.clientWidth, block.saved_height, 250, null);
        } else {

            block.saved_height = block.clientHeight;
            animate(block_id, block.clientLeft, block.clientTop, block.clientWidth, "0", 250, function(block_id) {
                var block = document.getElementById(block_id);
                block.style.display = "none";
                block.style.height = block.saved_height+"px";
                block.opened = false;
            });
        }
    }

    function slide_both(block_id) {
        var block = document.getElementById(block_id);
        if (block == null)
            return;

        if (block.opened == null || !block.opened) {
            block.style.visibility = "hidden";
            block.style.display = "block";
            block.saved_width = block.clientWidth;
            block.saved_height = block.clientHeight;
            block.style.width = "0px";
            block.style.height = "0px";
            block.style.visibility = "visible";
            block.opened = true;
            animate(block_id, block.clientLeft, block.clientTop, block.saved_width, block.saved_height, 250, null);
        } else {
            block.saved_width = block.clientWidth;
            block.saved_height = block.clientHeight;
            animate(block_id, block.clientLeft, block.clientTop, "0", "0", 250, function(block_id) {
                var block = document.getElementById(block_id);
                block.style.display = "none";
                block.style.width = block.saved_width+"px";
                block.style.height = block.saved_height+"px";
                block.opened = false;
            });
        }
    }

    function slide(block_id, direction) {
        if (direction == "right") {
            slide_right(block_id);
        } else if (direction == "down") {
            slide_down(block_id);
        } else if (direction == "both") {
            slide_both(block_id);
        }
    }

    function set_opacity(block, i) {
        block.style.opacity = i/100;
        block.style.MozOpacity =  i/100;
        block.style.KhtmlOpacity =  i/100;
        block.style.filter = " alpha(opacity="+i+")";
    }

    function fade_inout(block_id, delay, callback, out) {
        var block = document.getElementById(block_id);
        if (block == null)
            return;

        if (block.busy != null && block.busy)
            return;
        block.busy = true;

        block.style.zoom = 1;
        for (i = 1; i <= 100; i++) {
            (function(j) {
                setTimeout(function() {
                    if (out)
                        j = 100 - j;
                    set_opacity(block, j);
                    if (j == 100) {
                        if (callback != undefined)
                            callback(block_id);
                        block.busy = false;
                    }
                    else if (out && j == 0) {
                        if (callback != undefined)
                            callback(block_id);
                        block.busy = false;
                    }
                }, j*delay/100);
            })(i);
        }
    }

    function fade(block_id) {
        var block = document.getElementById(block_id);
        if (block == null)
            return;

        if (block.opened == null || !block.opened) {
            block.opened = true;
            set_opacity(block, 1);
            block.style.display = "block";
            fade_inout(block_id, 1000, null, false);
        } else {
            fade_inout(block_id, 1000, function(block_id) {
                block.style.display = "none";
                block.opened = false;
            }, true);
        }
    }

    function show_block(block_id, effect) {
        var block = document.getElementById(block_id);
        if (block.opened != null && block.opened)
            return;

        if (effect == "slide-right") {
            slide(block_id, "right");
        } else if (effect == "slide-down") {
            slide(block_id, "down");
        } else if (effect == "slide-both") {
            slide(block_id, "both");
        } else if (effect == "fade") {
            fade(block_id);
        } else {
            block.opened = true;
            block.style.display = "block";
        }
    }

    function hide_block(block_id, effect) {
        var block = document.getElementById(block_id);
        if (block.opened == null || !block.opened)
            return;

        if (effect == "slide-right") {
            slide(block_id, "right");
        } else if (effect == "slide-down") {
            slide(block_id, "down");
        } else if (effect == "slide-both") {
            slide(block_id, "both");
        } else if (effect == "fade") {
            fade(block_id);
        } else {
            block.style.display = "none";
            block.opened = false;
        }
    }

    return {show_block: show_block, hide_block: hide_block, doFrame: doFrame, moveSingleVal: moveSingleVal};
}();


function JSVG(varname, player_type, place_tag_id, options) {
    var _varname = varname;
    var _player_type = player_type;
    var _place_tag_id = place_tag_id;
    var _options = options;
    var _timer;
    var _inner_player_tag_id = "jsvg-player-" + Math.floor(Math.random() * 9000 + 1000);
    var _countdown_timers = [];
    var _countdown_timers_initialized = false;

    function start_video(event) {
        _timer = window.setInterval(_varname+".step_video()", 500);
    }

    function stop_video(event) {
        window.clearInterval(_timer);
    }

    function step_video() {
        var cur_time = null;
        switch (_player_type) {
            case "jwplayer":
                cur_time = jwplayer(_place_tag_id).getPosition();
                break;
            case "flowplayer":
                cur_time = flowplayer(_place_tag_id).getTime();
                break;
            case "youtube":
                var player = document.getElementById(_inner_player_tag_id);
                cur_time = player.getCurrentTime();
                break;
            default:
                return;
        }
        init_countdown_timers();
        for (var i = 0; i < _countdown_timers.length; i++) {
            var timer_data = _countdown_timers[i];
            if (timer_data.start_time <= cur_time && cur_time <= timer_data.end_time) {
                var diff = Math.floor(timer_data.end_time - cur_time);
                var diff_min = Math.floor(diff / 60);
                var diff_sec = diff % 60;
                var timer_text = "";
                if (diff_min > 0) {
                    if (diff_min < 101) {
                        timer_text = get_timer_min_text(diff_min, diff_min, timer_data.type);
                    } else {
                        var diff_min_str = "" + diff_min;
                        var last_nums = diff_min_str.charAt(diff_min_str.length-2) + diff_min_str.charAt(diff_min_str.length-1);
                        timer_text = get_timer_minsec_text(last_nums, diff_min, timer_data.type, "min");
                    }
                }
                timer_text += get_timer_minsec_text(diff_sec, diff_sec, timer_data.type, "sec");
                var span = document.getElementById(timer_data.id);
                span.innerHTML = timer_text;
            }
        }
        for (var j = 0; j < _options.blocks_data.length; j++) {
            var block_data = _options.blocks_data[j];

            if (block_data.start_time <= cur_time && cur_time <= block_data.end_time)
                jsvg_animation.show_block(block_data.id, block_data.effect);
            else
               jsvg_animation.hide_block(block_data.id, block_data.effect);
        }
    }

    function get_timer_minsec_text(val, fullval, timer_type, type) {
        var result = "";
     /*   var strdata = {};
        strdata["min"] = [ " минута", " минуту", " минуты", " минут" ];
        strdata["sec"] = [ " секунда", " секунду", " секунды", " секунд" ];
        var strs = strdata[type];
        if (val < 21) {
            switch (val) {
                case 1:
                    if (timer_type == 1) {
                        result = fullval;
                    } else {
                        result = fullval;
                    }
                    break;
                case 2:
                case 3:
                case 4:
                    result = fullval;
                    break;
                default:
                    result = fullval;
            }
        } else if (val < 101) {
            var diff_min_str = ""+val;
            var last_num = parseInt(diff_min_str.charAt(diff_min_str.length-1));
            switch (last_num) {
                case 1:
                    if (timer_type == 1) {
                        result = fullval;
                    }else {
                        result = fullval;
                    }
                    break;
                case 2:
                case 3:
                case 4:
                    result = fullval;
                    break;
                default:

            }
        }  */
        result = fullval;
        return result;
    }

    function init_countdown_timers() {
        if (_countdown_timers_initialized)
            return;
        _countdown_timers_initialized = true;
        for (var i = 0; i < _options.blocks_data.length; i++) {
            var block_data = _options.blocks_data[i];
            var block = document.getElementById(block_data.id);
            var html = block.innerHTML;
            if (html.match(/\[countdown\]/)) {
                html = html.replace(/\[countdown\]/g, "<span id='ct-"+block_data.id+"'></span>");
                _countdown_timers.push({id: "ct-"+block_data.id, type: 1, start_time: block_data.start_time, end_time: block_data.end_time});
            }
            block.innerHTML = html;
        }
    }

    function add_event_listener() {
        var player = document.getElementById(_inner_player_tag_id);
        player.addEventListener("onStateChange", _varname + ".onStateChange");
    }

    function onStateChange(newState) {
        new_state = parseInt(newState);
        if (new_state == 0 || new_state == 2) {
            window.clearInterval(_timer);
        }
        if (new_state == 1) {
            _timer = window.setInterval(_varname + ".step_video()", 500);
        }
    }

    if (_player_type == "jwplayer") {
        var controls_pos = "over";
        if (_options.hide_controls) {
            controls_pos = "none";
        }
        var settings = {
            'flashplayer': 'https://pult.glamurnenko.ru/public/WebApp/resources/views/full/langs/ru_RU/types/extensions/EBilling/products/pages/imageschool/sale/jwplayer/player.swf',
            'id': _inner_player_tag_id,
            'width': _options.width,
            'height': _options.height,
            'file': _options.video,
            'autostart': _options.auto_play,
            'controlbar.position': controls_pos,
            events: {
                onPlay: start_video,
                onComplete: stop_video
            }
        };
        if (_options.image != "")
            settings["image"] = _options.image;
        jwplayer(_place_tag_id).setup(settings);
    } else if (_player_type == "flowplayer") {
        var playlist = [];
        if (_options.image != "")
            playlist.push({ url: _options.image, scaling: 'orig' });
        playlist.push({
            url: _options.video,
            autoPlay: _options.auto_play,
            autoBuffering: false,
            onStart: start_video,
            onFinish: stop_video
        });
        var settings2 = { playlist: playlist };
        if (_options.hide_controls) {
            settings2["plugins"] = { controls: null };
        }
        document.getElementById(_place_tag_id).innerHTML = "";
        flowplayer(_place_tag_id, "vsl/flowplayer-3.2.7.swf", settings2);
    } else if (_player_type == "youtube") {
        var params = { allowScriptAccess: "always" };
        var atts = { id: _inner_player_tag_id };
        var url_suffix = "";
        if (_options.auto_play) {
            url_suffix = url_suffix + "&autoplay=1";
        }
        if (_options.hide_controls) {
            url_suffix = url_suffix + "&controls=0";
        }
        swfobject.embedSWF("http://www.youtube.com/e/" + _options.video_id + "?enablejsapi=1&rel=0" + url_suffix,
            _place_tag_id, _options.width, _options.height, "8", null, null, params, atts,
            function(e) {
                if (e.success) {
                    window.setTimeout(_varname + ".add_event_listener()", 1000);
                }
            }
        );
    }

    return {step_video: step_video, add_event_listener: add_event_listener, onStateChange: onStateChange};
}
