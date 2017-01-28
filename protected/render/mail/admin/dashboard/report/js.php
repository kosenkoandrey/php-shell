<?
APP::$insert['js_flot'] = ['js', 'file', 'before', '</body>', APP::Module('Routing')->root . 'public/ui/vendors/bower_components/flot/jquery.flot.js'];
APP::$insert['js_flot_resize'] = ['js', 'file', 'before', '</body>', APP::Module('Routing')->root . 'public/ui/vendors/bower_components/flot/jquery.flot.resize.js'];
APP::$insert['js_flot_time'] = ['js', 'file', 'before', '</body>', APP::Module('Routing')->root . 'public/ui/vendors/bower_components/flot/jquery.flot.time.js'];
APP::$insert['js_moment'] = ['js', 'file', 'before', '</body>', APP::Module('Routing')->root . 'public/ui/vendors/bower_components/moment/min/moment.min.js'];
APP::$insert['js_datetimepicker'] = ['js', 'file', 'before', '</body>', APP::Module('Routing')->root . 'public/ui/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'];
ob_start();
?>
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
</script>
<?
APP::$insert['js_strtotime'] = ['js', 'code', 'before', '</body>', ob_get_contents()];
ob_end_clean();
?>
<script>
    function GetMailStat(nav) {
        $('#mail-stat-period > button').removeAttr('disabled');
        if (nav) $('#mail-stat-period > button[data-period="' + nav + '"]').attr('disabled', 'disabled');
        $('#mail-stat').html('<div class="text-center"><div class="preloader pl-xxl"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20" /></svg></div></div>');
        
        $.ajax({
            url: '<?= APP::Module('Routing')->root ?>admin/mail/api/stat/dashboard.json',
            data: {
                date: {
                    from: $('#mail-stat-date-from').val(),
                    to: $('#mail-stat-date-to').val()
                }
            },
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                data.delivered = data.delivered === undefined ? 0 : data.delivered;
                data.click = data.click === undefined ? 0 : data.click;
                data.open = data.open === undefined ? 0 : data.open;
                data.spamreport = data.spamreport === undefined ? 0 : data.spamreport;
                data.unsubscribe = data.unsubscribe === undefined ? 0 : data.unsubscribe;
                data.processed = data.processed === undefined ? 0 : data.processed;

                data.details.delivered = data.details.delivered === undefined ? 0 : data.details.delivered;
                data.details.click = data.details.click === undefined ? 0 : data.details.click;
                data.details.open = data.details.open === undefined ? 0 : data.details.open;
                data.details.spamreport = data.details.spamreport === undefined ? 0 : data.details.spamreport;
                data.details.unsubscribe = data.details.unsubscribe === undefined ? 0 : data.details.unsubscribe;
                data.details.processed = data.details.processed === undefined ? 0 : data.details.processed;

                $('#mail-stat')
                .html(
                    $('<table/>', {
                        id: 'letters-table',
                        class: 'table table-hover'
                    })
                    .css({
                        'margin-top': '30px'
                    })
                    .append(
                        $('<tbody/>')
                        .append(
                            $('<tr/>')
                            .append(
                                $('<td/>')
                                .css({
                                    width: '35%'
                                })
                                .append('Отправлено')
                            )
                            .append(
                                $('<td/>')
                                .css({
                                    width: '50%'
                                })
                                .append(
                                    $('<a/>', {
                                        'class': 'alink',
                                        'target': '_blank',
                                        'href': '#'
                                    })
                                    .append(data.details.processed)
                                )
                            )
                            .append(
                                $('<td/>')
                                .css({
                                    width: '15%'
                                })
                                .append('100.00%')
                            )
                        )
                        .append(
                            $('<tr/>')
                            .append(
                                $('<td/>')
                                .css({
                                    width: '35%'
                                })
                                .append('Доставлено')
                            )
                            .append(
                                $('<td/>')
                                .css({
                                    width: '50%'
                                })
                                .append(
                                    $('<a/>', {
                                        'class': 'alink',
                                        'target': '_blank',
                                        'href': '#'
                                    })
                                    .append(data.details.delivered)
                                )
                            )
                            .append(
                                $('<td/>')
                                .css({
                                    width: '15%'
                                })
                                .append(data.details.delivered ? (data.details.delivered / (data.details.delivered / 100)).toFixed(2) + '%' : '-')
                            )
                        )
                        .append(
                            $('<tr/>')
                            .append(
                                $('<td/>')
                                .css({
                                    width: '35%'
                                })
                                .append('Открыли')
                            )
                            .append(
                                $('<td/>')
                                .css({
                                    width: '50%'
                                })
                                .append(
                                    $('<a/>', {
                                        'class': 'alink',
                                        'target': '_blank',
                                        'href': '#'
                                    })
                                    .append(data.details.open)
                                )
                            )
                            .append(
                                $('<td/>')
                                .css({
                                    width: '15%'
                                })
                                .append(data.details.delivered ? (data.details.open / (data.details.delivered / 100)).toFixed(2) + '%' : '-')
                            )
                        )
                        .append(
                            $('<tr/>')
                            .append(
                                $('<td/>')
                                .css({
                                    width: '35%'
                                })
                                .append('Кликнули')
                            )
                            .append(
                                $('<td/>')
                                .css({
                                    width: '50%'
                                })
                                .append(
                                    $('<a/>', {
                                        'class': 'alink',
                                        'target': '_blank',
                                        'href': '#'
                                    })
                                    .append(data.details.click)
                                )
                            )
                            .append(
                                $('<td/>')
                                .css({
                                    width: '15%'
                                })
                                .append(data.details.delivered ? (data.details.click / (data.details.delivered / 100)).toFixed(2) + '%' : '-')
                            )
                        )
                        .append(
                            $('<tr/>')
                            .append(
                                $('<td/>')
                                .css({
                                    width: '35%'
                                })
                                .append('СПАМ')
                            )
                            .append(
                                $('<td/>')
                                .css({
                                    width: '50%'
                                })
                                .append(
                                    $('<a/>', {
                                        'class': 'alink',
                                        'target': '_blank',
                                        'href': '#'
                                    })
                                    .append(data.details.spamreport)
                                )
                            )
                            .append(
                                $('<td/>')
                                .css({
                                    width: '15%'
                                })
                                .append(data.details.delivered ? (data.details.spamreport / (data.details.delivered / 100)).toFixed(2) + '%' : '-')
                            )
                        )
                        .append(
                            $('<tr/>')
                            .append(
                                $('<td/>')
                                .css({
                                    width: '35%'
                                })
                                .append('Отписались')
                            )
                            .append(
                                $('<td/>')
                                .css({
                                    width: '50%'
                                })
                                .append(
                                    $('<a/>', {
                                        'class': 'alink',
                                        'target': '_blank',
                                        'href': '#'
                                    })
                                    .append(data.details.unsubscribe)
                                )
                            )
                            .append(
                                $('<td/>')
                                .css({
                                    width: '15%'
                                })
                                .append(data.details.delivered ? (data.details.unsubscribe / (data.details.delivered / 100)).toFixed(2) + '%' : '-')
                            )
                        )
                        .append(
                            $('<tr/>')
                            .append(
                                $('<td/>')
                                .css({
                                    width: '35%'
                                })
                                .append('Dropped')
                            )
                            .append(
                                $('<td/>', {
                                    id: 'dropped_data'
                                })
                                .css({
                                    width: '50%'
                                })
                            )
                            .append(
                                $('<td/>')
                                .css({
                                    width: '15%'
                                })
                                .append(data.details.delivered ? (data.dropped / (data.details.delivered / 100)).toFixed(2) + '%' : '-')
                            )
                        )
                        .append(
                            $('<tr/>')
                            .append(
                                $('<td/>')
                                .css({
                                    width: '35%'
                                })
                                .append('Bounce')
                            )
                            .append(
                                $('<td/>', {
                                    id: 'bounce_data'
                                })
                                .css({
                                    width: '50%'
                                })
                            )
                            .append(
                                $('<td/>')
                                .css({
                                    width: '15%'
                                })
                                .append(data.details.delivered ? (data.bounce / (data.details.delivered / 100)).toFixed(2) + '%' : '-')
                            )
                        )
                    )
                    .append(
                        $('<tbody/>')
                    )
                );

                if (data.details.dropped !== undefined) {
                    $('#dropped_data')
                    .append(
                        $('<table/>', {
                            class: 'table'
                        })
                        .append(
                            $('<thead/>')
                            .append(
                                $('<tr/>')
                                .append(
                                    $('<th/>')
                                    .css({
                                        width: '50%'
                                    })
                                    .append('Reason')
                                )
                                .append(
                                    $('<th/>')
                                    .css({
                                        width: '20%'
                                    })
                                    .append(
                                        $('<a/>', {
                                            'class': 'alink',
                                            'target': '_blank',
                                            'href': '#'
                                        })
                                        .append(data.dropped)
                                    )
                                )
                                .append(
                                    $('<th/>')
                                    .css({
                                        width: '20%'
                                    })
                                    .append('100%')
                                )
                            )
                        )
                        .append(
                            $('<tbody/>')
                        )
                    );

                    $.each(data.details.dropped, function(reason, value) {
                        $('#dropped_data tbody')
                        .append(
                            $('<tr/>')
                            .append(
                                $('<td/>')
                                .css({
                                    width: '50%'
                                })
                                .append(reason)
                            )
                            .append(
                                $('<td/>')
                                .css({
                                    width: '20%'
                                })
                                .append(value)
                            )
                            .append(
                                $('<td/>')
                                .css({
                                    width: '20%'
                                })
                                .append((parseInt(value) / (data.dropped / 100)).toFixed(2) + '%')
                            )
                        );
                    });
                } else {
                    $('#dropped_data').append(data.dropped);
                }

                if (data.details.bounce !== undefined) {
                    $('#bounce_data')
                    .append(
                        $('<table/>', {
                            class: 'table'
                        })
                        .append(
                            $('<thead/>')
                            .append(
                                $('<tr/>')
                                .append(
                                    $('<th/>')
                                    .css({
                                        width: '50%'
                                    })
                                    .append('Reason')
                                )
                                .append(
                                    $('<th/>')
                                    .css({
                                        width: '20%'
                                    })
                                    .append(
                                        $('<a/>', {
                                            'class': 'alink',
                                            'target': '_blank',
                                            'href': '#'
                                        })
                                        .append(data.bounce)
                                    )
                                )
                                .append(
                                    $('<th/>')
                                    .css({
                                        width: '20%'
                                    })
                                    .append('100%')
                                )
                            )
                        )
                        .append(
                            $('<tbody/>')
                        )
                    );

                    $.each(data.details.bounce, function(reason, value) {
                        $('#bounce_data tbody')
                        .append(
                            $('<tr/>')
                            .append(
                                $('<td/>')
                                .css({
                                    width: '50%'
                                })
                                .append(reason)
                            )
                            .append(
                                $('<td/>')
                                .css({
                                    width: '20%'
                                })
                                .append(value)
                            )
                            .append(
                                $('<td/>')
                                .css({
                                    width: '20%'
                                })
                                .append((parseInt(value) / (data.bounce / 100)).toFixed(2) + '%')
                            )
                        );
                    });
                } else {
                    $('#bounce_data').append(data.bounce);
                }
            } 
        });
    }

    $(document).on('click', "#mail-stat-period > button",function() {
        var period = $(this).data('period');

        var to = Math.round(new Date().getTime() / 1000);
        var from = strtotime("-" + period, to);

        var to_date = new Date(to * 1000);
        var from_date = new Date(from * 1000);

        $('#mail-stat-date-to').val(to);
        $('#mail-stat-date-from').val(from);

        $('#mail-stat-calendar-from').html(from_date.getDate() + '.' + (from_date.getMonth() + 1) + '.' + from_date.getFullYear());
        $('#mail-stat-calendar-to').html(to_date.getDate() + '.' + (to_date.getMonth() + 1) + '.' + to_date.getFullYear());

        GetMailStat(period);
    }); 

    $('#mail-stat-calendar').popover({
        html: true,
        content: [
            '<div class="form-group">',
                '<div class="row">',
                    '<div class="col-md-6">',
                        '<div id="mail-stat-calendar-from"></div>',
                    '</div>',
                    '<div class="col-md-6">',
                        '<div id="mail-stat-calendar-to"></div>',
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
        var to_date = new Date(parseInt($('#mail-stat-date-to').val()) * 1000);
        var from_date = new Date(parseInt($('#mail-stat-date-from').val()) * 1000);

        $('#mail-stat-calendar-from').datetimepicker({
            inline: true,
            sideBySide: true,
            format: 'DD/MM/YYYY'
        });
        $('#mail-stat-calendar-to').datetimepicker({
            useCurrent: false,
            inline: true,
            sideBySide: true,
            format: 'DD/MM/YYYY'
        });

        $('#mail-stat-calendar-from').on('dp.change', function(e) {
            $('#mail-stat-date-from').val(Math.round(e.date._d.getTime() / 1000));
            $('#mail-stat-period > button').removeAttr('disabled');
            $('#mail-stat-calendar-to').data('DateTimePicker').minDate(e.date);
            $('#mail-stat-calendar-from').html(e.date._d.getDate() + '.' + (e.date._d.getMonth() + 1) + '.' + e.date._d.getFullYear());
            GetMailStat(false);
        });
        $('#mail-stat-calendar-to').on('dp.change', function(e) {
            $('#mail-stat-date-to').val(Math.round(e.date._d.getTime() / 1000));
            $('#mail-stat-period > button').removeAttr('disabled');
            $('#mail-stat-calendar-from').data('DateTimePicker').maxDate(e.date);
            $('#mail-stat-calendar-to').html(e.date._d.getDate() + '.' + (e.date._d.getMonth() + 1) + '.' + e.date._d.getFullYear());
            GetMailStat(false);
        });

        $('#mail-stat-calendar-from').data('DateTimePicker').date(moment(from_date));
        $('#mail-stat-calendar-to').data('DateTimePicker').date(moment(to_date));
    });
    
    $(document).on('click', '#tab-nav-<?= $data['hash'] ?> > a',function() {
        $('#mail-stat-period > button[data-period="1 months"]').trigger('click');
    });
</script>