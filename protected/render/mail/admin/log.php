<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Mail</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
        
        <style>
            #mail-list-chart {
                width: 100%;
                height: 300px;
                font-size: 14px;
                line-height: 1.2em;
            }
            #log-table-header .actionBar .actions > button {
                display: none;
            }
        </style>
        
        <? APP::Render('core/widgets/css') ?>
    </head>
    <body data-ma-header="teal">
        <? 
        APP::Render('admin/widgets/header', 'include', [
            'Mail log' => 'admin/mail/log',
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            <h2>Log</h2>
                        </div>
                        <div class="card-body card-padding">
                            <div id="mail-log-period" class="btn-group m-b-15 m-r-15">
                                <button data-period="0 days" type="button" class="btn btn-default waves-effect">Today</button>
                                <button data-period="1 weeks" type="button" class="btn btn-default waves-effect">Week</button>
                                <button data-period="1 months" type="button" class="btn btn-default waves-effect">Month</button>
                                <button data-period="3 months" type="button" class="btn btn-default waves-effect">Quarter</button>
                                <button data-period="1 years" type="button" class="btn btn-default waves-effect">Year</button>
                            </div>
                            <div class="btn-group m-b-15">
                                <button id="mail-log-calendar" type="button" class="btn btn-default waves-effect"><i class="zmdi zmdi-calendar"></i> <span id="mail-log-calendar-from">...</span> - <span id="mail-log-calendar-to">...</span></button>
                            </div>
                            <div id="mail-list-chart">
                                <div class="text-center">
                                    <div class="preloader pl-xxl">
                                        <svg class="pl-circular" viewBox="25 25 50 50">
                                            <circle class="plc-path" cx="50" cy="50" r="20" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <input id="mail-list-date-from" type="hidden">
                            <input id="mail-list-date-to" type="hidden">
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-vmiddle" id="log-table">
                                <thead>
                                    <tr>
                                        <th data-column-id="id" data-visible="false" data-type="numeric" data-order="desc">ID</th>
                                        <th data-column-id="email" data-formatter="user">User</th>
                                        <th data-column-id="letter" data-formatter="letter">Letter</th>
                                        <th data-column-id="copies" data-formatter="copies" data-css-class="text-uppercase">Copies</th>
                                        <th data-column-id="sender" data-formatter="sender">Sender</th>
                                        <th data-column-id="transport" data-formatter="transport">Transport</th>
                                        <th data-column-id="state" data-css-class="text-uppercase">State</th>
                                        <th data-column-id="result" data-visible="false">Result</th>
                                        <th data-column-id="retries" data-visible="false">Retries</th>
                                        <th data-column-id="ping" data-visible="false">Ping</th>
                                        <th data-column-id="cr_date">Date</th>
                                        <th data-column-id="actions" data-formatter="actions">Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <? APP::Render('admin/widgets/footer') ?>
        </section>
        
        <div class="modal fade" id="mail-events-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Events associated with letter</h4>
                    </div>
                    <div class="modal-body" id="accordion"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <? APP::Render('core/widgets/page_loader') ?>
        <? APP::Render('core/widgets/ie_warning') ?>

        <!-- Javascript Libraries -->
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootgrid/jquery.bootgrid.updated.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/flot/jquery.flot.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/flot/jquery.flot.resize.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/flot/jquery.flot.time.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/moment/min/moment.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
        
        <? APP::Render('core/widgets/js') ?>
        
        <script>
            function strtotime(text, now) {
                var parsed
                var match
                var today
                var year
                var date
                var days
                var ranges
                var len
                var times
                var regex
                var i
                var fail = false

                if (!text) {
                  return fail
                }

                // Unecessary spaces
                text = text.replace(/^\s+|\s+$/g, '')
                  .replace(/\s{2,}/g, ' ')
                  .replace(/[\t\r\n]/g, '')
                  .toLowerCase()

                // in contrast to php, js Date.parse function interprets:
                // dates given as yyyy-mm-dd as in timezone: UTC,
                // dates with "." or "-" as MDY instead of DMY
                // dates with two-digit years differently
                // etc...etc...
                // ...therefore we manually parse lots of common date formats
                var pattern = new RegExp([
                  '^(\\d{1,4})',
                  '([\\-\\.\\/:])',
                  '(\\d{1,2})',
                  '([\\-\\.\\/:])',
                  '(\\d{1,4})',
                  '(?:\\s(\\d{1,2}):(\\d{2})?:?(\\d{2})?)?',
                  '(?:\\s([A-Z]+)?)?$'
                ].join(''))
                match = text.match(pattern)

                if (match && match[2] === match[4]) {
                  if (match[1] > 1901) {
                    switch (match[2]) {
                      case '-':
                        // YYYY-M-D
                        if (match[3] > 12 || match[5] > 31) {
                          return fail
                        }

                        return new Date(match[1], parseInt(match[3], 10) - 1, match[5],
                        match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000
                      case '.':
                        // YYYY.M.D is not parsed by strtotime()
                        return fail
                      case '/':
                        // YYYY/M/D
                        if (match[3] > 12 || match[5] > 31) {
                          return fail
                        }

                        return new Date(match[1], parseInt(match[3], 10) - 1, match[5],
                        match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000
                    }
                  } else if (match[5] > 1901) {
                    switch (match[2]) {
                      case '-':
                        // D-M-YYYY
                        if (match[3] > 12 || match[1] > 31) {
                          return fail
                        }

                        return new Date(match[5], parseInt(match[3], 10) - 1, match[1],
                        match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000
                      case '.':
                        // D.M.YYYY
                        if (match[3] > 12 || match[1] > 31) {
                          return fail
                        }

                        return new Date(match[5], parseInt(match[3], 10) - 1, match[1],
                        match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000
                      case '/':
                        // M/D/YYYY
                        if (match[1] > 12 || match[3] > 31) {
                          return fail
                        }

                        return new Date(match[5], parseInt(match[1], 10) - 1, match[3],
                        match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000
                    }
                  } else {
                    switch (match[2]) {
                      case '-':
                        // YY-M-D
                        if (match[3] > 12 || match[5] > 31 || (match[1] < 70 && match[1] > 38)) {
                          return fail
                        }

                        year = match[1] >= 0 && match[1] <= 38 ? +match[1] + 2000 : match[1]
                        return new Date(year, parseInt(match[3], 10) - 1, match[5],
                        match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000
                      case '.':
                        // D.M.YY or H.MM.SS
                        if (match[5] >= 70) {
                          // D.M.YY
                          if (match[3] > 12 || match[1] > 31) {
                            return fail
                          }

                          return new Date(match[5], parseInt(match[3], 10) - 1, match[1],
                          match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000
                        }
                        if (match[5] < 60 && !match[6]) {
                          // H.MM.SS
                          if (match[1] > 23 || match[3] > 59) {
                            return fail
                          }

                          today = new Date()
                          return new Date(today.getFullYear(), today.getMonth(), today.getDate(),
                          match[1] || 0, match[3] || 0, match[5] || 0, match[9] || 0) / 1000
                        }

                        // invalid format, cannot be parsed
                        return fail
                      case '/':
                        // M/D/YY
                        if (match[1] > 12 || match[3] > 31 || (match[5] < 70 && match[5] > 38)) {
                          return fail
                        }

                        year = match[5] >= 0 && match[5] <= 38 ? +match[5] + 2000 : match[5]
                        return new Date(year, parseInt(match[1], 10) - 1, match[3],
                        match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000
                      case ':':
                        // HH:MM:SS
                        if (match[1] > 23 || match[3] > 59 || match[5] > 59) {
                          return fail
                        }

                        today = new Date()
                        return new Date(today.getFullYear(), today.getMonth(), today.getDate(),
                        match[1] || 0, match[3] || 0, match[5] || 0) / 1000
                    }
                  }
                }

                // other formats and "now" should be parsed by Date.parse()
                if (text === 'now') {
                  return now === null || isNaN(now)
                    ? new Date().getTime() / 1000 | 0
                    : now | 0
                }
                if (!isNaN(parsed = Date.parse(text))) {
                  return parsed / 1000 | 0
                }
                // Browsers !== Chrome have problems parsing ISO 8601 date strings, as they do
                // not accept lower case characters, space, or shortened time zones.
                // Therefore, fix these problems and try again.
                // Examples:
                //   2015-04-15 20:33:59+02
                //   2015-04-15 20:33:59z
                //   2015-04-15t20:33:59+02:00
                pattern = new RegExp([
                  '^([0-9]{4}-[0-9]{2}-[0-9]{2})',
                  '[ t]',
                  '([0-9]{2}:[0-9]{2}:[0-9]{2}(\\.[0-9]+)?)',
                  '([\\+-][0-9]{2}(:[0-9]{2})?|z)'
                ].join(''))
                match = text.match(pattern)
                if (match) {
                  // @todo: time zone information
                  if (match[4] === 'z') {
                    match[4] = 'Z'
                  } else if (match[4].match(/^([\+-][0-9]{2})$/)) {
                    match[4] = match[4] + ':00'
                  }

                  if (!isNaN(parsed = Date.parse(match[1] + 'T' + match[2] + match[4]))) {
                    return parsed / 1000 | 0
                  }
                }

                date = now ? new Date(now * 1000) : new Date()
                days = {
                  'sun': 0,
                  'mon': 1,
                  'tue': 2,
                  'wed': 3,
                  'thu': 4,
                  'fri': 5,
                  'sat': 6
                }
                ranges = {
                  'yea': 'FullYear',
                  'mon': 'Month',
                  'day': 'Date',
                  'hou': 'Hours',
                  'min': 'Minutes',
                  'sec': 'Seconds'
                }

                function lastNext (type, range, modifier) {
                  var diff
                  var day = days[range]

                  if (typeof day !== 'undefined') {
                    diff = day - date.getDay()

                    if (diff === 0) {
                      diff = 7 * modifier
                    } else if (diff > 0 && type === 'last') {
                      diff -= 7
                    } else if (diff < 0 && type === 'next') {
                      diff += 7
                    }

                    date.setDate(date.getDate() + diff)
                  }
                }

                function process (val) {
                  // @todo: Reconcile this with regex using \s, taking into account
                  // browser issues with split and regexes
                  var splt = val.split(' ')
                  var type = splt[0]
                  var range = splt[1].substring(0, 3)
                  var typeIsNumber = /\d+/.test(type)
                  var ago = splt[2] === 'ago'
                  var num = (type === 'last' ? -1 : 1) * (ago ? -1 : 1)

                  if (typeIsNumber) {
                    num *= parseInt(type, 10)
                  }

                  if (ranges.hasOwnProperty(range) && !splt[1].match(/^mon(day|\.)?$/i)) {
                    return date['set' + ranges[range]](date['get' + ranges[range]]() + num)
                  }

                  if (range === 'wee') {
                    return date.setDate(date.getDate() + (num * 7))
                  }

                  if (type === 'next' || type === 'last') {
                    lastNext(type, range, num)
                  } else if (!typeIsNumber) {
                    return false
                  }

                  return true
                }

                times = '(years?|months?|weeks?|days?|hours?|minutes?|min|seconds?|sec' +
                  '|sunday|sun\\.?|monday|mon\\.?|tuesday|tue\\.?|wednesday|wed\\.?' +
                  '|thursday|thu\\.?|friday|fri\\.?|saturday|sat\\.?)'
                regex = '([+-]?\\d+\\s' + times + '|' + '(last|next)\\s' + times + ')(\\sago)?'

                match = text.match(new RegExp(regex, 'gi'))
                if (!match) {
                  return fail
                }

                for (i = 0, len = match.length; i < len; i++) {
                  if (!process(match[i])) {
                    return fail
                  }
                }

                return (date.getTime() / 1000)
            }
    
            function GetMailLog(nav) {
                $('#mail-log-period > button').removeAttr('disabled');
                if (nav) $('#mail-log-period > button[data-period="' + nav + '"]').attr('disabled', 'disabled');
                $('#mail-list-chart').html('<div class="text-center"><div class="preloader pl-xxl"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20" /></svg></div></div>');

                $.ajax({
                    url: '<?= APP::Module('Routing')->root ?>admin/mail/api/log/dashboard.json',
                    data: {
                        date: {
                            from: $('#mail-list-date-from').val(),
                            to: $('#mail-list-date-to').val()
                        }
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(data) {
                        $.plot("#mail-list-chart", [
                            { data: data.wait, label: 'Wait' },
                            { data: data.success, label: 'Success'},
                            { data: data.error, label: 'Error' },
                        ], {
                            series: {
                                lines: {
                                    show: true
                                },
                                points: {
                                    show: true
                                }
                            },
                            grid : {
                                borderWidth: 1,
                                borderColor: '#eee',
                                show : true,
                                hoverable : true,
                                clickable : true
                            },
                            yaxis: { 
                                tickColor: '#eee',
                                tickDecimals: 0,
                                font :{
                                    lineHeight: 13,
                                    style: "normal",
                                    color: "#9f9f9f",
                                },
                                shadowSize: 0
                            },
                            xaxis: {
                                mode: 'time',
                                tickColor: '#fff',
                                tickDecimals: 0,
                                font :{
                                    lineHeight: 13,
                                    style: 'normal',
                                    color: '#9f9f9f'
                                },
                                shadowSize: 0
                            }
                        });

                        $('<div id="card-<?= $data['hash'] ?>-tooltip"></div>').css({
                            position: "absolute",
                            display: "none",
                            border: "1px solid #fdd",
                            padding: "2px",
                            "background-color": "#fee",
                            opacity: 0.80
                        }).appendTo("body");

                        $("#mail-list-chart").bind("plothover", function (event, pos, item) {
                            if (item) {
                                var date = new Date(item.datapoint[0]);

                                $("#card-<?= $data['hash'] ?>-tooltip")
                                .html(item.datapoint[1] + ' ' + item.series.label + ' of ' + date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear())
                                .css({
                                    top: item.pageY+5, 
                                    left: item.pageX+5
                                })
                                .fadeIn(200);
                            } else {
                                $("#card-<?= $data['hash'] ?>-tooltip").hide();
                            }
                        });
                    } 
                });
            }

            $(document).on('click', "#mail-log-period > button",function() {
                var period = $(this).data('period');

                var to = Math.round(new Date().getTime() / 1000);
                var from = strtotime("-" + period, to);

                var to_date = new Date(to * 1000);
                var from_date = new Date(from * 1000);

                $('#mail-list-date-to').val(to);
                $('#mail-list-date-from').val(from);

                $('#mail-log-calendar-from').html(from_date.getDate() + '.' + (from_date.getMonth() + 1) + '.' + from_date.getFullYear());
                $('#mail-log-calendar-to').html(to_date.getDate() + '.' + (to_date.getMonth() + 1) + '.' + to_date.getFullYear());

                GetMailLog(period);
            }); 

            $('#mail-log-calendar').popover({
                html: true,
                content: [
                    '<div class="form-group">',
                        '<div class="row">',
                            '<div class="col-md-6">',
                                '<div id="mail-list-calendar-from"></div>',
                            '</div>',
                            '<div class="col-md-6">',
                                '<div id="mail-list-calendar-to"></div>',
                            '</div>',
                        '</div>',
                    '</div>'
                ].join(''),
                placement: 'bottom',
                title: 'Set date range',
                trigger: 'click'
            }).on('show.bs.popover', function() { 
                $(this).data('bs.popover').tip().css({
                    'max-width': '640px',
                    'width': '640px'
                });
            }).on('shown.bs.popover', function() { 
                var to_date = new Date(parseInt($('#mail-list-date-to').val()) * 1000);
                var from_date = new Date(parseInt($('#mail-list-date-from').val()) * 1000);

                $('#mail-list-calendar-from').datetimepicker({
                    inline: true,
                    sideBySide: true,
                    format: 'DD/MM/YYYY'
                });
                $('#mail-list-calendar-to').datetimepicker({
                    useCurrent: false,
                    inline: true,
                    sideBySide: true,
                    format: 'DD/MM/YYYY'
                });

                $('#mail-list-calendar-from').on('dp.change', function(e) {
                    $('#mail-list-date-from').val(Math.round(e.date._d.getTime() / 1000));
                    $('#mail-log-period > button').removeAttr('disabled');
                    $('#mail-list-calendar-to').data('DateTimePicker').minDate(e.date);
                    $('#mail-log-calendar-from').html(e.date._d.getDate() + '.' + (e.date._d.getMonth() + 1) + '.' + e.date._d.getFullYear());
                    GetMailLog(false);
                });
                $('#mail-list-calendar-to').on('dp.change', function(e) {
                    $('#mail-list-date-to').val(Math.round(e.date._d.getTime() / 1000));
                    $('#mail-log-period > button').removeAttr('disabled');
                    $('#mail-list-calendar-from').data('DateTimePicker').maxDate(e.date);
                    $('#mail-log-calendar-to').html(e.date._d.getDate() + '.' + (e.date._d.getMonth() + 1) + '.' + e.date._d.getFullYear());
                    GetMailLog(false);
                });

                $('#mail-list-calendar-from').data('DateTimePicker').date(moment(from_date));
                $('#mail-list-calendar-to').data('DateTimePicker').date(moment(to_date));
            });

            $('#mail-log-period > button[data-period="1 months"]').trigger('click');
            
            $(document).ready(function() {
                var log_table = $("#log-table").bootgrid({
                    ajax: true,
                    ajaxSettings: {
                        method: 'POST',
                        cache: false
                    },
                    url: '<?= APP::Module('Routing')->root ?>admin/mail/api/log/list.json',
                    css: {
                        icon: 'zmdi icon',
                        iconColumns: 'zmdi-view-module',
                        iconDown: 'zmdi-chevron-down pull-left',
                        iconRefresh: 'zmdi-refresh',
                        iconUp: 'zmdi-chevron-up pull-left'
                    },
                    formatters: {
                        user: function(column, row) {
                            return  '<a href="<?= APP::Module('Routing')->root ?>admin/users/profile/' + row.user + '" target="_blank">' + row.email + '</a>';
                        },
                        letter: function(column, row) {
                            return  '<a href="<?= APP::Module('Routing')->root ?>admin/mail/letters/' + row.letter_group[1] + '/edit/' + row.letter[1] + '" target="_blank">' + row.subject + '</a>';
                        },
                        copies: function(column, row) {
                            return  parseInt(row.copies) ? '<div class="btn-group btn-group-xs" role="group"><a href="<?= APP::Module('Routing')->root ?>mail/html/' + row.id_token + '" target="_blank" class="btn btn-default waves-effect">HTML</a><a href="<?= APP::Module('Routing')->root ?>mail/plaintext/' + row.id_token + '" target="_blank" class="btn btn-default waves-effect">PLAINTEXT</a></div>' : 'none';
                        },
                        sender: function(column, row) {
                            return  '<a href="<?= APP::Module('Routing')->root ?>admin/mail/senders/' + row.sender_group[1] + '/edit/' + row.sender[1] + '" target="_blank">' + row.sender_name + ' (' + row.sender_email + ')</a>';
                        },
                        transport: function(column, row) {
                            return  '<a href="<?= APP::Module('Routing')->root ?>' + row.transport_settings + '" target="_blank">' + row.transport_module + ' / ' + row.transport_method + '</a>';
                        },
                        actions: function(column, row) {
                            var events_icon = parseInt(row.events) ? 'notifications-active' : 'notifications-none';
                            
                            return  '<a href="javascript:void(0)" data-token="' + row.id_token + '" class="mail-events btn btn-sm btn-default btn-icon waves-effect waves-circle"><span class="zmdi zmdi-' + events_icon + '"></span></a> ' +
                                    '<a href="javascript:void(0)" class="btn btn-sm btn-default btn-icon waves-effect waves-circle remove-log" data-log-id="' + row.id + '"><span class="zmdi zmdi-delete"></span></a>';
                        }
                    }
                }).on('loaded.rs.jquery.bootgrid', function () {
                    log_table.find('.mail-events').on('click', function (e) {
                        var token = $(this).data('token');
                        
                        $('#mail-events-modal .modal-body').html('<center><div class="preloader pl-xxl"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20" /></svg></div></center>');
                        $('#mail-events-modal').modal('show');
                        
                        $.ajax({
                            type: 'post',
                            url: '<?= APP::Module('Routing')->root ?>admin/mail/api/events/list.json',
                            data: {
                                token: token
                            },
                            success: function(events) {
                                if (events.length) {
                                    $('#mail-events-modal .modal-body').empty();
                                
                                    $.each(events, function(key, event) {
                                        var details = event.details !== 'NULL' ? JSON.stringify(JSON.parse(event.details), undefined, 4) : 'Details not found';
                                        
                                        $('#mail-events-modal .modal-body').append([
                                            '<div class="panel panel-collapse">',
                                                '<div class="panel-heading" role="tab" id="heading-mail-event-' + key + '">',
                                                    '<h4 class="panel-title">',
                                                        '<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-mail-event-' + key + '" aria-expanded="false" aria-controls="collapse-mail-event-' + key + '"><span class="pull-right">' + event.cr_date + '</span>' + event.event + '</a>',
                                                    '</h4>',
                                                '</div>',
                                                '<div id="collapse-mail-event-' + key + '" class="collapse" role="tabpanel" aria-labelledby="collapse-mail-event-' + key + '">',
                                                    '<div class="panel-body"><pre>' + details + '</pre></div>',
                                                '</div>',
                                            '</div>'
                                        ].join(''));
                                    });
                                } else {
                                    $('#mail-events-modal .modal-body').html('<div class="alert alert-warning" role="alert">Events not found</div>');
                                }
                            }
                        });
                    });
                });
            });
        </script>
    </body>
</html>