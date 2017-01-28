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
    function GetInvoices(nav) {
        $('#billing-period > button').removeAttr('disabled');
        if (nav) $('#billing-period > button[data-period="' + nav + '"]').attr('disabled', 'disabled');
        $('#billing-chart').html('<div class="text-center"><div class="preloader pl-xxl"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20" /></svg></div></div>');
        
        $.ajax({
            url: '<?= APP::Module('Routing')->root ?>admin/billing/api/dashboard.json',
            data: {
                date: {
                    from: $('#billing-date-from').val(),
                    to: $('#billing-date-to').val()
                }
            },
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                $.plot("#billing-chart", [
                    { 
                        label: "Новые", 
                        data: data.new 
                    },
                    { 
                        label: "В обработке", 
                        data: data.processed 
                    },
                    { 
                        label: "Аннулированные", 
                        data: data.revoked 
                    },
                    { 
                        label: "Оплаченные", 
                        data: data.success 
                    }
                ], {
                    series: {
                        lines: {
                            show: true
                        },
                        points: {
                            show: true
                        }
                    },
                    legend: {
                        noColumns: 2
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
                        mode: "time",
                        tickColor: '#fff',
                        tickDecimals: 0,
                        font :{
                            lineHeight: 13,
                            style: "normal",
                            color: "#9f9f9f"
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

		$("#billing-chart").bind("plothover", function (event, pos, item) {
                    if (item) {
                        var date = new Date(item.datapoint[0]);

                        $("#card-<?= $data['hash'] ?>-tooltip")
                        .html(item.datapoint[1] + ' ' + item.series.label + ' - ' + date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear())
                        .css({
                            top: item.pageY+5, 
                            left: item.pageX+5
                        })
                        .fadeIn(200);
                    } else {
                        $("#card-<?= $data['hash'] ?>-tooltip").hide();
                    }
		});
                
                $('#billing-list').html([
                    '<div class="table-responsive m-b-25">',
                        '<table id="billing-invoices-table" class="table table-hover">',
                            '<thead>',
                                '<tr>',
                                    '<th width="20%">Дата</th>',
                                    '<th width="15%">Новые</th>',
                                    '<th width="15%">В обработке</th>',
                                    '<th width="15%">Аннулированные</th>',
                                    '<th width="15%">Оплаченные</th>',
                                    '<th width="20%">Заработано</th>',
                                '</tr>',
                            '</thead>',
                            '<tbody>',
                                '<tr class="total">',
                                    '<td></td>',
                                    '<td class="t_new"><a href="#"></a></td>',
                                    '<td class="t_processed"><a href="#"></a></td>',
                                    '<td class="t_revoked"><a href="#"></a></td>',
                                    '<td class="t_success"><a href="#"></a></td>',
                                    '<td class="t_profit"></td>',
                                '</tr>',
                            '</tbody>',
                        '</table>',
                    '</div>'
                ].join(''));
                
                var billing_invoices = [0, 0, 0, 0, 0];
                
                $.each(data.success, function(key, invoices) {
                    $('#billing-invoices-table > tbody').prepend([
                        '<tr>',
                            '<td>' + moment.unix(parseInt(invoices[0]) / 1000).format('DD-MM-YYYY') + '</td>',
                            '<td><a href="#" target="_blank">' + data.new[key][1] + '</a></td>',
                            '<td><a href="#" target="_blank">' + data.processed[key][1] + '</a></td>',
                            '<td><a href="#" target="_blank">' + data.revoked[key][1] + '</a></td>',
                            '<td><a href="#" target="_blank">' + invoices[1] + '</a></td>',
                            '<td>' + invoices[2] + ' руб.</td>',
                        '</tr>'
                    ].join(''));
                    
                    billing_invoices[0] += data.new[key][1];
                    billing_invoices[1] += data.processed[key][1];
                    billing_invoices[2] += data.revoked[key][1];
                    billing_invoices[3] += data.success[key][1];
                    billing_invoices[4] += invoices[2];
                });
                
                $('#billing-invoices-table .total .t_new a').html(billing_invoices[0]);
                $('#billing-invoices-table .total .t_processed a').html(billing_invoices[1]);
                $('#billing-invoices-table .total .t_revoked a').html(billing_invoices[2]);
                $('#billing-invoices-table .total .t_success a').html(billing_invoices[3]);
                $('#billing-invoices-table .total .t_profit').html(billing_invoices[4] + ' руб.');
            } 
        });
    }

    $(document).on('click', "#billing-period > button",function() {
        var period = $(this).data('period');

        var to = Math.round(new Date().getTime() / 1000);
        var from = strtotime("-" + period, to);

        var to_date = new Date(to * 1000);
        var from_date = new Date(from * 1000);

        $('#billing-date-to').val(to);
        $('#billing-date-from').val(from);

        $('#billing-calendar-from').html(from_date.getDate() + '.' + (from_date.getMonth() + 1) + '.' + from_date.getFullYear());
        $('#billing-calendar-to').html(to_date.getDate() + '.' + (to_date.getMonth() + 1) + '.' + to_date.getFullYear());

        GetInvoices(period);
    }); 

    $('#billing-calendar').popover({
        html: true,
        content: [
            '<div class="form-group">',
                '<div class="row">',
                    '<div class="col-md-6">',
                        '<div id="billing-calendar-from"></div>',
                    '</div>',
                    '<div class="col-md-6">',
                        '<div id="billing-calendar-to"></div>',
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
        var to_date = new Date(parseInt($('#billing-date-to').val()) * 1000);
        var from_date = new Date(parseInt($('#billing-date-from').val()) * 1000);

        $('#billing-calendar-from').datetimepicker({
            inline: true,
            sideBySide: true,
            format: 'DD/MM/YYYY'
        });
        $('#billing-calendar-to').datetimepicker({
            useCurrent: false,
            inline: true,
            sideBySide: true,
            format: 'DD/MM/YYYY'
        });

        $('#billing-calendar-from').on('dp.change', function(e) {
            $('#billing-date-from').val(Math.round(e.date._d.getTime() / 1000));
            $('#billing-period > button').removeAttr('disabled');
            $('#billing-calendar-to').data('DateTimePicker').minDate(e.date);
            $('#billing-calendar-from').html(e.date._d.getDate() + '.' + (e.date._d.getMonth() + 1) + '.' + e.date._d.getFullYear());
            GetInvoices(false);
        });
        $('#billing-calendar-to').on('dp.change', function(e) {
            $('#billing-date-to').val(Math.round(e.date._d.getTime() / 1000));
            $('#billing-period > button').removeAttr('disabled');
            $('#billing-calendar-from').data('DateTimePicker').maxDate(e.date);
            $('#billing-calendar-to').html(e.date._d.getDate() + '.' + (e.date._d.getMonth() + 1) + '.' + e.date._d.getFullYear());
            GetInvoices(false);
        });

        $('#billing-calendar-from').data('DateTimePicker').date(moment(from_date));
        $('#billing-calendar-to').data('DateTimePicker').date(moment(to_date));
    });
    
    $(document).on('click', '#tab-nav-<?= $data['hash'] ?> > a',function() {
        $('#billing-period > button[data-period="1 months"]').trigger('click');
    });
</script>